<?php

require_once 'db.php';

$iconosTipos = [
        'planta' => 'Grass.ico',
        'fuego' => 'Fire.ico',
        'agua' => 'Water.ico',
        'electrico' => 'Electric.ico',
        'psiquico' => 'Psychic.ico',
        'siniestro' => 'Ghost.ico',
        'dragon' => 'Dragon.ico',
        'veneno' => 'Poison.ico',
        'hielo' => 'Ice.ico',

];

if (isset($_GET['search'])) {
    $busqueda = $_GET['search'];

    $statement = $conexion->prepare("SELECT * FROM pokemons WHERE nombre LIKE ?");

    $string = $busqueda . "%";
    $statement->bind_param("s", $string);
    $statement->execute();

    $resultadoBusqueda = $statement->get_result();

    if ($resultadoBusqueda->num_rows === 1) {
        $pokemon = $resultadoBusqueda->fetch_assoc();
        if (strtolower($busqueda) === strtolower($pokemon['nombre'])) {
            header('Location: detalle.php?id=' . $pokemon['id']);
            exit();
        } else {
            $resultadoBusqueda->data_seek(0);
            $resultado = $resultadoBusqueda;
        }

    } elseif ($resultadoBusqueda->num_rows === 0) {
        $_SESSION['error'] = 'no_existe';
        header('Location: index.php');
        exit();
    } else {
        $resultado = $resultadoBusqueda;
    }
} else {
    $resultado = $conexion->query("SELECT * FROM pokemons ORDER BY numero_id ASC");
}

include_once 'header.php'
?>

    <main class="container mt-5">
        <h1 class="text-center mb-4">Mi Pokédex</h1>

        <form action="index.php" method="GET" class="mb-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text"
                               name="search"
                               class="form-control border-primary"
                               placeholder="Buscar Pokémon por nombre...">
                        <button class="btn btn-primary px-4" type="submit">Buscar</button>
                    </div>
                </div>
            </div>
        </form>

        <?php
        if (isset($_SESSION['error']) && $_SESSION['error'] == 'no_existe') {

            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>El Pokémon que buscás no existe.
                   </div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>" . $_SESSION ['mensaje'] . "</div>";
            unset($_SESSION['mensaje']);
        }

        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
            <div class="d-flex justify-content-end mb-4">
                <a href="alta.php" class="btn btn-success fw-bold px-4 shadow-sm">
                    + Nuevo Pokémon
                </a>
            </div>
        <?php endif; ?>

        <div class="row">

            <?php
            while ($pokemon = $resultado->fetch_assoc()) :
                ?>

                <div class="col-md-4 col-12 mb-4">

                    <div class="card h-100 shadow-sm">

                        <img src="<?php echo $pokemon['imagen_ruta']; ?>" class="card-img-top"
                             alt="<?php echo $pokemon['nombre']; ?>">

                        <div class="card-body text-center">

                            <h5 class="card-title"><?php echo $pokemon['nombre']; ?></h5>


                            <p class="card-text">Tipo:<img
                                        src="assets/tipos/<?php echo $iconosTipos[$pokemon['tipo']]; ?>"
                                        alt="<?php echo $pokemon['tipo']; ?>" width="20">
                            </p>

                            <a href="detalle.php?id=<?php echo $pokemon['id']; ?>" class="btn btn-primary mt-3 w-100">Ver
                                detalle</a>

                        </div>
                    </div>


                </div>

            <?php endwhile; ?>

        </div>
    </main>

<?php
include_once 'footer.php';
$conexion->close();
?>