<?php

include '../modelos/home_modelo.php';

class HomeControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new HomeModelo();
    }

    public function mostrarDatos()
    {
        return $this->modelo->obtenerDatos();
    }
}