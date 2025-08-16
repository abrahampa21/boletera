<?php

// Conexión a la base de datos
include("../src/conexion.php");

if (isset($_POST["registrar-articulo"])) {
    $nombreArticulo = mysqli_real_escape_string($conexion, $_POST['nombre-articulo']);
    
    // Se verifica si se subió la imagen
    if (isset($_FILES['img-articulo']) && $_FILES['img-articulo']['error'] === 0) {
        $img_tmp = $_FILES['img-articulo']['tmp_name'];
        $imagenArticulo = addslashes(file_get_contents($img_tmp)); // listo para BLOB
    } else {
        echo "<script>alert('Error al subir la foto del articulo'); window.location='articulos.php';</script>";
        exit();
    }
   // Insertar nuevo articulo
    $sql_insert = "INSERT INTO articulo (nombreArticulo, imagen) VALUES ('$nombreArticulo', '$imagenArticulo')";

    if ($conexion->query($sql_insert) === TRUE) {
        echo "<script>
            alert('Articulo registrado exitosamente');
            window.location = 'articulos.php';
        </script>";
    } else{
        echo "<script>alert('Error al registrar el articulo'); window.location='articulos.php';</script>";
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
    <script
      src="https://kit.fontawesome.com/e522357059.js"
      crossorigin="anonymous"
    ></script>
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
      <button id="registrar-articulo" ondblclick="hideFormArticulo()" onclick="revealFormArticulo()">Registrar artículo</button>
     <form action="" method="POST" enctype="multipart/form-data" class="registro-articulo" id="registro-articulo">
        <h3>Ingrese los siguiente datos:</h3>
        <div class="div-nombre-articulo">
          <input
            type="text"
            name="nombre-articulo"
            id=""
            placeholder="Nombre del artículo"
          />
          <i class="fa-solid fa-pen"></i>
        </div>
        <div class="div-img-articulo">
          <label for="img-articulo">Sube foto del artículo</label>
          <input type="file" name="img-articulo" id="" />
        </div>
        <button type="submit" class="registrar-articulo" name="registrar-articulo">Enviar</button>
      </form>
    </div>

    <script src="../assets/js/login.js"></script>
  </body>
</html>
