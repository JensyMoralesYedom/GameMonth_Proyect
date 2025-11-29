<?php
session_start();
    if(isset($_SESSION["verificado"])){
        echo '<script>
            window.location = "vistas/home.php";
        </script>';
        
    }else{
        echo '<script>
            window.location = "vistas/login.php";
        </script>';
        
    }
?>
<h1>Hola :d</h1>