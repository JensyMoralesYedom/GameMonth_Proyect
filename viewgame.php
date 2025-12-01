<!doctype html>
<html lang="en">
    <head>
        <title>Game</title>
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
  <style>
        body {
            background-color: #192443ff;
            color : #ffffff;
        }

        .game-title {
            font-weight: bold;
            font-size: 22px;
            margin-top: 15px;
        }
        .stars {
            color: gold;
            font-size: 20px;
        }
        .buy-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        }
        .buy-btn:hover {
            background: #46a04e;
            color: white;
        }
        .gray-img {
            background: #e5e5e5;
            width: 100%;
            height: 280px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: gray;
            font-size: 50px;
            border-radius: 8px;
        }
        .desc{
            color : #fe952dda;
            font-weight: bold;
            font-family: Arial, sans-serif;

        }
        .datos{
            font-family: Arial, sans-serif;
            FONT-SIZE: 15PX;
        }
        .precios{
            font-size: 15px;
            margin-top:160px;
            font-family: Arial, sans-serif;

        }
        .adquirir{
            font-size: 15px;
            margin-top:160px;
            font-family: Arial, sans-serif;

        }

    </style>
</head>
<?php
require_once "../modelos/conexion.php";

// Validar ID recibido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID no válido.");
}

$id = intval($_GET['id']);

try {
    $db = Conexion::conectar();

    $sql = "SELECT * FROM videojuegos WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        die("Juego no encontrado.");
    }

    $juego = $stmt->fetch();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<body class="p-4">

    <!-- Logo -->
    <div class="mb-4">
        <a href="home.php"><img src="img/logo.png" width="130"></a>
    </div>
<div class="container m-5">
    <div class="row">

        <!-- VIDEO + TÍTULO -->
        <div class="col-md-6">

            <?php
            // Convertir URL normal de YouTube a formato embed
            $trailer = str_replace("https://www.youtube.com/watch?v=", "", $juego['trailer']);
            ?>

            <iframe width="100%" height="350"
                    src="https://www.youtube.com/embed/<?= $trailer ?>"
                    frameborder="0" allowfullscreen>
            </iframe>

            <div class="d-flex justify-content-between align-items-center m-4">
                <h1 class="game-title"><?= $juego['titulo'] ?></h1>

                <!-- Estrellas dinámicas -->
                <label>
                    <?php
                        echo str_repeat("★ ", $juego['calificacion']);
                        echo str_repeat("☆ ", 5 - $juego['calificacion']);
                    ?>
                </label>
            </div>

        </div>

        <!-- INFORMACIÓN -->
        <div class="col-md-5 datos mb-5">

            <p><?= $juego['descripcion'] ?></p>

            <p><strong>Plataforma:</strong>
                <label class="desc"><?= $juego['plataforma'] ?></label>
            </p>

            <p><strong>Género:</strong>
                <label class="desc"><?= $juego['genero'] ?></label>
            </p>

            <p><strong>Estado:</strong>
                <label class="desc"><?= $juego['estado'] ?></label>
            </p>

            <p><strong>Fecha agregado:</strong>
                <label class="desc"><?= $juego['fecha_agregado'] ?></label>
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <p class="precios">
                    <strong>Precio:
                        <span class="text-success">
                            <label class="">$<?= $juego['precio'] ?></label>
                        </span>
                    </strong>
                </p>

                <a href="agregar_biblioteca.php?id=<?= $juego['id'] ?>" 
                class="buy-btn mt-5 adquirir d-inline-block">
                ADQUIRIR AHORA
                </a>

            </div>

        </div>
</div>
</div>

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

