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
$boletosVendidos = []; // Array para almacenar boletos ya vendidos
$idVendedor = 0;

if (isset($_SESSION['usuarioVendedor'])) {
    $usuario = $_SESSION['usuarioVendedor'];
    $sqlVendedor = "SELECT idVendedor FROM vendedor WHERE usuario = '$usuario'";
    $resVendedor = $conexion->query($sqlVendedor);
    if ($resVendedor && $resVendedor->num_rows > 0) {
        $rowVendedor = $resVendedor->fetch_assoc();
        $idVendedor = intval($rowVendedor['idVendedor']);
    }
} elseif (isset($_GET['idVendedor'])) {
    $idVendedor = intval($_GET['idVendedor']);
} else {
    die('ID del vendedor no disponible.');
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
  // Obtener todos los boletos del vendedor para este artículo
  $sql = "SELECT folioBoleto FROM vendedorboleto WHERE idVendedor = $idVendedor AND idArticulo = $idArticuloSel ORDER BY folioBoleto ASC";
  $res = $conexion->query($sql);
  if (!$res) {
    die('Error en consulta boletos: ' . $conexion->error);
  }
  while ($row = $res->fetch_assoc()) {
    $boletos[] = $row['folioBoleto'];
  }
  
  // Obtener boletos ya vendidos (que están en clienteboleto)
  $sqlVendidos = "SELECT DISTINCT cb.folioBoleto 
                  FROM clienteboleto cb 
                  WHERE cb.idVendedor = $idVendedor";
  $resVendidos = $conexion->query($sqlVendidos);
  if ($resVendidos) {
    while ($row = $resVendidos->fetch_assoc()) {
      $boletosVendidos[] = $row['folioBoleto'];
    }
  }
}

//Para registroCliente.php
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
  <title>Boletera</title>
</head>

<body>
  <main class="boletera">
    <?php if (count($boletos) > 0): ?>
      <div class="boletos-grid">
        <?php foreach ($boletos as $folio): ?>
          <?php $esVendido = in_array($folio, $boletosVendidos); ?>
          <div class="boleto-card <?= $esVendido ? 'vendido' : '' ?>" 
               data-folio="<?= htmlspecialchars($folio) ?>"
               <?= $esVendido ? 'data-vendido="true"' : '' ?>>
            <?= htmlspecialchars($folio) ?>
            <?php if ($esVendido): ?>
              <span class="vendido-label">VENDIDO</span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No tienes boletos asignados para este artículo.</p>
    <?php endif; ?>
    <a href="registroCliente.php?idArticulo=<?= $idArticuloSel ?>" data-idarticulo="<?= $idArticuloSel ?>" class="registrar-cliente" id="registrar-cliente">Registrar cliente</a>
    <a href="#" class="imprimir-boletera" id="imprimir-boletera">Guardar boletera en PDF</a>
  </main>

  <a href="../panelVendedor/articulosRifar.php"><i class="fa-solid fa-arrow-left"></i></a>

  <script>
    // Pasar los boletos vendidos al JS
    const boletosVendidos = <?= json_encode($boletosVendidos) ?>;
  </script>
  <script src="../assets/js/panelVendedor/boletera.js"></script>
</body>

</html>