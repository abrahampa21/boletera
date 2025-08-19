<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../src/conexion.php");


if (isset($_GET['idArticulo'])) {
    $_SESSION['idArticuloSel'] = intval($_GET['idArticulo']);
}
$idArticuloSel = $_SESSION['idArticuloSel'] ?? 0;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="icon" href="../src/img/logoPaginas.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Registro del cliente</title>
</head>

<body>

    <form action="" class="registro-cliente" method="post" id="registro-cliente" autocomplete="off" data-aos="fade-down">
        <h1>Registro del cliente</h1>
        <div class="campos-cliente">
            <div class="div-nombre div-campos">
                <input type="text" name="name" class="campos-registro inputs-general" id="name" placeholder="Nombre completo" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="div-apellido div-campos">
                <input type="text" name="apellidos" class="campos-registro inputs-general" id="apellidos" placeholder="Apellidos" required>
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="div-email div-campos">
                <input type="email" name="email" class="campos-registro inputs-general" id="email" placeholder="Correo electrónico">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="div-direccion div-campos">
                <input type="text" name="direcion-fisica" class="campos-registro inputs-general" id="direccion" placeholder="Domicilio" required>
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="div-celular div-campos">
                <input type="tel" name="noCelular" class="campos-registro inputs-general" id="noCelular" placeholder="Número de celular" required>
                <i class="fa-solid fa-phone"></i>
            </div>

            <div class="div-entidad div-campos">
                <input type="text" name="entidad" class="campos-registro inputs-general" id="entidad" placeholder="Ciudad o entidad" required>
                <i class="fa-solid fa-city"></i>
            </div>
            <div class="div-metodoPago div-campos">
                <label for="metodo-pago">Método de Pago</label>
                <select name="metodo-pago" id="metodo-pago" title="metodo" class="metodo-pago">
                    <option value="Transferencia">Transferencia</option>
                    <option value="Depósito">Depósito</option>
                    <option value="Efectivo">Efectivo</option>
                </select>
            </div>
            <div class="div-comprobante div-campos">
                <label for="comprobante">Comprobante de pago</label>
                <input type="file" name="comprobante" class="comprobante" placeholder="Comprobante de pago" id="">
            </div>
            <button title="Enviar" type="submit" name="registrar-cliente">Registrar</button>
        </div>

    </form>

    <a href="boletera.php?idArticulo=<?= $idArticuloSel ?>" class="regresar-btn-cliente">
        <i id="regresar-icono" class="arrow fa-solid fa-arrow-left" title="Regresar"></i>
    </a>

    <!--Librerías JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>