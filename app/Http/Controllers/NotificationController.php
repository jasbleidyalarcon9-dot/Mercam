<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        // auth()->user()->notifications ya viene filtrado por notifiable_id
        // = el usuario autenticado: nadie puede ver notificaciones ajenas.
        $notificaciones = auth()->user()->notifications()->paginate(15);

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(string $id)
    {
        $notificacion = auth()->user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        return back();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
