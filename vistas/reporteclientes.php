<?php
include_once "C:\laragon\www\GameMonth_Proyect\modelos\conexion.php";

$db = Conexion::conectar();

// --- Filtros ---
$nombre      = $_GET['nombre'] ?? '';
$apellido    = $_GET['apellido'] ?? '';
$f_inicio    = $_GET['f_inicio'] ?? '';
$f_fin       = $_GET['f_fin'] ?? '';
$estado      = $_GET['estado'] ?? '';
$monto_min   = $_GET['monto_min'] ?? '';
$monto_max   = $_GET['monto_max'] ?? '';
$rol         = $_GET['rol'] ?? '';

// --- Consulta base ---
$query = "
SELECT 
    u.nombre AS nombre_usuario,
    u.apellido AS apellido_usuario,
    u.telefono,
    u.usuario,
    s.estado,
    s.fecha_inicio,
    s.fecha_fin,
    s.monto
FROM usuarios u
LEFT JOIN suscripciones s ON u.id = s.id_usuario
WHERE 1=1
";

$params = [];

// Filtros dinámicos
if ($nombre !== '') {
    $query .= " AND u.nombre LIKE :nombre";
    $params[':nombre'] = "%$nombre%";
}
if ($apellido !== '') {
    $query .= " AND u.apellido LIKE :apellido";
    $params[':apellido'] = "%$apellido%";
}
if ($rol !== '') {
    $query .= " AND u.rol = :rol";
    $params[':rol'] = $rol;
}
if ($estado !== '') {
    $query .= " AND s.estado = :estado";
    $params[':estado'] = $estado;
}
if ($f_inicio !== '') {
    $query .= " AND DATE(s.fecha_inicio) >= :f_inicio";
    $params[':f_inicio'] = $f_inicio;
}
if ($f_fin !== '') {
    $query .= " AND DATE(s.fecha_fin) <= :f_fin";
    $params[':f_fin'] = $f_fin;
}
if ($monto_min !== '') {
    $query .= " AND s.monto >= :monto_min";
    $params[':monto_min'] = $monto_min;
}
if ($monto_max !== '') {
    $query .= " AND s.monto <= :monto_max";
    $params[':monto_max'] = $monto_max;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$clientes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Clientes</title>

    <style>
        body { 
            font-family: Arial; 
            text-align: center; 
            background: #192443ff; 
            color: white;
        }
        nav { background-color: #e7a923ff; padding: 10px; }
        a { 
            color: white; 
            text-decoration: none; 
            margin: 10px; 
            padding: 8px 15px; 
            border-radius: 5px; 
        }
        a:hover { background-color: #fe952dda; }
        h2 { color: #bb1818ff; }

        /* PERFIL */
        .perfil {
            position: absolute;
            top: 15px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .perfil-img { width: 45px; height: 45px; object-fit: cover; }
        .perfil-nombre { color: white; font-weight: bold; font-size: 16px; }

        /* TABLA */
        table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; background: #24345b; border: 1px solid #6b6b6b; }
        th { background: #e7a923ff; color: black; }

        /* FORM */
        input, select { padding: 8px; margin: 5px; border-radius: 5px; border: none; }
        button { padding: 8px 15px; background: #e7a923ff; border: none; cursor: pointer; }
        button:hover { background: #fe952dda; }
    </style>
</head>

<body>

<!-- PERFIL -->
<div class="perfil">
    <img src="../img/fotoadmin.png" class="perfil-img">
    <span class="perfil-nombre">Nombre</span>
</div>

<h2>Reporte de Clientes</h2>

<!-- MENÚ -->
<nav>
    <a href="adminpage.php">Pantalla Administrador</a>
    <a href="reporteclientes.php">Reporte Clientes</a>
    <a href="reportejuegos.php">Reporte Juegos</a>
    <a href="registrarclienteadmin.php">Administrar Cliente</a>
    <a href="registrarjuegoadmin.php">Administrar Juegos</a>
</nav>

<!-- FILTROS -->
<form method="GET">
    <input type="text" name="nombre" placeholder="Nombre" value="<?= $nombre ?>">
    <input type="text" name="apellido" placeholder="Apellido" value="<?= $apellido ?>">

    <input type="date" name="f_inicio" value="<?= $f_inicio ?>">
    <input type="date" name="f_fin" value="<?= $f_fin ?>">

    <select name="estado">
        <option value="">Estado</option>
        <option value="activa"   <?= $estado=="activa"?"selected":"" ?>>Activa</option>
        <option value="expirada" <?= $estado=="expirada"?"selected":"" ?>>Expirada</option>
    </select>

    <select name="rol">
        <option value="">Rol</option>
        <option value="admin"   <?= $rol=="admin"?"selected":"" ?>>Administrador</option>
        <option value="cliente" <?= $rol=="cliente"?"selected":"" ?>>Cliente</option>
    </select>

    <input type="number" name="monto_min" placeholder="Monto mínimo" value="<?= $monto_min ?>">
    <input type="number" name="monto_max" placeholder="Monto máximo" value="<?= $monto_max ?>">

    <button type="submit">Filtrar</button>
</form>

<!-- TABLA -->
<table>
<tr>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Teléfono</th>
    <th>Usuario</th>
    <th>Estado</th>
    <th>Fecha Inicio</th>
    <th>Fecha Fin</th>
    <th>Monto</th>
</tr>

<?php if (!empty($clientes)): ?>
    <?php foreach ($clientes as $c): ?>
    <tr>
        <td><?= $c["nombre_usuario"] ?></td>
        <td><?= $c["apellido_usuario"] ?></td>
        <td><?= $c["telefono"] ?: "N/A" ?></td>
        <td><?= $c["usuario"] ?></td>
        <td><?= $c["estado"] ?: "Sin suscripción" ?></td>
        <td><?= $c["fecha_inicio"] ?: "-" ?></td>
        <td><?= $c["fecha_fin"] ?: "-" ?></td>
        <td><?= $c["monto"] ?: "0.00" ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>

</table>

</body>
</html>
