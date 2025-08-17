<?php
include("../src/conexion.php");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta 
$sqlArticulos = "SELECT idArticulo, nombreArticulo, imagen FROM articulo";
$resultadoArticulos = $conexion->query($sqlArticulos);

if (!$resultadoArticulos) {
  die("Error en la consulta: " . $conexion->error);
}

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
  <title>Artículos</title>
</head>

<body>

  <div class="contenedor" data-aos="fade-down">
    <table class="tabla-articulos">
      <thead>
        <tr>
          <th>Artículo</th>
          <th>Imagen</th>
          <th>Boletera</th>
          <th>Clientes</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($articulo = $resultadoArticulos->fetch_assoc()) : ?>
          <tr>
            <td><?php echo htmlspecialchars($articulo['nombreArticulo']); ?></td>
            <td>
              <img src="data:image/jpeg;base64,<?php echo base64_encode($articulo['imagen']); ?>" alt="foto-artículo" onclick="agrandarImagen(this.src)">
            </td>
            <td><a href="#">Ver</a></td>
            <td><a href="#">Ver</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal para ver la imagen más grande-->
  <div id="modal-img" class="modal-img" onclick="cerrarModal()">
    <span class="cerrar">&times;</span>
    <img class="modal-contenido" id="imgModal">
  </div>


  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="../assets/js/panelVendedor/articulosRifar.js"></script>
</body>

</html>