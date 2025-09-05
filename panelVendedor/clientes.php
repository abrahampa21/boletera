<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../src/conexion.php");

if (!isset($_SESSION['idVendedor'])) {
    die('Error: Sesión no iniciada.');
}

if (!isset($_GET['idArticulo'])) {
    die('Error: ID del artículo no recibido.');
}

$idVendedor = intval($_SESSION['idVendedor']);
$idArticuloSel = intval($_GET['idArticulo']);

// Consulta: obtener clientes + boletos vendidos por ese vendedor para ese artículo
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
    <title>Clientes</title>
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
                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row["apellidos"]) ?></td>
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
                        <td colspan="2">No hay clientes registrados para este vendedor y artículo.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="articulosRifar.php"><i class="fa-solid fa-arrow-left"></i></a>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>