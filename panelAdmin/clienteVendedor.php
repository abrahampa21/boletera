<?php
include("../src/conexion.php");

if (!isset($_GET['idVendedor']) || !isset($_GET['idArticulo'])) {
    die('Error: ID del vendedor o del artículo no recibido.');
}

$idVendedor = intval($_GET['idVendedor']);
$idArticuloSel = intval($_GET['idArticulo']);
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
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <a href="boleteraVendedor.php?idVendedor=<?= $idVendedor ?>"><i class="fa-solid fa-arrow-left"></i></a>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/login.js"></script>
</body>
</html>