<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $unreadNotifications = $user->unreadNotifications()->latest()->get();
        $readNotifications = $user->readNotifications()->latest()->paginate(15);

        return view('notifications.index', compact('unreadNotifications', 'readNotifications'));
    }

    public function read($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('dashboard'));
    }
}
