<?php
include_once "conexion.php";
class biblioteca_modelo{

    public static function obtenerLibros($id_user){
        $db =  Conexion::conectar();
        
        $userId = $id_user;
        $stmt = $db->prepare("SELECT * FROM biblioteca_usuarios WHERE id_usuario = ?");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>