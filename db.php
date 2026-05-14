<?php

$conexion = new mysqli("localhost", "root", "", "pokedex-practica");

if ($conexion->connect_error) {
    die("Fallo la conexion " . $conexion->connect_error);
}

$conexion->set_charset("utf8");

session_start();




