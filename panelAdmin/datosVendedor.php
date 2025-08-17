<?php
// Conexión a la base de datos
include("../src/conexion.php");

// Verificar si se recibió un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de vendedor no proporcionado.");
}

$id_vendedor = intval($_GET['id']); // Sanitiza el valor

// Consulta
$sql = "SELECT nombre, apellidoP, apellidoM, email, noCelular, noReferencia, fotoINE FROM vendedor WHERE idVendedor = ?";
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
    <link rel="stylesheet" href="../assets/css/panelAdmin/vendedores.css" />
    <link rel="icon" href="../src/img/logoPaginas.png" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Datos Personales</title>
  </head>
  <body class="card-vendedores">
    <table class="datos-vendedores" id="datos-vendedores">
      <caption>Datos Personales</caption>
      <tbody>
        <tr>
          <td colspan="2" style="text-align:center;">
            <img src="data:image/png;base64,<?php echo base64_encode($vendedor['fotoINE']); ?>" alt="foto-ine-vendedor" style="max-width: 200px;" />
          </td>
        </tr>
        <tr>
          <td>Nombres</td>
          <td><?php echo htmlspecialchars($vendedor['nombre']); ?></td>
        </tr>
        <tr>
          <td>Apellido Paterno</td>
          <td><?php echo htmlspecialchars($vendedor['apellidoP']); ?></td>
        </tr>
        <tr>
          <td>Apellido Materno</td>
          <td><?php echo htmlspecialchars($vendedor['apellidoM']); ?></td>
        </tr>
        <tr>
          <td>Correo Electrónico</td>
          <td><?php echo htmlspecialchars($vendedor['email']); ?></td>
        </tr>
        <tr>
          <td>Número de celular</td>
          <td><?php echo htmlspecialchars($vendedor['noCelular']); ?></td>
        </tr>
        <tr>
          <td>Número de referencia</td>
          <td><?php echo htmlspecialchars($vendedor['noReferencia']); ?></td>
        </tr>
      </tbody>
    </table>
  </body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
