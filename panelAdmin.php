<?php
session_start();
include("src/conexion.php");

if (!isset($_SESSION['usuarioAdmin'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuarioAdmin'];
$sql = "SELECT nombreCompleto FROM administrador WHERE usuario = '$usuario' LIMIT 1";
$resultado = $conexion->query($sql);

$nombre = "Administrador";

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre = explode(' ', trim($fila['nombreCompleto']))[0];
}
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
    <title>Panel del administrador</title>
</head>
<body>
    <header>
        <nav>
            <div class="img-company">
                <img src="src/img/logoPaginas.png" alt="Décori" title="Décori">
            </div>
            <div class="links">
                <div class="apartados">
                    <i class="fa-solid fa-user-tie"></i>
                    <a href="panelAdmin/vendedores.php">Vendedores</a></li>
                </div>
                <div class="apartados">
                    <i class="fa-solid fa-user-check"></i>
                    <a href="panelAdmin/listaClientes.php">Lista de clientes</a></li>
                </div>
                <div class="apartados">
                    <i class="fa-solid fa-ticket"></i>
                    <a href="panelAdmin/articulos.php">Artículos</a></li>
                </div>
            </div>
            <div class="toggle" id="toggle">
                <i class="fa-solid fa-bars" id="bars"></i>
            </div>
            <div class="menu" id="menu">
                <div class="apartados">
                    <i class="fa-solid fa-user-tie"></i>
                    <a href="panelAdmin/vendedores.php">Vendedores</a></li>
                </div>
                <div class="apartados">
                    <i class="fa-solid fa-user-check"></i>
                    <a href="panelAdmin/listaClientes.php">Lista de clientes</a></li>
                </div>
                <div class="apartados">
                    <i class="fa-solid fa-ticket"></i>
                    <a href="panelAdmin/articulos.php">Artículos</a></li>
                </div>
            </div>
            <div class="user">
                <i class="fa-solid fa-user" ></i>
                <h4><?php echo htmlspecialchars($nombre); ?></h4>
            </div>
        </nav>
    </header>

    <main class="main-admin"></main>

    <a href="src/logout.php" onclick="return confirm('¿Estás seguro que quieres salir?');" class="exit" title="Salir"><i class="fa-solid fa-right-from-bracket"></i></a>

    <script src="assets/js/paneles.js"></script>
</body>
</html>
