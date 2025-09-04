<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PrivateChatController extends Controller
{
    /**
     * Ouvre une conversation privée entre l'utilisateur connecté et un autre utilisateur.
     */
    public function openConversation(User $user)
    {
        $currentUser = Auth::user();

        // Empêche l'utilisateur d'ouvrir une conversation avec lui-même
        if ($user->id === $currentUser->id) {
            abort(403, "Vous ne pouvez pas discuter avec vous-même.");
        }

        // Recherche d'une conversation entre les deux utilisateurs
        $conversation = Conversation::whereHas('users', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id);
        })->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        // Si aucune conversation n'existe, on la crée
        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$currentUser->id, $user->id]);
        }

        // Redirige vers la page d'affichage des messages de cette conversation
        return redirect()->route('chat.show', $conversation->id);
    }

    /**
     * Affiche les messages d'une conversation.
     */
    public function show(Conversation $conversation)
    {
        // Vérifie que l'utilisateur appartient à la conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        // Met à jour last_read_at quand l'utilisateur consulte la conversation
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'last_read_at' => now(),
        ]);

        $conversation->load(['messages.user', 'users']);

        return view('etudiant.showchat', compact('conversation'));
    }

    /**
     * Envoie un message dans une conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // Vérifie que l'utilisateur fait bien partie de la conversation
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json([
                'message' => 'Access denied to this conversation.'
            ], 403);
        }

        $message = $conversation->messages()->create([
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        // Charge la relation user pour la réponse
        $message->load('user');

        // Met à jour la date de lecture
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'last_read_at' => now(),
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'body' => $message->body,
                'user_id' => $message->user_id,
                'created_at' => $message->created_at,
                'user' => [
                    'name' => $message->user->name
                ]
            ]
        ]);
    }

    /**
     * Supprime un message.
     */
 public function deleteMessage(Message $message)
    {
        // Verify the user is the author of the message
        if ($message->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You can only delete your own messages.'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Met à jour un message.
     */
  public function updateMessage(Request $request, Message $message)
    {
        // Verify the user is the author of the message
        if ($message->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You can only edit your own messages.'
            ], 403);
        }

        $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        $message->update([
            'body' => $request->body,
            'edited_at' => now()
        ]);

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message
        ]);
    }
}