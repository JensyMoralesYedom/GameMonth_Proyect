<?php
session_start();

// Si no está logueado, redirige
if(!isset($_SESSION['verificado'])){
    header("Location: login.php");
    exit;
}

include_once "../controladores/biblioteca_controlador.php";
$videojuegos = BibliotecaControlador::mostrarLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Biblioteca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #121212;
            color: white;
        }

        .game-card {
            background-color: #1f1f1f;
            border-radius: 10px;
            overflow: hidden;
            transition: transform .2s;
        }

        .game-card:hover {
            transform: scale(1.05);
        }

        .game-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .game-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-top: 10px;
            color: #fff;
        }

        .game-genre {
            color: #aaa;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div>
    <header class="sticky-top bg-primary">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Mi Web</a>
    
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="biblioteca.php">Mi Biblioteca</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pag</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pag</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</div>

<!-- CONTENIDO -->
<div class="container py-4">
    <h2 class="mb-4">🎮 Mi Biblioteca de Juegos</h2>

    <div class="row g-4">

        <?php if(!empty($videojuegos)): ?>
            <?php foreach($videojuegos as $juego): ?>
            
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="game-card p-2">
                        <img src="../img/juegos/<?php echo $juego['portada']; ?>" class="game-img" alt="Portada">

                        <div class="p-2">
                            <div class="game-title"><?php echo $juego['titulo']; ?></div>
                            <div class="game-genre"><?php echo $juego['genero']; ?></div>

                            <a href="juego.php?id=<?php echo $juego['id']; ?>" 
                               class="btn btn-sm btn-primary mt-2 w-100">
                                Ver juego
                            </a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="text-center mt-5">
                <h4>No tienes juegos en tu biblioteca aún.</h4>
            </div>

        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>