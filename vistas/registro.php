<?php
    include "../database/conexiondb.php";

    if(isset($_SESSION['verificado'])){
        header("Location: bienvenida.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <link rel="stylesheet" href="../css/style_sing.css">
   </head>
   <body>

    <!-- Include the above in your HEAD tag -->

    <div class="sidenav">
        <div class="login-main-text">
            <h2><h3>Bienvenido</h3><br> Registrarse </h2>
            <p>formulario de registro.</p>
        </div>
    </div>

    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
                <form action="" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" placeholder="Juan" id="nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" placeholder="Perez" id="apellido" name="apellido">
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" placeholder="It'ztuRealMenolEnCotiseHD" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="elrealpradaboy@mail.com" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    </div>
                        <button type="submit" class="btn btn-success mt-3">Register</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        $ins = $conn -> query("INSERT INTO usuarios (nombre, apellido, usuario, email, password_hash) VALUES ('$_POST[nombre]', '$_POST[apellido]', '$_POST[username]', '$_POST[email]', '$_POST[password]')");

            IF ($ins){
                HEADER("Location: bienvenida.php");

                session_start();
                $_SESSION["verificado"] = "si";
            } else {
                echo "Error al guardar";
            }
    }
    
    ?>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </body>
</html>