<?php

class Conexion{
    public static function conectar(){
        $link = new PDO("mysql:host=localhost;dbname=tienda_videojuegos","root","", array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));
        $link->exec("set names utf8");
        return $link;
    }
}