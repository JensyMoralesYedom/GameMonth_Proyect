<?php
include_once "../modelos/registro_modelo.php";

class registro_controlador {

    public function registrarUsuario() {

        $nombre   = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email    = $_POST['email'];
        $telefono = $_POST['telefono'];
        $usuario  = $_POST['usuario'];
        $password = $_POST['pass'];

        // Validar campos
        if (empty($nombre) || empty($apellido) || empty($telefono) || empty($email) || empty($usuario) || empty($password)) {
            echo '<script>alert("Todos los campos son obligatorios"); window.location="../vistas/registro.php";</script>';
            exit;
        }

        // Hashear contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Registrar usuario en la base de datos
        $nuevoUsuarioID = registro_modelo::registrarUsuarioNormal(
            $nombre,
            $apellido,
            $email,
            $telefono,        
            $usuario,
            $password
        );

        if ($nuevoUsuarioID) {

            // ⚠️ NO iniciar sesión todavía
            // ⚠️ NO crear suscripción todavía

            // Guardamos el ID temporalmente para completar pago
            session_start();
            $_SESSION['usuario_pendiente'] = $nuevoUsuarioID;

            // Enviar a página de suscripción
            echo '<script>
                    alert("Cuenta creada. Ahora debes suscribirte para activar tu cuenta.");
                    window.location="../vistas/suscripcion.php";
                  </script>';
        } else {
            echo '<script>
                    alert("Error al registrar. Usuario o email ya existen.");
                    window.location="../vistas/registro.php";
                  </script>';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registro = new registro_controlador();
    $registro->registrarUsuario();
}
