<?php
include_once "conexion.php";
class registro_modelo{

    public static function registrarUsuarioNormal($nombre, $apellido, $email, $telefono, $usuario, $password){
        $db =  Conexion::conectar();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, usuario, password_hash, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $apellido, PDO::PARAM_STR);
        $stmt->bindParam(3, $email, PDO::PARAM_STR);
        $stmt->bindParam(4, $telefono, PDO::PARAM_STR);
        $stmt->bindParam(5, $usuario, PDO::PARAM_STR);
        $stmt->bindParam(6, $password, PDO::PARAM_STR);
        
        return $stmt->execute();
    }


}