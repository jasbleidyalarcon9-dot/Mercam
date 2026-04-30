<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Seguridad básica -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    
    <title>MERCAM | Portal de Frutas y Verduras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700&display=swap" rel="stylesheet">
   
</head>

<body>

    <!-- BARRA SUPERIOR -->
    <div class="top-bar">
         Productos frescos del campo colombiano
    </div>

    <!-- HEADER -->
    <header>
         
        <header class="main-header">

    <div class="container-fluid">

        <!-- LOGO -->
        <div class="logo-section">
            <img src="{{ asset('img/image.png') }}" alt="Logo MERCAM">
        </div>

        <!-- BUSCADOR -->
        <div class="search-section">
            <input type="text" placeholder="Buscar productos...">
        </div>

        <!-- ICONOS -->
        <div class="icons-section">
            <a href="#">🛒</a>
            <a href="#">💬</a>
            <a href="{{ route('login') }}">👤 Mi cuenta</a>
        </div>

    </div>

    <!-- BOTONES (LOS MISMOS TUYOS) -->
    <nav class="menu-buttons">
        <a href="#">Inicio</a>
        <a href="{{ route('register') }}">Registrar</a>
        <a href="#CAT">Catálogo</a>
        <a href="#PROD">Productos destacados</a>
        <a href="#CONT">Contacto</a>
    </nav>

</header>
    </header>


</section>

    <!-- CARRUSEL -->
    <section class="container-fluid px-0 my-5">
        <div id="carouselMercam" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselMercam" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselMercam" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselMercam" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner shadow">

                <div class="carousel-item active">
                    <img src="{{ asset('img/mazorca cultivo.jpg') }}" class="d-block w-100" height="400" alt="Mazorca cultivo">
                    <div class="carousel-caption">
                        <h5>Productos Frescos</h5>
                        <p>Seleccionados directamente del campo colombiano.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('img/verduras cosechas.jpg') }}" class="d-block w-100" height="400" alt="Verduras cosechas">
                    <div class="carousel-caption">
                        <h5>Calidad Garantizada</h5>
                        <p>Control de frescura y estándares agrícolas.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('img/zanahoria cultivo.jpeg') }}" class="d-block w-100" height="400" alt="Zanahoria cultivo">
                    <div class="carousel-caption">
                        <h5>Precios Actualizados</h5>
                        <p>Información confiable del mercado.</p>
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMercam" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carouselMercam" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </section>

     <!-- BANNER -->
<section class="py-5 bg-white">

    <div class="container">

        <div class="banner rounded-4 shadow">

            <div class="row align-items-center p-5">

                <!-- TEXTO -->
                <div class="col-md-7 text-start">

                    <h1 class="display-5 fw-bold mb-4">
                        Disfruta la frescura auténtica de productos directamente del campo de la Villa de San Diego de Ubaté.
                    </h1>

                    <p class="fs-5">
                        Encuentra frutas, verduras y alimentos seleccionados con calidad garantizada, cultivados por agricultores de nuestra región para llevar sabor, tradición y frescura hasta tu mesa.
                    </p>

                </div>

                <!-- IMAGEN -->
                <div class="col-md-5 text-center">

                    <img src="{{ asset('img/descarga (5).png') }}"
                         alt="Productos del campo"
                         class="img-fluid rounded shadow"
                         style="max-height: 350px;">

                </div>

            </div>

        </div>

    </div>

    <!-- ACCESOS RÁPIDOS -->
    <section class="accesos text-center">
        <div class="container">
            <div class="row" id="CAT">
         <h2 class="text-center mb-4 fw-bold"
    style="color: #3f9351;">
    Cátalogo
</h2>


                <div class="col-md-3" >
                    <h5>🍎 Frutas</h5>
                    <p>Información nutricional y precios actualizados.</p>
                </div>

                <div class="col-md-3">
                    <h5>🥕 Verduras</h5>
                    <p>Productos frescos y de temporada.</p>
                </div>

                <div class="col-md-3">
                    <h5>🌱 Agricultura sostenible</h5>
                    <p>Compromiso con el medio ambiente.</p>
                </div>

                <div class="col-md-3">
                    <h5>📊 Precios del mercado</h5>
                    <p>Valores promedio por kilogramo.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- SECCIÓN TIPO NOTICIAS -->
    <section class="container my-5">
        <div class="row"id="PROD">
        <h2 class="text-center mb-4 fw-bold"
    style="color: #3f9351;">
    Productos destacados</h2>

            <div class="col-md-4 mb-4">
    <div class="card card-custom p-3">
        <img src="{{ asset('img/papa.png') }}" class="d-block w-50" alt="Papa">
        <h4>Papa</h4>
        <p><strong>Precio:</strong> $40.000</p>
        <p><strong>Stock:</strong> 50</p>
        <p>Papas frescas cultivadas sin pesticidas.</p>
    </div>
</div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom p-3">
                    <img src="{{ asset('img/lechuga.png') }}" class="d-block w-50" alt="desi">
                    <h4> Lechuga</h4>
                    <p><strong>Precio:</strong> $1.500/unidad</p>
                    <p>Verdura fresca rica en fibra y vitaminas A y C. Perfecta para ensaladas.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom p-3">
                    <img src="{{ asset('img/tomat.png') }}" class="d-block w-50" alt="desi">
                    <h4> Tomate</h4>
                    <p><strong>Precio:</strong> $2.800/kg</p>
                    <p>Rico en antioxidantes como el licopeno. Ideal para salsas y ensaladas.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom p-3">
                    <img src="{{ asset('img/banano.png') }}" class="d-block w-50" alt="desi">
                    <h4> Banano</h4>
                    <p><strong>Precio:</strong> $1.800/kg</p>
                    <p>Alto en potasio y energía natural. Excelente para deportistas.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom p-3">
                    <img src="{{ asset('img/manzana.png') }}" class="d-block w-50" alt="desi">
                    <h4> Manzana</h4>
                    <p><strong>Precio:</strong> $3.000/kg</p>
                    <p>Rica en fibra y antioxidantes. Contribuye a la salud digestiva.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom p-3">
                    <img src="{{ asset('img/zanahoria.jpeg') }}" class="d-block w-65" alt="desi">
                    <h4> Zanahoria</h4>
                    <p><strong>Precio:</strong> $2.200/kg</p>
                    <p>Alta en vitamina A, beneficiosa para la visión y el sistema inmune.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- CERTIFICACIONES / ALIADOS -->
    <section class="footer-top text-center">
        <div class="container">
            <h5>Comprometidos con calidad y bioseguridad</h5>
            <p>Productos seleccionados bajo estándares de frescura y control agrícola.</p>
        </div>
    </section>

    <!-- FOOTER GRANDE -->
    <footer>
        <div class="container">
            <div class="row" id="CONT">

                <div class="col-md-6">
                    <h4>Información Legal</h4>
                    <a href="#">Política de calidad</a>
                    <a href="#">Protección de datos</a>
                    <a href="#">Transparencia</a>
                    <a href="#">Normatividad agrícola</a>
                </div>

                <div class="col-md-6">
                    <h4>Contacto</h4>
                    <p>Línea gratuita: 01 8000 000 000</p>
                    <p>Email: jasbleidyalarcon@mercam.com</p>
                    <p>Sede principal: Colombia</p>
                </div>

            </div>

            <hr>

            <p class="text-center">© 2026 MERCAM - Portal informativo agrícola</p>
        </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
