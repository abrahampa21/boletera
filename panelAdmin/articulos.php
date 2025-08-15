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
      <form action="" class="registro-articulo" id="registro-articulo">
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
        <button type="submit" class="registrar-articulo">Enviar</button>
      </form>
    </div>

    <script src="../assets/js/login.js"></script>
  </body>
</html>
