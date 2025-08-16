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
    <div class="container">
      <template>
        <div class="card-articulo">
          <img src="" alt="articulo a rifar" />
          <button>Generar boletos</button>
        </div>
      </template>

      <button id="registrar-articulo" ondblclick="hideFormArticulo()" onclick="revealFormArticulo()">
        Registrar artículo
      </button>

      <form action="" autocomplete="off" method="POST" enctype="multipart/form-data" class="registro-articulo" id="registro-articulo">
        <h3>Ingrese los siguiente datos:</h3>
        <div class="div-nombre-articulo">
          <input
            type="text"
            name="nombre-articulo"
            placeholder="Nombre del artículo"
            required
          />
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

    <!-- Script que dispara SweetAlert después de cargar la librería -->
    <script>
      <?php if ($mensaje === "exito"): ?>
        Swal.fire({
          title: 'Registro exitoso!',
          text: 'El artículo ha sido registrado correctamente.',
          icon: 'success'
        }).then(() => {
          window.location = 'articulos.php';
        });
      <?php elseif ($mensaje === "error"): ?>
        Swal.fire({
          title: 'Error!',
          text: 'Error al registrar el artículo.',
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

    <script src="../assets/js/login.js"></script>
  </body>
</html>
