<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Pokédex</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Pokédex</a>

        <div class="d-flex">
            <?php

            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
              echo "<a href='logout.php' class='btn btn-light btn-sm'>Cerrar Sesión</a>";
            } else{
                echo "<a href='login.php' class='btn btn-light btn-sm'>Ingresar</a>";
            }
            ?>
        </div>
    </div>
</nav>


