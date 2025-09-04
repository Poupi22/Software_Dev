<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Injecter le nombre total de messages non lus dans toutes les vues
        View::composer('*', function ($view) {
            $user = Auth::user();
            $totalUnreadMessages = 0;

            if ($user) {
                $users = User::where('id', '!=', $user->id)
                    ->with(['roles', 'conversations.messages'])
                    ->get()
                    ->map(function ($otherUser) use ($user) {
                        $conversation = $otherUser->conversations()
                            ->whereHas('users', fn ($q) => $q->where('user_id', $user->id))
                            ->first();

                        $unread = 0;

                        if ($conversation) {
                            $pivot = $conversation->users()
                                ->where('user_id', $user->id)
                                ->first()?->pivot;

                            $lastRead = $pivot?->last_read_at;

                            $unread = $conversation->messages()
                                ->where('user_id', $otherUser->id)
                                ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
                                ->count();
                        }

                        $otherUser->unread_messages = $unread;
                        return $otherUser;
                    });

                $totalUnreadMessages = $users->sum('unread_messages');
            }

            $view->with('totalUnreadMessages', $totalUnreadMessages);
        });
    }
}
