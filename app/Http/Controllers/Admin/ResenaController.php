<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resena;

class ResenaController extends Controller
{
    public function index()
    {
        $resenas = Resena::with(['user', 'post.user'])
            ->latest()
            ->paginate(20);

        return view('admin.resenas', compact('resenas'));
    }

    public function destroy(Resena $resena)
    {
        $this->authorize('delete', $resena);

        $resena->delete();

        return back()->with('success', 'Reseña eliminada.');
    }
}