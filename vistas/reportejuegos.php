<?php
include_once "C:\laragon\www\GameMonth_Proyect\modelos\conexion.php";
$db = Conexion::conectar();

// --- CAPTURA DE FILTROS ---
$titulo = $_GET['titulo'] ?? '';
$calificacion = $_GET['calificacion'] ?? '';
$genero = $_GET['genero'] ?? '';
$estado = $_GET['estado'] ?? '';
$fecha = $_GET['fecha'] ?? '';

// --- CONSULTA BASE ---
$query = "SELECT * FROM videojuegos WHERE 1=1";

// --- APLICAR FILTROS ---
if ($titulo !== '') {
    $query .= " AND titulo LIKE :titulo";
    $params[':titulo'] = "%$titulo%";
}
if ($calificacion !== '') {
    $query .= " AND calificacion = :calificacion";
}
if ($genero !== '') {
    $query .= " AND genero = :genero";
}
if ($estado !== '') {
    $query .= " AND estado = :estado";
}
if ($fecha !== '') {
    $query .= " AND DATE(fecha_agregado) = :fecha";
}

$stmt = $db->prepare($query);

// --- BIND DE VALORES ---
if ($titulo !== '') {
    $stmt->bindValue(':titulo', "%$titulo%");
}
if ($calificacion !== '') {
    $stmt->bindValue(':calificacion', $calificacion);
}
if ($genero !== '') {
    $stmt->bindValue(':genero', $genero);
}
if ($estado !== '') {
    $stmt->bindValue(':estado', $estado);
}
if ($fecha !== '') {
    $stmt->bindValue(':fecha', $fecha);
}

$stmt->execute();
$juegos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Juegos</title>

    <style>
        body { 
            font-family: Arial; 
            text-align: center; 
            background: #192443ff; 
            color: white;
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

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            color: black;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background: #e7a923ff;
            color: black;
        }

        /* PERFIL */
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

        /* Formularios */
        .filtros {
            margin: 20px auto;
            width: 90%;
            background: #203060;
            padding: 20px;
            border-radius: 10px;
        }

        .filtros input, .filtros select {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: none;
        }

        .btn {
            background: #e7a923ff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background: #fe952dda;
        }
    </style>
</head>

<body>

<!-- PERFIL DEL ADMIN -->
<div class="perfil">
    <img src="../img/fotoadmin.png" class="perfil-img">
    <span class="perfil-nombre">Admin</span>
</div>

<h2>Reporte de Juegos</h2>

<nav>
    <a href="adminpage.php">Pantalla Administrador</a>
    <a href="reporteclientes.php">Reporte Clientes</a>
    <a href="reportejuegos.php">Reporte Juegos</a>
    <a href="registrarclienteadmin.php">Administrar Cliente</a>
    <a href="registrarjuegoadmin.php">Administrar Juegos</a>
</nav>

<!-- FILTROS -->
<form method="GET" class="filtros">

    <input type="text" name="titulo" placeholder="Buscar por nombre" value="<?= $titulo ?>">

    <?php 
$calificacion = $_GET['calificacion'] ?? ''; 
?>

<select name="calificacion">
    <option value="">Calificación</option>

    <?php for ($i = 1; $i <= 5; $i++): ?>
        <option value="<?= $i ?>" <?= ((string)$calificacion === (string)$i ? 'selected' : '') ?>>
            <?= $i ?> estrellas
        </option>
    <?php endfor; ?>

</select>


    <select name="genero">
        <option value="">Género</option>
<option value="Supervivencia / Creatividad" <?= $genero == 'Supervivencia / Creatividad' ? 'selected' : '' ?>>Supervivencia / Creatividad</option>
<option value="Acción / Mundo Abierto" <?= $genero == 'Acción / Mundo Abierto' ? 'selected' : '' ?>>Acción / Mundo Abierto</option>
<option value="Puzzle" <?= $genero == 'Puzzle' ? 'selected' : '' ?>>Puzzle</option>
<option value="MOBA" <?= $genero == 'MOBA' ? 'selected' : '' ?>>MOBA</option>
<option value="Acción / Aventura" <?= $genero == 'Acción / Aventura' ? 'selected' : '' ?>>Acción / Aventura</option>
<option value="RPG / Acción" <?= $genero == 'RPG / Acción' ? 'selected' : '' ?>>RPG / Acción</option>
<option value="Aventura / Mundo Abierto" <?= $genero == 'Aventura / Mundo Abierto' ? 'selected' : '' ?>>Aventura / Mundo Abierto</option>
<option value="Plataformas" <?= $genero == 'Plataformas' ? 'selected' : '' ?>>Plataformas</option>
<option value="Battle Royale" <?= $genero == 'Battle Royale' ? 'selected' : '' ?>>Battle Royale</option>
<option value="Shooter / Héroes" <?= $genero == 'Shooter / Héroes' ? 'selected' : '' ?>>Shooter / Héroes</option>
<option value="Shooter / Sci-Fi" <?= $genero == 'Shooter / Sci-Fi' ? 'selected' : '' ?>>Shooter / Sci-Fi</option>
<option value="Shooter / Acción" <?= $genero == 'Shooter / Acción' ? 'selected' : '' ?>>Shooter / Acción</option>
<option value="RPG / Sci-Fi" <?= $genero == 'RPG / Sci-Fi' ? 'selected' : '' ?>>RPG / Sci-Fi</option>
<option value="Party / Social" <?= $genero == 'Party / Social' ? 'selected' : '' ?>>Party / Social</option>
<option value="Simulación / RPG" <?= $genero == 'Simulación / RPG' ? 'selected' : '' ?>>Simulación / RPG</option>
<option value="Roguelike / Acción" <?= $genero == 'Roguelike / Acción' ? 'selected' : '' ?>>Roguelike / Acción</option>
<option value="RPG" <?= $genero == 'RPG' ? 'selected' : '' ?>>RPG</option>
<option value="Sigilo / Acción" <?= $genero == 'Sigilo / Acción' ? 'selected' : '' ?>>Sigilo / Acción</option>
<option value="Survival Horror" <?= $genero == 'Survival Horror' ? 'selected' : '' ?>>Survival Horror</option>
<option value="Carreras" <?= $genero == 'Carreras' ? 'selected' : '' ?>>Carreras</option>

    </select>

    <select name="estado">
        <option value="">Estado</option>
        <option value="Activo" <?= $estado == 'disponible' ? 'selected' : '' ?>>Disponible</option>
        <option value="Inactivo" <?= $estado == 'deshabilitado' ? 'selected' : '' ?>>Deshabilitado</option>
    </select>

    <input type="date" name="fecha" value="<?= $fecha ?>">

    <button type="submit" class="btn">Filtrar</button>
</form>

<!-- TABLA DE RESULTADOS -->
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Género</th>
        <th>Calificación</th>
        <th>Estado</th>
        <th>Fecha</th>
    </tr>

    <?php if (count($juegos) > 0): ?>
        <?php foreach ($juegos as $j): ?>
            <tr>
                <td><?= $j['id'] ?></td>
                <td><?= $j['titulo'] ?></td>
                <td><?= $j['genero'] ?></td>
                <td><?= $j['calificacion'] ?> ⭐</td>
                <td><?= $j['estado'] ?></td>
                <td><?= $j['fecha_agregado'] ?></td>
            </tr>
        <?php endforeach; ?>

    <?php else: ?>
        <tr>
            <td colspan="6">No se encontraron resultados.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
