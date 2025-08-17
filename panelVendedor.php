<?php
session_start();
include("src/conexion.php");

if (!isset($_SESSION['usuarioVendedor'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuarioVendedor'];
$sql = "SELECT nombre, apellidoP FROM vendedor WHERE usuario = '$usuario'";
$resultado = $conexion->query($sql);

$nombre = "Vendedor";

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre = $fila['nombre'];
    $apellido = $fila['apellidoP'];
}

// Obtener nombres de los artículos
$sqlArticulos = "SELECT nombreArticulo FROM articulo";
$resultadoArticulos = $conexion->query($sqlArticulos);

?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/paneles.css">
    <link rel="icon" href="src/img/logoPaginas.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Panel del Vendedor</title>
</head>
<body>
    <header>
        <nav>
            <div class="img-company">
                <img src="src/img/logoPaginas.png" alt="Décori" title="Décori">
                <h1>Amigos Décori</h1>
            </div>
            <div class="links-vendedor">
                <i class="fa-solid fa-truck"></i>
                <a href="panelVendedor/articulosRifar.php">Artículos a rifar</a>
            </div>
            <div class="user">
                <i class="fa-solid fa-user" ></i>
                <h4><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h4>
            </div>
        </nav>
    </header>

    <a href="src/logout.php" class="exit" title="Salir"><i class="fa-solid fa-right-from-bracket"></i></a>

    <script src="assets/js/paneles.js"></script>
</body>
</html>
