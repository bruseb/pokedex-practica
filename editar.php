<?php
require_once 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$statement = $conexion->prepare("SELECT * FROM pokemons WHERE id = ?");
$statement->bind_param("i", $id);
$statement->execute();
$resultado = $statement->get_result();
$pokemonActual = $resultado->fetch_assoc();

if (!$pokemonActual) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit_editar'])) {

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
        header("Location: editar.php?id=" . $id);
        exit();
    }

    if ($fileSize > $max_file_size) {
        $_SESSION['error'] = "La imagen es demasiado pesada. El máximo permitido es 2MB.";
        header("Location: editar.php?id=" . $id);
        exit();
    }

    $carpeta_destino = 'uploads/';

    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }

    $nombre_limpio = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    $ruta_destino = $carpeta_destino . time() . "_" . $nombre_limpio;

    if (move_uploaded_file($fileTmpPath, $ruta_destino)) {

        if (!empty($pokemonActual['imagen_ruta']) && file_exists($pokemonActual['imagen_ruta'])) {
            unlink($pokemonActual['imagen_ruta']);
        }

        $statement = $conexion->prepare("UPDATE pokemons SET numero_id=?, nombre=?, tipo=?,descripcion=?, altura_m=?,peso_kg=?,habitat=?,color=?,habilidad=?, imagen_ruta=? WHERE id=? ");
        $statement->bind_param("isssddssssi",$numero_id, $nombre, $tipo, $descripcion, $altura_m, $peso_kg, $habitat, $color, $habilidad, $ruta_destino, $id);
        $statement->execute();
    }
} else {

        $statement = $conexion->prepare("UPDATE pokemons SET numero_id=?, nombre=?, tipo=?,descripcion=?, altura_m=?,peso_kg=?,habitat=?,color=?,habilidad=? WHERE id=? ");
        $statement->bind_param("isssddsssi",$numero_id, $nombre, $tipo, $descripcion, $altura_m, $peso_kg, $habitat, $color, $habilidad, $id);
        $statement->execute();
    }

    $_SESSION['mensaje'] = "Pokémon actualizado exitosamente";
    header("Location: index.php");
    exit();
}

include_once 'header.php';
?>

<main class="container mt-5 mb-5" style="max-width: 700px;">
    <h2 class="text-center mb-4">Editar Pokémon</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'> " . $_SESSION['error'] . " </div>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="card p-4 shadow">
        <form action="editar.php?id=<?php echo $pokemonActual['id']; ?>" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Número en la Pokédex</label>
                    <input type="number" class="form-control" name="numero_id"
                           value="<?php echo $pokemonActual['numero_id'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="<?php echo $pokemonActual['nombre'] ?>"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>

                <select name="tipo" class="form-select" required>
                    <option value="fuego"<?php echo ($pokemonActual['tipo'] === 'fuego') ? 'selected' : ''; ?> >Fuego
                    </option>
                    <option value="agua"<?php echo ($pokemonActual['tipo'] === 'agua') ? 'selected' : ''; ?>>Agua
                    </option>
                    <option value="planta" <?php echo ($pokemonActual['tipo'] == 'planta') ? 'selected' : ''; ?>>Planta
                    </option>
                    <option value="electrico" <?php echo ($pokemonActual['tipo'] == 'electrico') ? 'selected' : ''; ?>>
                        Eléctrico
                    </option>
                    <option value="hielo" <?php echo ($pokemonActual['tipo'] == 'hielo') ? 'selected' : ''; ?>>Hielo
                    </option>
                    <option value="veneno" <?php echo ($pokemonActual['tipo'] == 'veneno') ? 'selected' : ''; ?>>
                        Veneno
                    </option>
                    <option value="psiquico" <?php echo ($pokemonActual['tipo'] == 'psiquico') ? 'selected' : ''; ?>>
                        Psíquico
                    </option>
                    <option value="dragon" <?php echo ($pokemonActual['tipo'] == 'dragon') ? 'selected' : ''; ?>>
                        Dragón
                    </option>
                    <option value="siniestro" <?php echo ($pokemonActual['tipo'] == 'siniestro') ? 'selected' : ''; ?>>
                        Siniestro
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3"
                          required><?php echo $pokemonActual['descripcion'] ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Altura (m)</label>
                    <input type="number" step="0.1" class="form-control" name="altura_m"
                           value="<?php echo $pokemonActual['altura_m'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" step="0.1" class="form-control" name="peso_kg"
                           value="<?php echo $pokemonActual['peso_kg'] ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hábitat</label>
                    <input type="text" class="form-control" name="habitat"
                           value="<?php echo $pokemonActual['habitat'] ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="color" value="<?php echo $pokemonActual['color'] ?>"
                           required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Habilidad</label>
                    <input type="text" class="form-control" name="habilidad"
                           value="<?php echo $pokemonActual['habilidad'] ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Imagen actual del Pokémon</label><br>

                <img src="<?php echo $pokemonActual['imagen_ruta']; ?>"
                     alt="Foto de <?php echo $pokemonActual['nombre']; ?>"
                     style="max-width: 150px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #ccc;">

                <label class="form-label">Subir nueva imagen (opcional)</label>
                <input type="file" class="form-control" name="imagen">
            </div>

            <button type="submit" class="btn btn-success w-100" name="submit_editar">Guardar Pokémon</button>
        </form>
    </div>
</main>

<?php
include_once 'footer.php';
?>
