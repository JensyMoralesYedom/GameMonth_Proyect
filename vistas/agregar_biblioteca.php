<?php
require_once "../modelos/conexion.php";

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID del juego inválido.");
}

$idJuego = intval($_GET['id']);

// TEMPORAL si aún no tienes login real
$idUsuario = 1;

try {
    $db = Conexion::conectar();

    // Verificar si ya está en la biblioteca
    $check = $db->prepare("
        SELECT * FROM biblioteca_usuarios
        WHERE id_usuario = :usuario AND id_videojuego = :juego
    ");
    $check->bindParam(":usuario", $idUsuario, PDO::PARAM_INT);
    $check->bindParam(":juego", $idJuego, PDO::PARAM_INT);
    $check->execute();

    if ($check->rowCount() > 0) {
        // Ya existe → mensaje bonito + redirección
        echo "<script>alert('Este juego ya está en tu biblioteca'); window.history.back();</script>";
        exit;
    }

    // Insertar en biblioteca_usuarios usando tu columna fecha_activacion
    $insert = $db->prepare("
        INSERT INTO biblioteca_usuarios (id_usuario, id_videojuego, fecha_activacion)
        VALUES (:usuario, :juego, NOW())
    ");
    $insert->bindParam(":usuario", $idUsuario, PDO::PARAM_INT);
    $insert->bindParam(":juego", $idJuego, PDO::PARAM_INT);

    if ($insert->execute()) {
        echo "<script>alert('Juego agregado a tu biblioteca'); window.history.back();</script>";
    } else {
        echo "Error al agregar.";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
