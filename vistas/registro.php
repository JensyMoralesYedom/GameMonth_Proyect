<?php
// session_start();
// if (isset($_SESSION['verificado'])) {
//     header("Location: bienvenida.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style_sing.css">
</head>
<body>

<div class="sidenav">
    <div class="login-main-text">
        <h3>Bienvenido</h3>
        <p>Formulario de registro.</p>
    </div>
</div>

<div class="main">
    <div class="col-md-6 col-sm-12">
        <div class="login-form">

            <form action="../controladores/registro_controlador.php" method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label>telefono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" id="pass" name="pass" required>
                </div>

                <button type="submit" class="btn btn-success mt-3">Registrarse</button>
            </form>

        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
