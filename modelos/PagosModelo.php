<?php
// modelos/PagosModelo.php

require_once __DIR__ . '/conexion.php';

class PagosModelo
{
    /**
     * Registra un pago en la tabla pagos.
     * @param int $id_usuario
     * @param int $id_suscripcion
     * @param float $monto
     * @param string $transaction_id
     * @return bool
     */
    public static function registrarPago(int $id_usuario, int $id_suscripcion, float $monto, string $transaction_id)
    {
        try {
            $db = Conexion::conectar();

            $sql = "INSERT INTO pagos (id_usuario, id_suscripcion, monto, fecha, estado, transaction_id)
                    VALUES (:id_usuario, :id_suscripcion, :monto, NOW(), 'completado', :transaction_id)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_suscripcion', $id_suscripcion, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':transaction_id', $transaction_id);

            return $stmt->execute();

        } catch (PDOException $e) {
            // opcional: loguear $e->getMessage()
            return false;
        }
    }

    /**
     * Obtener los pagos de un usuario (opcional utilidad).
     * @param int $id_usuario
     * @return array|false
     */
    public static function obtenerPagosPorUsuario(int $id_usuario)
    {
        try {
            $db = Conexion::conectar();

            $sql = "SELECT * FROM pagos WHERE id_usuario = :id_usuario ORDER BY fecha DESC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }
}
