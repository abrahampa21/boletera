<?php
// Conexión a la base de datos
include("../src/conexion.php");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta 
$sql = "SELECT nombre, apellidoP FROM vendedor";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/panelAdmin/vendedores.css">
    <link rel="icon" href="../src/img/logoPaginas.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Vendedores</title>
</head>
<body>
    <div class="contenedor">
        <table class="tabla-vendedores" id="tabla-vendedores">
            <caption>Vendedores</caption>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Boletera</th>
                    <th>Clientes</th>
                    <th>Datos Personales</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . " " . htmlspecialchars($fila['apellidoP']) . "</td>";
                        echo "<td><a href='#'>Ver</a></td>";
                        echo "<td><a href='#'>Ver</a></td>";
                        echo "<td><a href='#'>Ver</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay vendedores registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conexion->close();
?>
