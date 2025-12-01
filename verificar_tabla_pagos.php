<?php
require_once 'modelos/conexion.php';

try {
    $db = Conexion::conectar();
    
    echo "<h1>Verificación Tabla PAGOS</h1>";
    
    // 1. Estructura
    echo "<h2>1. Estructura de la tabla</h2>";
    $stmt = $db->query("DESCRIBE pagos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . ($col['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 2. Restricciones de clave foránea
    echo "<h2>2. Restricciones de Clave Foránea</h2>";
    $stmt = $db->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                        WHERE TABLE_NAME = 'pagos'");
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($fks);
    echo "</pre>";
    
    // 3. Usuarios existentes
    echo "<h2>3. Usuarios en BD</h2>";
    $stmt = $db->query("SELECT id, usuario, email FROM usuarios ORDER BY id DESC LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
    // 4. Suscripciones existentes
    echo "<h2>4. Suscripciones en BD</h2>";
    $stmt = $db->query("SELECT id_suscripcion, id_usuario, estado FROM suscripciones ORDER BY id_suscripcion DESC LIMIT 5");
    $suscs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($suscs);
    echo "</pre>";
    
    // 5. Intentar INSERT de prueba
    echo "<h2>5. Prueba de INSERT</h2>";
    if (isset($_GET['test'])) {
        try {
            // Usar usuario y suscripción existentes
            $stmt = $db->query("SELECT id FROM usuarios ORDER BY id DESC LIMIT 1");
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userID = $user['id'] ?? null;
            
            $stmt = $db->query("SELECT id_suscripcion FROM suscripciones ORDER BY id_suscripcion DESC LIMIT 1");
            $susc = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_susc = $susc['id_suscripcion'] ?? null;
            
            if (!$userID || !$id_susc) {
                echo "❌ No hay usuario o suscripción disponibles<br>";
            } else {
                echo "Intentando INSERT con userID=$userID, id_suscripcion=$id_susc<br>";
                
                $stmt = $db->prepare("
                    INSERT INTO pagos (id_usuario, id_suscripcion, monto, fecha, transaction_id)
                    VALUES (?, ?, 10.00, NOW(), 'TEST_" . time() . "')
                ");
                $stmt->bindParam(1, $userID, PDO::PARAM_INT);
                $stmt->bindParam(2, $id_susc, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    echo "✅ INSERT exitoso!<br>";
                } else {
                    $err = $stmt->errorInfo();
                    echo "❌ Error: " . $err[0] . " - " . $err[1] . " - " . $err[2] . "<br>";
                }
            }
        } catch (Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "<a href='?test=1' style='background:green;color:white;padding:10px;text-decoration:none'>Ejecutar Test</a>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error</h2>";
    echo $e->getMessage();
}
?>
