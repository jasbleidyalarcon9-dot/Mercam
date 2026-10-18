<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ResenaController as AdminResenaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Productor\DashboardController as ProductorDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResenaController;
use Illuminate\Support\Facades\Route;

// ---------- Público (catálogo abierto a cualquier visitante) ----------
Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::view('/politica-de-datos', 'legal.politica-datos')->name('legal.politica-datos');
Route::view('/manual-de-uso', 'legal.manual-uso')->name('manual.uso');

// Asistente virtual "Campi" (Gemini). Público para visitantes y usuarios;
// limitado a 15 mensajes por minuto por IP/usuario para cuidar la cuota de la API.
Route::view('/asistente', 'asistente.index')->name('asistente.index');
Route::post('/asistente/mensaje', [ChatbotController::class, 'mensaje'])
    ->middleware('throttle:15,1')
    ->name('asistente.mensaje');

Route::get('/login', [LoginController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');

Route::get('/register', [RegisterController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

// Recuperar contraseña (usa el broker nativo de Laravel; funciona con
// MAIL_MAILER=log sin necesidad de un SMTP real)
Route::middleware('guest')->group(function () {
    Route::get('/olvide-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/olvide-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/restablecer-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/restablecer-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Verificación de correo: necesita sesión iniciada, pero a propósito NO
// lleva el middleware 'verified' (si no, nadie podría llegar aquí nunca).
// Logout vive aquí también por la misma razón: cerrar sesión debe
// funcionar SIEMPRE, esté el correo verificado o no.
Route::middleware(['auth', 'cuenta.activa'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::put('/email/actualizar', [EmailVerificationController::class, 'actualizarCorreo'])
        ->name('verification.actualizar-correo');
});

// ---------- Requiere sesión iniciada Y correo confirmado ----------
Route::middleware(['auth', 'verified', 'cuenta.activa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//...carrito, pedidos, mis-productos, admin, etc.

    // Reseñas: siempre colgadas de una publicación
    Route::post('/posts/{post:slug}/resenas', [ResenaController::class, 'store'])->name('resenas.store');
    Route::put('/resenas/{resena}', [ResenaController::class, 'update'])->name('resenas.update');
    Route::delete('/resenas/{resena}', [ResenaController::class, 'destroy'])->name('resenas.destroy');

    // Perfil (cambio de contraseña estando logueado; distinto del reset por correo)
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil/nombre', [ProfileController::class, 'updateName'])->name('profile.update-name');
    Route::post('/perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/perfil/password', [PasswordController::class, 'edit'])->name('perfil.password.edit');
    Route::put('/perfil/password', [PasswordController::class, 'update'])->name('perfil.password.update');

    // Notificaciones: cada quien ve/marca solo las suyas
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notificaciones.index');
    Route::put('/notificaciones/{id}/leida', [NotificationController::class, 'marcarLeida'])->name('notificaciones.marcarLeida');
    Route::put('/notificaciones/leidas', [NotificationController::class, 'marcarTodasLeidas'])->name('notificaciones.marcarTodas');

    // Carrito de compras: cada usuario solo ve/mueve el suyo (va por sesión)
    // OJO de orden: /carrito/checkout debe ir ANTES de /carrito/{post},
    // si no la ruta comodín {post} intercepta "checkout" como si fuera un id.
    Route::get('/carrito', [CartController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/checkout', [CartController::class, 'checkout'])->name('carrito.checkout');
    Route::post('/carrito/{post}', [CartController::class, 'store'])->name('carrito.store');
    Route::put('/carrito/{post}', [CartController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/{post}', [CartController::class, 'destroy'])->name('carrito.destroy');

    // Mis pedidos como comprador: solo los propios (PedidoPolicy)
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::put('/pedidos/detalle/{detalle}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');

    // ---------- Solo productores (y administrador) ----------
    Route::middleware('role:productor,administrador')->group(function () {
        // "Mis productos": solo los propios, incluidos borradores
        Route::get('/mis-productos', [PostController::class, 'mine'])->name('posts.mine');
        Route::get('/productos/crear', [PostController::class, 'create'])->name('posts.create');
        Route::post('/productos', [PostController::class, 'store'])->name('posts.store');
        Route::get('/productos/{post:slug}/editar', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/productos/{post:slug}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/productos/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');

        // Panel del productor: métricas + pedidos recibidos (solo sus renglones)
        Route::get('/productor', [ProductorDashboardController::class, 'index'])->name('productor.dashboard');
        Route::get('/productor/pedidos', [ProductorDashboardController::class, 'pedidos'])->name('productor.pedidos');
        Route::put('/productor/pedidos/{detalle}', [ProductorDashboardController::class, 'actualizarEstado'])
            ->name('productor.pedidos.estado');
    });

    // ---------- Solo administrador ----------
    Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
        Route::put('/usuarios/{user}/rol', [AdminUserController::class, 'actualizarRol'])->name('usuarios.rol');
        Route::put('/usuarios/{user}/suspender', [AdminUserController::class, 'suspender'])->name('usuarios.suspender');
        Route::put('/usuarios/{user}/activar', [AdminUserController::class, 'activar'])->name('usuarios.activar');

        Route::get('/productos', [AdminPostController::class, 'index'])->name('productos.index');
        Route::put('/productos/{post}/despublicar', [AdminPostController::class, 'despublicar'])->name('productos.despublicar');
        Route::delete('/productos/{post}', [AdminPostController::class, 'destroy'])->name('productos.destroy');

        Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');

        Route::get('/resenas', [AdminResenaController::class, 'index'])->name('resenas.index');
        Route::delete('/resenas/{resena}', [AdminResenaController::class, 'destroy'])->name('resenas.destroy');
    });
});