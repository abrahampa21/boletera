<?php
session_start();
include("../src/conexion.php");

if (!isset($_SESSION['usuarioVendedor'])) {
  die('Error: Sesión de vendedor no iniciada.');
}

if (!isset($conexion) || !$conexion) {
  die('Error: No se pudo conectar a la base de datos.');
}

$boletos = [];
$usuario = $_SESSION['usuarioVendedor'];
// Obtener el idvendedor usando el usuario
$sqlVendedor = "SELECT idVendedor FROM vendedor WHERE usuario = '$usuario'";
$resVendedor = $conexion->query($sqlVendedor);
if (!$resVendedor) {
  die('Error en consulta vendedor: ' . $conexion->error);
}
$idVendedor = 0;
if ($resVendedor->num_rows > 0) {
  $rowVendedor = $resVendedor->fetch_assoc();
  $idVendedor = intval($rowVendedor['idVendedor']);
}

// Obtener artículos disponibles
$articulos = [];
$sqlArticulos = "SELECT idArticulo, nombreArticulo FROM articulo";
$resArticulos = $conexion->query($sqlArticulos);
if ($resArticulos) {
  while ($row = $resArticulos->fetch_assoc()) {
    $articulos[] = $row;
  }
}

// Obtener el idArticulo seleccionado
$idArticuloSel = isset($_GET['idArticulo']) ? intval($_GET['idArticulo']) : (count($articulos) > 0 ? $articulos[0]['idArticulo'] : 0);

if ($idVendedor > 0 && $idArticuloSel > 0) {
  $sql = "SELECT folioBoleto FROM vendedorboleto WHERE idVendedor = $idVendedor AND idArticulo = $idArticuloSel ORDER BY folioBoleto ASC";
  $res = $conexion->query($sql);
  if (!$res) {
    die('Error en consulta boletos: ' . $conexion->error);
  }
  while ($row = $res->fetch_assoc()) {
    $boletos[] = $row['folioBoleto'];
  }
}
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
    <title>Boletera</title>
  </head>
  <body>
  <main class="boletera" style="background: transparent; box-shadow: none; border: none;">
    <?php if (count($boletos) > 0): ?>
      <div class="boletos-grid">
        <?php foreach ($boletos as $folio): ?>
          <div class="boleto-card">
            <?= htmlspecialchars($folio) ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No tienes boletos asignados para este artículo.</p>
    <?php endif; ?>
    <a href="#" class="registrar-cliente" id="registrar-cliente">Registrar cliente</a>
  </main>
  </body>
</html>
