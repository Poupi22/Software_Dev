<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Forum;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // Récupération des autres utilisateurs avec conversations
        $users = User::where('id', '!=', $currentUser->id)
            ->with(['roles', 'conversations.messages'])
            ->get()
            ->map(function ($user) use ($currentUser) {
                $conversation = $user->conversations()
                    ->whereHas('users', fn ($q) => $q->where('user_id', $currentUser->id))
                    ->first();

                $unread = 0;
                $lastMessageAt = null;
                $lastMessagePreview = null;

                if ($conversation) {
                    $pivot = $conversation->users()
                        ->where('user_id', $currentUser->id)
                        ->first()
                        ->pivot ?? null;

                    $lastRead = $pivot?->last_read_at;

                    $unread = $conversation->messages()
                        ->where('user_id', $user->id)
                        ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
                        ->count();

                    $lastMessage = $conversation->messages()->latest()->first();
                    $lastMessageAt = $lastMessage?->created_at;
                    $lastMessagePreview = $lastMessage?->body;
                }

                $user->unread_messages = $unread;
                $user->last_message_at = $lastMessageAt;
                $user->last_message_preview = $lastMessagePreview;
                
                // ✅ FIXED: Properly store role name
                $user->role_name = $user->roles->first()?->name ?? 'Utilisateur';

                return $user;
            })
            ->sortByDesc('last_message_at')
            ->values();

        // Total des messages non lus
        $totalUnreadMessages = $users->sum('unread_messages');

        // Forums selon rôle - FIXED LOGIC
        if ($currentUser->hasRole(['administrateur'])) {
            $forums = Forum::withCount('threads')
                ->with('formation')
                ->get();
        } elseif ($currentUser->hasRole(['Etudiant'])) {
            // NEW: Better way to get student's forums
            $forums = $this->getStudentForums($currentUser);
        } else {
            $forums = collect();
        }

        return view('etudiant.chat', compact('users', 'forums', 'totalUnreadMessages'));
    }

    /**
     * Get forums for a student based on their enrollment
     */
    protected function getStudentForums(User $student)
    {
        // Method 1: Direct query through relationships
        $formationId = Inscription::where('user_id', $student->id)
            ->with(['programmeSession.programme.formation'])
            ->first()
            ?->programmeSession
            ?->programme
            ?->formation
            ?->id;

        if ($formationId) {
            return Forum::where('formation_id', $formationId)
                ->withCount('threads')
                ->with('formation')
                ->get();
        }

        // Method 2: Alternative approach if above fails
        $formationId = $student->inscriptions()
            ->first()
            ?->programmeSession
            ?->programme
            ?->formation
            ?->id;

        if ($formationId) {
            return Forum::where('formation_id', $formationId)
                ->withCount('threads')
                ->with('formation')
                ->get();
        }

        // Method 3: Simplified join query as fallback
        $formationId = Inscription::join('programme_sessions', 'inscriptions.programme_session_id', '=', 'programme_sessions.id')
            ->join('programmes', 'programme_sessions.programme_id', '=', 'programmes.id')
            ->where('inscriptions.user_id', $student->id)
            ->value('programmes.formation_id');

        if ($formationId) {
            return Forum::where('formation_id', $formationId)
                ->withCount('threads')
                ->with('formation')
                ->get();
        }

        // Return empty collection if no forums found
        return collect();
    }
}