<?php
session_start();
// Conexión a la base de datos
include("../src/conexion.php");

$mensaje = ""; // bandera para mostrar el SweetAlert

if (isset($_POST["registrar-articulo"])) {
  $nombreArticulo = mysqli_real_escape_string($conexion, $_POST['nombre-articulo']);

  // Se verifica si se subió la imagen
  if (isset($_FILES['img-articulo']) && $_FILES['img-articulo']['error'] === 0) {
    $img_tmp = $_FILES['img-articulo']['tmp_name'];
    $imagenArticulo = addslashes(file_get_contents($img_tmp)); // listo para BLOB
  } else {
    $mensaje = "error-foto";
  }

  // Insertar nuevo articulo si no hubo error de foto
  if ($mensaje === "") {
    $sql_insert = "INSERT INTO articulo (nombreArticulo, imagen) VALUES ('$nombreArticulo', '$imagenArticulo')";
    if ($conexion->query($sql_insert) === TRUE) {
      $mensaje = "exito-articulo";
    } else {
      $mensaje = "error-articulo";
    }
  }
}

//Mostrar artículos
$sqlArticulos = "SELECT idArticulo, nombreArticulo, imagen FROM articulo";
$resultadoArticulos = $conexion->query($sqlArticulos);

//Eliminar artículos - LÓGICA CORREGIDA
if (isset($_POST["confirmar-eliminar"])) {
  $idArticulo = intval($_POST['id-articulo']);
  $eliminarArticulo = "DELETE FROM articulo WHERE idArticulo = '$idArticulo'";
  if ($conexion->query($eliminarArticulo) === true) {
    $mensaje = "exito-eliminar";
  } else {
    $mensaje = "error-eliminar";
  }
}

// Generar boletos y repartir entre vendedores
if (isset($_POST["generar-boletos"])) {
  $idArticulo = intval($_POST['id-articulo']);
  $cantidad = intval($_POST['boletera-cantidad']);
  $rangoInicio = intval($_POST['rango-inicio']);
  $rangoFinal = intval($_POST['rango-final']);

  // Validar rango
  if ($rangoFinal < $rangoInicio || ($rangoFinal - $rangoInicio + 1) < $cantidad) {
    $mensaje = "error";
  } else {
    // Generar folios aleatorios únicos
    $todosFolios = range($rangoInicio, $rangoFinal);
    shuffle($todosFolios);
    $folios = array_slice($todosFolios, 0, $cantidad);

    // Obtener vendedores
    $vendedores = isset($_POST['vendedores']) ? $_POST['vendedores'] : [];

    if (empty($vendedores)) {
      $mensaje = "error"; // No seleccionaron vendedores
    } else {
      $numVendedores = count($vendedores);
    }

    $numVendedores = count($vendedores);

    if ($numVendedores > 0) {
      // Calcular boletos por vendedor
      $boletosPorVendedor = round($cantidad / $numVendedores);
      $index = 0;
      foreach ($vendedores as $idVendedor) {
        for ($j = 0; $j < $boletosPorVendedor && $index < count($folios); $j++, $index++) {
          $folio = $folios[$index];
          if (!$conexion->query("INSERT INTO vendedorboleto (idVendedor, folioBoleto, idArticulo) VALUES ($idVendedor, $folio, $idArticulo)")) {
            echo "<script>alert('Error SQL vendedorboleto: " . $conexion->error . "');</script>";
          }
        }
      }
      // Si sobran boletos, asignar al primer vendedor
      while ($index < count($folios)) {
        $folio = $folios[$index];
        if (!$conexion->query("INSERT INTO vendedorboleto (idVendedor, folioBoleto, idArticulo) VALUES ({$vendedores[0]}, $folio, $idArticulo)")) {
          echo "<script>alert('Error SQL vendedorboleto: " . $conexion->error . "');</script>";
        }
        $index++;
      }
      // Insertar cantidad en articuloboleto
      if (!$conexion->query("INSERT INTO articuloboleto (idArticulo, cantidadBoletos) VALUES ($idArticulo, $cantidad) ON DUPLICATE KEY UPDATE cantidadBoletos = $cantidad")) {
        echo "<script>alert('Error SQL articuloboleto: " . $conexion->error . "');</script>";
      }
      $mensaje = "exito";
    } else {
      $mensaje = "error";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/css/panelAdmin/articulos.css" />
  <link rel="icon" href="../src/img/logoPaginas.png" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Artículos a rifar</title>
</head>

<body>
  <div class="container" data-aos="fade-down" data-aos-duration="1000">
    <div class="container-cards">
      <?php if ($resultadoArticulos && $resultadoArticulos->num_rows > 0): ?>
        <?php while ($articulo = $resultadoArticulos->fetch_assoc()): ?>

          <form method="post" class="card-articulo">
            <img src="data:image/jpeg;base64,<?= base64_encode($articulo['imagen']) ?>" alt="Artículo a rifar" />
            <h3><?= htmlspecialchars($articulo['nombreArticulo']) ?></h3>
            <input type="hidden" name="id-articulo" value="<?= $articulo['idArticulo'] ?>" />
            <div class="btns-modified">
              <button type="button" class="btn-generar-boletos" data-id="<?= $articulo['idArticulo'] ?>">Generar boletos</button>
              <!-- BOTÓN MODIFICADO: ahora usa onclick con JavaScript -->
              <button type="button" class="btn-eliminar" onclick="confirmarEliminar(<?= $articulo['idArticulo'] ?>, '<?= htmlspecialchars($articulo['nombreArticulo']) ?>')">Eliminar Artículo</button>
            </div>
          </form>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No hay artículos registrados aún.</p>
      <?php endif; ?>
    </div>

    <!--Botón para registrar artículo-->
    <button id="registrar-articulo" ondblclick="hideFormArticulo()" onclick="revealFormArticulo()">
      Registrar artículo
    </button>

    <!--Componente para registrar artículo-->
    <form action="" autocomplete="off" method="POST" enctype="multipart/form-data" class="registro-articulo" id="registro-articulo">
      <h3>Ingrese los siguiente datos:</h3>
      <div class="div-nombre-articulo">
        <input
          type="text"
          name="nombre-articulo"
          placeholder="Nombre del artículo"
          required />
        <i class="fa-solid fa-pen"></i>
      </div>
      <div class="div-img-articulo">
        <label for="img-articulo">Sube foto del artículo</label>
        <input type="file" name="img-articulo" required />
      </div>
      <button type="submit" class="registrar-articulo" name="registrar-articulo">
        Enviar
      </button>
    </form>
  </div>

  <!--Componente de generar boletera-->
  <div class="modal-boletera" id="modal-boletera">
    <form action="" method="POST" class="generar-boletera" id="generar-boletera">
      <input type="hidden" name="id-articulo" id="modal-id-articulo" value="">
      <div class="inputs">
        <label for="boletera-cantidad">¿Cuántos boletos quieres generar?</label>
        <input type="number" name="boletera-cantidad" placeholder="ej. 600" required>
      </div>
      <div class="inputs">
        <label for="rango-inicio">Escribe el valor de inicio:</label>
        <input type="number" name="rango-inicio" placeholder="ej. 1000" required>
      </div>
      <div class="inputs">
        <label for="rango-final">Escribe el valor final:</label>
        <input type="number" name="rango-final" placeholder="ej. 9999" required>
      </div>
      <div class="inputs">
        <label for="vendedores[]">Selecciona los vendedores:</label>
        <select name="vendedores[]" id="select-vendedores" class="select-vendedores" multiple required>
          <?php
          $resVendedores = $conexion->query("SELECT idVendedor, nombre, apellidoP FROM vendedor");
          while ($row = $resVendedores->fetch_assoc()):
          ?>
            <option value="<?= $row['idVendedor'] ?>"><?= htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellidoP']) ?></option>
          <?php endwhile; ?>
        </select>
        <small>Mantén presionado CTRL o CMD para seleccionar varios</small>
      </div>

      <button type="submit" name="generar-boletos">Generar</button>
    </form>
  </div>

  <!-- FORM OCULTO PARA ELIMINAR -->
  <form id="form-eliminar" method="POST" style="display: none;">
    <input type="hidden" name="id-articulo" id="id-articulo-eliminar">
    <input type="hidden" name="confirmar-eliminar" value="1">
  </form>

  <!--Botón para salir-->
  <a href="../panelAdmin.php"><i class="fa-solid fa-arrow-left"></i></a>

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <!-- Script que dispara SweetAlert después de cargar la librería -->
  <script>
   

    <?php if ($mensaje === "exito"): ?>
      Swal.fire({
        title: 'Boletos generados exitosamente!',
        text: 'Los boletos han sido asignados y registrados correctamente.',
        icon: 'success'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "exito-articulo"): ?>
      Swal.fire({
        title: 'Registro exitoso!',
        text: 'El artículo ha sido registrado correctamente.',
        icon: 'success'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "exito-eliminar"): ?>
      Swal.fire({
        title: 'Eliminado!',
        text: 'El artículo ha sido eliminado correctamente.',
        icon: 'success'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "error"): ?>
      Swal.fire({
        title: 'Error!',
        text: 'Error al generar los boletos.',
        icon: 'error'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "error-articulo"): ?>
      Swal.fire({
        title: 'Error!',
        text: 'Error al registrar el artículo.',
        icon: 'error'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "error-eliminar"): ?>
      Swal.fire({
        title: 'Error!',
        text: 'Error al eliminar el artículo.',
        icon: 'error'
      }).then(() => {
        window.location = 'articulos.php';
      });
    <?php elseif ($mensaje === "error-foto"): ?>
      Swal.fire({
        title: 'Error!',
        text: 'Error al subir la foto del artículo.',
        icon: 'error'
      });
    <?php endif; ?>
  </script>
  <script src="../assets/js/panelAdmin/articulos.js"></script>
</body>

</html>