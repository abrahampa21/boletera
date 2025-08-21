<?php
include("../src/conexion.php");

if (!isset($_GET['idVendedor']) || !isset($_GET['idArticulo'])) {
    die('Error: ID del vendedor o del artículo no recibido.');
}

$idVendedor = intval($_GET['idVendedor']);
$idArticuloSel = intval($_GET['idArticulo']);

$boletos = [];

// Obtener boletos del vendedor para el artículo
$sql = "SELECT folioBoleto FROM vendedorboleto WHERE idVendedor = $idVendedor AND idArticulo = $idArticuloSel ORDER BY folioBoleto ASC";
$res = $conexion->query($sql);

if (!$res) {
    die('Error en consulta boletos: ' . $conexion->error);
}

while ($row = $res->fetch_assoc()) {
    $boletos[] = $row['folioBoleto'];
}

// Obtener boletos vendidos para el vendedor
$boletosVendidos = [];
$sqlVendidos = "SELECT DISTINCT cb.folioBoleto FROM clienteboleto cb WHERE cb.idVendedor = $idVendedor";
$resVendidos = $conexion->query($sqlVendidos);
if ($resVendidos) {
    while ($row = $resVendidos->fetch_assoc()) {
        $boletosVendidos[] = $row['folioBoleto'];
    }
}


// Para usar en otras partes si se necesita
session_start();
$_SESSION['idArticuloSel'] = $idArticuloSel;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/css/panelVendedor/boletera.css" />
    <link rel="icon" href="../src/img/logoPaginas.png" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Boletera del vendedor</title>
</head>

<body>
    <main class="boletera">
        <div class="boletos-grid">
            <?php
            foreach ($boletos as $boleto) {
                $esVendido = in_array($boleto, $boletosVendidos);
                $clase = "boleto-card";
                if ($esVendido) {
                    $clase .= " vendido";
                }
                echo '<div class="' . $clase . '">';
                echo htmlspecialchars($boleto);
                if ($esVendido) {
                    echo '<span class="vendido-label">VENDIDO</span>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </main>

    <a href="boleteraVendedor.php?idVendedor=<?= $idVendedor ?>"><i class="fa-solid fa-arrow-left"></i></a>


</body>

</html>