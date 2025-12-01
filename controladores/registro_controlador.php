<?php
include_once "../modelos/registro_modelo.php";
    class registro_controlador{

        

        public static function registrarUsuario(){
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $usuario = $_POST['username'];
            $password = $_POST['pass'];
            
            

            $registro = registro_modelo::registrarUsuarioNormal($nombre, $apellido, $email, $telefono, $usuario, $password);

            if($registro){
                echo '<script>
                        alert("Registro Exitoso");
                        window.location = "login.php";
                      </script>';
            } else {
                echo '<script>
                        alert("Error al registrar, vuelve a intentarlo");
                        window.location = "registro.php";
                      </script>';
            }
        }

    }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrocontrol = new registro_controlador();
    $registrocontrol->registrarUsuario();
}