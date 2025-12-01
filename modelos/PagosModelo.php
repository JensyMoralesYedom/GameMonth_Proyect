<?php
require_once __DIR__ . '/conexion.php';

class PagosModelo {

    /**
     * Registra un pago en la base de datos
     * Retorna true si se guardó correctamente
     */
    public static function registrarPago($userID, $id_suscripcion, $monto, $transactionID) {
        try {
            $db = Conexion::conectar();

            // Convertir monto a formato DECIMAL correcto
            $monto = floatval($monto);

            // Ajuste de columnas para coincidir con la tabla 'pagos'
            // La tabla tiene columnas: id_usuario, id_suscripcion, monto, fecha, transaction_id
            $stmt = $db->prepare("
                INSERT INTO pagos (id_usuario, id_suscripcion, monto, fecha, transaction_id)
                VALUES (?, ?, ?, NOW(), ?)
            ");

            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_suscripcion, PDO::PARAM_INT);
            $stmt->bindParam(3, $monto, PDO::PARAM_STR);  // DECIMAL como string
            $stmt->bindParam(4, $transactionID, PDO::PARAM_STR);

            $ok = $stmt->execute();
            if (!$ok) {
                $err = $stmt->errorInfo();
                $errorMsg = "SQLSTATE[" . $err[0] . "]: " . $err[1] . " - " . $err[2];
                error_log("Error INSERT pagos - userID:$userID, suscripcion:$id_suscripcion, monto:$monto, transID:$transactionID");
                error_log("Error detalles: " . $errorMsg);
                return false;
            }
            return true;

        } catch (PDOException $e) {
            error_log("Error Modelo Pagos (PDO): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener historial de pagos del usuario
     */
    public static function obtenerPagosPorUsuario($userID) {
        try {
            $db = Conexion::conectar();

            $stmt = $db->prepare("
                SELECT * FROM pagos 
                WHERE id_usuario = ?
                ORDER BY fecha DESC
            ");

            $stmt->execute([$userID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerPagosPorUsuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Auxiliar para debug: obtener últimos errores de PDO
     */
    public static function debug_pdo($stmt) {
        $info = $stmt->errorInfo();
        return "SQLSTATE[" . $info[0] . "]: " . $info[1] . " - " . $info[2];
    }

}
