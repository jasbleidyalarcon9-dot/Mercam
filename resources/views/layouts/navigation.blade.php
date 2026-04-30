<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">

    <div class="container-fluid">

        <!-- IZQUIERDA -->
        <span class="navbar-brand fw-bold">
            Mi cuenta
        </span>

        <!-- DERECHA -->
        <div class="ms-auto d-flex align-items-center">

            <!-- INFO USUARIO -->
            <div class="text-end me-3">
                <div><strong>{{ Auth::user()->name }}</strong></div>
                <div style="font-size: 12px;" class="text-muted">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <!-- DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end text-center p-2">

                    <li>
                        <a href="{{ route('profile.edit') }}" class="btn btn-success w-100 mb-2">
                            Profile
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-danger w-100">
                                Log Out
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>

    </div>

</nav>