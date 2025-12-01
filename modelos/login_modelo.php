<?php
include_once "conexion.php";


class LoginModelo
{
    public static function auntenticar($user, $password)
    {
        $db =  Conexion::conectar();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bindParam(1, $user, PDO::PARAM_STR);
        
        $resultado=$stmt->execute();
        

        $usuarioRe = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$usuarioRe) {
            return false;
        }
        if($resultado){
            
            if($password == $usuarioRe['password_hash']) {
                return $usuarioRe;
            } else {
                return false;
            }
        }
        

    }
}
