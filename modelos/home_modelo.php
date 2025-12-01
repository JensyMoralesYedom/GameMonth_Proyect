<?php



class HomeModelo
{
    
    
    public function obtenerDatos()
    {
        include_once 'conexion.php';
        $conexion = Conexion::conectar();
        $query = "SELECT * FROM videojuegos"; 
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}