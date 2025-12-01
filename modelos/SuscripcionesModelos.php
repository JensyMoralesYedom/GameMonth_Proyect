<?php
// modelos/SuscripcionesModelo.php

require_once __DIR__ . '/conexion.php';

class SuscripcionesModelo
{
    /**
     * Crear una suscripción activa de 30 días y devolver el id_suscripcion.
     * @param int $id_usuario
     * @param float $monto
     * @return int|false id_suscripcion o false en error
     */
    public static function crearSuscripcion(int $id_usuario, float $monto)
    {
        try {
            $db = Conexion::conectar();

            $sql = "INSERT INTO suscripciones (id_usuario, fecha_inicio, fecha_fin, estado, monto)
                    VALUES (:id_usuario, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'activa', :monto)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);

            $ok = $stmt->execute();
            if (!$ok) {
                return false;
            }

            // retornar id de la suscripción recién creada
            $lastId = (int)$db->lastInsertId();
            return $lastId;

        } catch (PDOException $e) {
            // opcional: loguear $e->getMessage()
            return false;
        }
    }

    /**
     * Comprueba si un usuario tiene una suscripción activa y vigente.
     * Devuelve la fila de la suscripción o false si no existe/expirada.
     * @param int $id_usuario
     * @return array|false
     */
    public static function tieneSuscripcionActiva(int $id_usuario)
    {
        try {
            $db = Conexion::conectar();

            $sql = "SELECT * FROM suscripciones
                    WHERE id_usuario = :id_usuario
                      AND estado = 'activa'
                      AND fecha_fin >= NOW()
                    ORDER BY fecha_fin DESC
                    LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row === false) return false;
            return $row;

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Opcional: marcar una suscripción como expirada/cancelada.
     * @param int $id_suscripcion
     * @param string $nuevoEstado ('expirada', 'cancelada', etc.)
     * @return bool
     */
    public static function actualizarEstado(int $id_suscripcion, string $nuevoEstado)
    {
        try {
            $db = Conexion::conectar();

            $sql = "UPDATE suscripciones SET estado = :estado WHERE id_suscripcion = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':estado', $nuevoEstado);
            $stmt->bindParam(':id', $id_suscripcion, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }
}
