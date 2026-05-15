<?php

require_once 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $statement_select = $conexion->prepare("SELECT imagen_ruta FROM pokemons WHERE id = ?");
    $statement_select->bind_param("i", $id);
    $statement_select->execute();
    $resultado = $statement_select->get_result();

    if($pokemon = $resultado->fetch_assoc()){
        $ruta_imagen = $pokemon['imagen_ruta'];

        if(!empty($ruta_imagen) && file_exists($ruta_imagen)){
            unlink($ruta_imagen);
        }
    }
$statement_select->close();

    $statement = $conexion->prepare("DELETE FROM pokemons WHERE id = ?");
    $statement->bind_param("i", $id);
    $statement->execute();

    $_SESSION['mensaje'] = "Pokémon eliminado correctamente";
}

header('Location: index.php');
exit();
?>


