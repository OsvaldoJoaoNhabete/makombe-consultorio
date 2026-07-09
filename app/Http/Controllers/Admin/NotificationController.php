<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $stats = [
            'total' => Auth::user()->notifications()->count(),
            'nao_lidas' => Auth::user()->unreadNotifications()->count(),
            'lidas' => Auth::user()->notifications()->whereNotNull('read_at')->count(),
            'hoje' => Auth::user()->notifications()->whereDate('created_at', today())->count(),
        ];
        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->route('notifications.index');
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        return back()->with('success', 'Notificação marcada como lida.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas marcadas como lidas.');
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notificação excluída.');
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'Todas as notificações foram removidas.');
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}