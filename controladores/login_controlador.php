<?php
include_once "../modelos/login_modelo.php";
class LoginControler
{
    public function loginUser()
    {
        // Lógica para manejar el inicio de sesión del usuario
        $user = $_POST['usuario'];
        $password = $_POST['pass'];
        $usuarioRe = LoginModelo::auntenticar($user, $password);

        if ($usuarioRe) {
            session_start();
            $_SESSION['verificado'] = 'si';
            $_SESSION['user_id'] = $usuarioRe['id'];
            $_SESSION['username'] = $usuarioRe['usuario'];

            echo '<script>
                    window.location = "../index.php";
                  </script>';
        } else {
            echo '<script>
                    alert("Error al ingresar, vuelve a intentarlo");
                    window.location = "login.php";
                  </script>';
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginControler = new LoginControler();
    $loginControler->loginUser();
}