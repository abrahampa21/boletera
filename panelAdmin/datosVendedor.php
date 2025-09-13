<?php
include("../src/conexion.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("ID de vendedor no proporcionado.");
}

$id_vendedor = intval($_GET['id']);

$sql = "SELECT nombre, apellidoP, apellidoM, email, noCelular, noReferencia, fotoINE, video FROM vendedor WHERE idVendedor = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
  die("Vendedor no encontrado.");
}

$vendedor = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/css/panelAdmin/datosVendedor.css" />
  <link rel="icon" href="../src/img/logoPaginas.png" />
  <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
  <title>Datos del Vendedor</title>
</head>

<body>
  <div class="card-container">
    <div class="card">
      <div class="card-header">
        <img src="data:image/png;base64,<?php echo base64_encode($vendedor['fotoINE']); ?>" alt="Foto INE del vendedor" class="vendedor-img" />
        <h2>Datos del Vendedor</h2>
      </div>

      <div class="card-body">
        <div class="info-row"><span>Nombres:</span> <?php echo htmlspecialchars($vendedor['nombre']); ?></div>
        <div class="info-row"><span>Apellido Paterno:</span> <?php echo htmlspecialchars($vendedor['apellidoP']); ?></div>
        <div class="info-row"><span>Apellido Materno:</span> <?php echo htmlspecialchars($vendedor['apellidoM']); ?></div>
        <div class="info-row"><span>Correo Electrónico:</span> <?php echo htmlspecialchars($vendedor['email']); ?></div>
        <div class="info-row"><span>Número de Celular:</span> <?php echo htmlspecialchars($vendedor['noCelular']); ?></div>
        <div class="info-row"><span>Número de Referencia:</span> <?php echo htmlspecialchars($vendedor['noReferencia']); ?></div>
        <div class="info-row"><span>Compromiso:</span>
          <?php if (!empty($vendedor['video'])): ?>
            <video width="320" height="240" controls>
              <source src="data:video/mp4;base64,<?php echo base64_encode($vendedor['video']); ?>" type="video/mp4">
              Tu navegador no soporta la reproducción de video.
            </video>
          <?php else: ?>
            <p>No se ha subido un video de compromiso.</p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <a href="../panelAdmin/vendedores.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i></a>

</body>

</html>

<?php
$stmt->close();
$conexion->close();
?>