<?php

require_once 'db.php';

if (isset($_POST['submit'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $statement = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");

    $statement->bind_param("s", $usuario);
    $statement->execute();
    $resultado = $statement->get_result();

    if ($resultado->num_rows === 1) {
        $usuarioExistente = $resultado->fetch_assoc();
        if (password_verify($password, $usuarioExistente['password'])) {
            $_SESSION['admin'] = true;
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = 'Contraseña incorrecta';
        }
    } else {
        $_SESSION['error'] = 'Usuario inexistente';
    }
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login Pokédex</title>
</head>
<body class="bg-light p-5">

<div class="container" style="max-width: 400px;">
    <h2 class="text-center mb-4">Ingreso Admin</h2>


    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class= 'alert alert-danger'>". $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    ?>


    <div class="card p-4 shadow">

        <form action="login.php" method="post">

            <div class="mb-3">
                <label class="form-label">Usuario</label>

                <input type="text" class="form-control" required name="usuario">
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" required name="password">
            </div>

            <button type="submit" class="btn btn-primary w-100" name="submit">Entrar</button>

        </form>
    </div>
</div>

</body>
</html>
