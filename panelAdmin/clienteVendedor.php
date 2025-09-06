<?php
include("../src/conexion.php");

if (!isset($_GET['idVendedor']) || !isset($_GET['idArticulo'])) {
    die('Error: ID del vendedor o del artículo no recibido.');
}

$idVendedor = intval($_GET['idVendedor']);
$idArticuloSel = intval($_GET['idArticulo']);

// Consulta para obtener nombre del cliente + sus boletos en base al artículo y vendedor que se lo vendió
$sqlClienteBoletos = "SELECT cliente.nombre, cliente.apellidos, 
    cliente.noCelular, cliente.direccionFisica, cliente.entidad, cliente.comprobante,
    GROUP_CONCAT(clienteboleto.folioBoleto SEPARATOR ', ') as Boletos
FROM clienteboleto
INNER JOIN cliente ON clienteboleto.idCliente = cliente.idCliente
INNER JOIN vendedorBoleto ON clienteboleto.folioBoleto = vendedorBoleto.folioBoleto
WHERE vendedorBoleto.idArticulo = $idArticuloSel
  AND vendedorBoleto.idVendedor = $idVendedor
GROUP BY cliente.idCliente";
$resClienteBoletos = $conexion->query($sqlClienteBoletos);

if (!$resClienteBoletos) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/panelAdmin/vendedores.css">
    <link rel="icon" href="../src/img/logoPaginas.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Vendedores</title>
</head>

<body>
    <div class="contenedor" data-aos="fade-down">
        <table class="tabla-clientes-vendedores" id="tabla-clientes-vendedores">
            <caption>Clientes registrados</caption>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Boletos</th>
                    <th>Comprobante</th>
                    <th>Número de celular</th>
                    <th>Domicilio</th>
                    <th>Ciudad</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resClienteBoletos && $resClienteBoletos->num_rows > 0): ?>
                    <?php while ($row = $resClienteBoletos->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre'] . " " . $row['apellidos']) ?></td>
                            <td><?= htmlspecialchars($row['Boletos']) ?></td>

                            <td>
                                <?php if (!empty($row['comprobante'])): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($row['comprobante']) ?>" width="100" alt="Comprobante">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($row['noCelular']) ?></td>
                            <td><?= htmlspecialchars($row['direccionFisica']) ?></td>
                            <td><?= htmlspecialchars($row['entidad']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay clientes registrados para este vendedor y artículo.</td>
                    </tr>
                <?php endif; ?>

                <!--Para tabletas y celulares -->
                <div class="cards-container">
                    <?php
                    if ($resClienteBoletos && $resClienteBoletos->num_rows > 0) {
                        $resClienteBoletos->data_seek(0);
                        while ($fila = $resClienteBoletos->fetch_assoc()) {
                            echo "<div class='card'>";
                            echo "<h3>" . htmlspecialchars($fila['nombre']) . " " . ($fila['apellidos']) . "</h3>";
                            echo "<h3>" . htmlspecialchars($fila['Boletos']) . " " . "</h3>";

                            // Imagen del comprobante
                            if (!empty($fila['comprobante'])) {
                                echo "<img src='data:image/jpeg;base64," . base64_encode($fila['comprobante']) . "' width='100' alt='Comprobante'>";
                            } else {
                                echo "<p>Comprobante no disponible</p>";
                            }
                            echo "<h3>" . htmlspecialchars($fila['noCelular']) . " " . "</h3>";
                            echo "<h3>" . htmlspecialchars($fila['direccionFisica']) . " " . "</h3>";
                            echo "<h3>" . htmlspecialchars($fila['entidad']) . " " . "</h3>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No hay clientes registrados</p>";
                    }
                    ?>
                </div>
            </tbody>
        </table>
    </div>

    <a href="boleteraVendedor.php?idVendedor=<?= $idVendedor ?>"><i class="fa-solid fa-arrow-left"></i></a>

    <div id="modal-img" class="modal">
        <span class="cerrar">&times;</span>
        <img class="modal-contenido" id="img-ampliada">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>