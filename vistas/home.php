<!doctype html>
<html lang="en">
    <head>
        <?php
            include '../controladores/home_controlador.php';
            session_start();

            if (!isset($_SESSION['verificado'])) {
                echo '<script>
                        alert("Debes iniciar sesión para acceder a esta página.");
                        window.location = "vistas/login.php";
                      </script>';
                exit();
            }
        ?>
        <link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
        <title>Colmado Gamer</title>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <?php
        include 'header.php';
        ?>
        <!-- <header>
            Nav tabs
            <ul
                class="nav nav-tabs"
                id="navId"
                role="tablist"
            >
                <li class="nav-item">
                    <a
                        href="#tab1Id"
                        class="nav-link active"
                        data-bs-toggle="tab"
                        aria-current="page"
                        >Active</a
                    >
                </li>
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >Dropdown</a
                    >
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#tab2Id">Action</a>
                        <a class="dropdown-item" href="#tab3Id">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#tab4Id">Action</a>
                    </div>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tab5Id" class="nav-link" data-bs-toggle="tab"
                        >Another link</a
                    >
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#" class="nav-link disabled" data-bs-toggle="tab"
                        >Disabled</a
                    >
                </li>
            </ul>

            Cuadros
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab1Id" role="tabpanel">
                    
                </div>
                <div class="tab-pane fade" id="tab2Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab3Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab4Id" role="tabpanel"></div>
                <div class="tab-pane fade" id="tab5Id" role="tabpanel"></div>
            </div>
            
            (Optional) - Place this js code after initializing bootstrap.min.js or bootstrap.bundle.min.js
            <script>
                var triggerEl = document.querySelector("#navId a");
                bootstrap.Tab.getInstance(triggerEl).show(); // Select tab by name
            </script>
        </header> -->


        <main>            
            <div class="container py-4">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                    <?php
                        $control = new HomeControlador();
                        $juegos = $control->mostrarDatos();
                        foreach ($juegos as $juego) {
                            echo '<div class="col m-2">
                                    <div class="h-100 m-3 ">
                                        <img src="' . $juego['portada'] . '" class="card-img-top custom-img" alt="Producto">
                                        <div class="card-body m-0 p-0">
                                        <div class="elemento-centro"><h5 class="card-title d-inline nombre-juego m-1 p-0">' . htmlspecialchars($juego['titulo']) . '</h5><p class=" d-inline m-0 ">' . str_repeat('★', intval($juego['calificacion'])) . str_repeat('☆', 5 - intval($juego['calificacion'])) . '</p></div>
                                        <div class="elemento-centro"><p class="d-inline m-2 ">$' . "HOLA" . '</p><img class="icono-naranja d-inline ms-auto m-0 mt-2 " width="30" height="30" src="https://img.icons8.com/sf-regular-filled/48/add-shopping-cart.png" alt="add-shopping-cart" style="fill: red;" /> </div>
                                        </div>
                                    </div>
                                </div>';
                        }   
                    ?>
                    

                    <!-- Repita este bloque .col para agregar más productos lado a lado -->
                </div>
            
            </div>

        </main>


        <footer>
        </footer>

        <style>
        .elemento-centro{
            justify-content: right;
            display: flex;
        }
        .nombre-juego {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        .custom-img {
            width: 200px;
            height: 115px;
            object-fit: cover; /* mantiene la imagen recortada sin deformarse */
        }
        .icono-naranja {
        filter: invert(53%) sepia(95%) saturate(3103%) hue-rotate(1deg) brightness(100%) contrast(105%);
        }
        </style>


        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
