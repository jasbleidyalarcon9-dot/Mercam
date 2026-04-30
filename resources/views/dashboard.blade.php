<x-app-layout>
    <div class="container mt-5">

        <div class="card shadow p-4 text-center">
            <h2 class="mb-3">Dashboard</h2>

            <p class="mb-3">
                Bienvenido, <strong>{{ Auth::user()->name }}</strong>
            </p>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('posts.index') }}" class="btn btn-success">
                    Publicaciones
                </a>

                <a href="{{ route('productos.index') }}" class="btn btn-outline-success">
                    Productos
                </a>
            </div>

        </div>

    </div>
</x-app-layout>