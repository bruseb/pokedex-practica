<?php

require_once 'db.php';

if (!isset($_GET['id'])) {
    header("location: index.php");
    exit();
}

$id = $_GET['id'];

$statement = $conexion->prepare("SELECT * FROM pokemons WHERE id=?");

$statement->bind_param("i", $id);

$statement->execute();

$resultado = $statement->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION['error'] = 'no_existe';
    header("location: index.php");
    exit();
}


$pokemon = $resultado->fetch_assoc();

//echo $pokemon['nombre'];

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

include_once 'header.php';
?>
    <main class="container mt-5">


        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <div class="row g-0">

                <div class="col-md-6 d-flex justify-content-center align-items-center p-4 bg-white">

                    <img src="<?php echo htmlspecialchars($pokemon['imagen_ruta']); ?>"
                         class="img-fluid"
                         alt="<?php echo htmlspecialchars($pokemon['nombre']); ?>"
                         style="max-height: 400px; object-fit: contain;">


                </div>

                <div class="col-md-6 bg-light">
                    <div class="card-body p-5">

                        <h1 class="card-title text-capitalize fw-bold mb-4" style="color: #2c3e50;">
                            <?php echo htmlspecialchars($pokemon['nombre']); ?>
                        </h1>

                        <div class="mb-4">
                            <h5 class="text-muted mb-2">Tipo</h5>
                            <img src="assets/tipos/<?php echo htmlspecialchars($iconosTipos[$pokemon['tipo']]); ?>"
                                 width="35">
                        </div>

                        <hr>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Id</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['id']); ?>
                            </p>
                        </div>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Número Id</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['numero_id']); ?>
                            </p>
                        </div>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Descripción</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['descripcion']); ?>
                            </p>
                        </div>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Habitat</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['habitat']); ?>
                            </p>
                        </div>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Peso</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['peso_kg']) . " KG" ?>
                            </p>
                        </div>

                        <div class="mb-4 mt-4">
                            <h5 class="text-muted mb-3">Altura</h5>
                            <p class="card-text fs-5">
                                <?php echo htmlspecialchars($pokemon['altura_m']) . " M" ?>
                            </p>
                        </div>


                        <div class="mt-5">
                            <a href="index.php" class="btn btn-outline-secondary btn-lg px-4">Volver a la Pokédex</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </main>

<?php
$statement->close();
$conexion->close();
include_once 'footer.php';
?>