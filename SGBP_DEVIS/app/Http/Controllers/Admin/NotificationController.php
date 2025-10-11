<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->marquerCommeLue();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
