<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AdminPage</title>

    <style>
        body { 
            font-family: Arial; 
            text-align: center; 
            background: #192443ff; 
        }
        nav { 
            background-color: #e7a923ff; 
            padding: 10px; 
        }
        a {
            color: white; 
            text-decoration: none; 
            margin: 10px;
            font-size: 18px; 
            padding: 8px 15px; 
            border-radius: 5px;
        }
        a:hover { background-color: #fe952dda; }
        h2 { color: #bb1818ff; }

        /* ---- PERFIL ARRIBA A LA DERECHA ---- */
        .perfil {
            position: absolute;
            top: 15px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .perfil-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        .perfil-nombre {
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
<!-- PERFIL DEL ADMIN -->
<div class="perfil">
    <img src="../img/fotoadmin.png" class="perfil-img">
    <span class="perfil-nombre">Nombre</span>
</div>

<h2>Página del Administrador</h2>

<nav>
    <a href="adminpage.php">Pantalla Administrador</a>
    <a href="reporteclientes.php">Reporte Clientes</a>
    <a href="reportejuegos.php">Reporte Juegos</a>
    <a href="registrarclienteadmin.php">Administrar Cliente</a>
    <a href="registrarjuegoadmin.php">Administrar Juegos</a>
</nav>

<img src="../img/colmadogamer.jpg" alt="Imagen Logo" width="570px">

</body>
</html>
