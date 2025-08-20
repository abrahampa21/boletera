<?php
include("../src/conexion.php");


// Consulta 
$sqlArticulos = "SELECT idArticulo, nombreArticulo FROM articulo";
$resultadoArticulos = $conexion->query($sqlArticulos);

if (!$resultadoArticulos) {
    die("Error en la consulta: " . $conexion->error);
}

if (!isset($_GET['idVendedor'])) {
    die("Error: ID del vendedor no recibido.");
}
$idVendedor = intval($_GET['idVendedor']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="../assets/css/panelVendedor/articulosRifar.css" />
    <link rel="icon" href="../src/img/logoPaginas.png" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script
        src="https://kit.fontawesome.com/e522357059.js"
        crossorigin="anonymous"></script>
    <title>Boletera por artículo</title>
</head>

<body>

    <div class="contenedor" data-aos="fade-down">
        <table class="tabla-articulos">
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Boletera</th>
                    <th>Clientes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($articulo = $resultadoArticulos->fetch_assoc()) : ?>
                    <tr>
                        <td class="nombre-articulo"><?php echo htmlspecialchars($articulo['nombreArticulo']); ?></td>
                        <td><a data-label="Boletera" href="../panelAdmin/boleteraAdmin.php?idArticulo=<?php echo $articulo['idArticulo']; ?>&idVendedor=<?php echo $idVendedor; ?>">Ver</a>
                        </td>
                        <td><a  data-label="Clientes" href="clienteVendedor.php?idVendedor=<?= $idVendedor ?>&idArticulo=<?= $articulo['idArticulo'] ?>">Ver</a></td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="../panelAdmin/vendedores.php"><i class="fa-solid fa-arrow-left"></i></a>


    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/panelVendedor/articulosRifar.js"></script>
</body>

</html>