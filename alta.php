<?php
require_once 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit_alta'])) {

    // 1. Recibir todos los datos del formulario
    $numero_id = $_POST['numero_id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $altura_m = $_POST['altura_m'];
    $peso_kg = $_POST['peso_kg'];
    $habitat = $_POST['habitat'];
    $color = $_POST['color'];
    $habilidad = $_POST['habilidad'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileType = $_FILES['imagen']['type'];

        $extensiones_permitidas = ['image/jpeg', 'image/png', 'image/webp'];
        $max_file_size = 2 * 1024 * 1024;

        if (!in_array($fileType, $extensiones_permitidas)) {
            $_SESSION['error'] = "Solo se permiten archivos jpg, png o webp.";
            header("Location: alta.php");
            exit();
        }

        if ($fileSize > $max_file_size) {
            $_SESSION['error'] = "La imagen es demasiado pesada. El máximo permitido es 2MB.";
            header("Location: alta.php");
            exit();
        }

        $carpeta_destino = 'uploads/';

        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        $nombre_limpio = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
        $ruta_destino = $carpeta_destino . time() . "_" . $nombre_limpio;

        if (move_uploaded_file($fileTmpPath, $ruta_destino)) {

            $statement = $conexion->prepare("INSERT INTO pokemons(numero_id, nombre, tipo, descripcion, imagen_ruta, altura_m, peso_kg, habitat, color, habilidad) VALUES(?,?,?,?,?,?,?,?,?,?)");

            $statement->bind_param("issssddsss", $numero_id, $nombre, $tipo, $descripcion, $ruta_destino, $altura_m, $peso_kg, $habitat, $color, $habilidad);
            $statement->execute();

            $_SESSION['mensaje'] = "Pokémon agregado exitosamente";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Error crítico al guardar la imagen en el servidor.";
            header("Location: alta.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Por favor, subí una imagen válida.";
        header("Location: alta.php");
        exit();
    }
}

include_once 'header.php';
?>

<main class="container mt-5 mb-5" style="max-width: 700px;">
    <h2 class="text-center mb-4">Agregar Nuevo Pokémon</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'> " . $_SESSION['error'] . " </div>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="card p-4 shadow">
        <form action="alta.php" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Número en la Pokédex</label>
                    <input type="number" class="form-control" name="numero_id" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <!-- Actualizado con todas las opciones de tu ENUM -->
                <select name="tipo" class="form-select" required>
                    <option value="fuego">Fuego</option>
                    <option value="agua">Agua</option>
                    <option value="planta">Planta</option>
                    <option value="electrico">Eléctrico</option>
                    <option value="hielo">Hielo</option>
                    <option value="veneno">Veneno</option>
                    <option value="psiquico">Psíquico</option>
                    <option value="dragon">Dragón</option>
                    <option value="siniestro">Siniestro</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Altura (m)</label>
                    <!-- step="0.1" permite ingresar decimales -->
                    <input type="number" step="0.1" class="form-control" name="altura_m" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" step="0.1" class="form-control" name="peso_kg" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hábitat</label>
                    <input type="text" class="form-control" name="habitat" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="color" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Habilidad</label>
                    <input type="text" class="form-control" name="habilidad" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Imagen del Pokémon</label>
                <input type="file" class="form-control" name="imagen" required>
            </div>

            <button type="submit" class="btn btn-success w-100" name="submit_alta">Guardar Pokémon</button>
        </form>
    </div>
</main>

<?php
include_once 'footer.php';
?>
