<?php
include_once "../modelos/biblioteca_modelo.php";

class BibliotecaControlador{

    public static function mostrarLibros(){
        
        $id_user = $_SESSION['user_id'];
        $libros = biblioteca_modelo::obtenerLibros($id_user);
        return $libros;
    }

}

?>