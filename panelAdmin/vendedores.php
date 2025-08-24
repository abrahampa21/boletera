<?php
// Conexión a la base de datos
include("../src/conexion.php");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

//Mostrar vendedores
$sql = "SELECT idVendedor, nombre, apellidoP FROM vendedor";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

//Eliminar a un vendedor
if (isset($_POST["vendedor-id"]) && isset($_POST["borrar-vendedor"])) {
    $id_vendedor = $_POST["vendedor-id"];

    $statement = $conexion->prepare("DELETE FROM vendedor WHERE idVendedor = ?");
    $statement->bind_param("i", $id_vendedor);

    if ($statement->execute()) {
        echo "<script>
        alert('Vendedor eliminado correctamente');
        window.location.href = 'vendedores.php';
        </script>";
    } else {
        echo "<script>
        alert('No se pudo eliminar al vendedor');
        window.location.href = 'vendedores.php';
        </script>";
    }
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
    <div class="contenedor" data-aos="fade-down">
        <table class="tabla-clientes-vendedores" id="tabla-clientes-vendedores">
            <caption>Vendedores</caption>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Boletera y Clientes</th>
                    <th>Datos Personales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . " " . htmlspecialchars($fila['apellidoP']) . "</td>";
                        echo "<td><a href='boleteraVendedor.php?idVendedor=" . urlencode($fila['idVendedor']) . "'>Ver</a></td>";
                        echo "<td><a href='datosVendedor.php?id=" . urlencode($fila['idVendedor']) . "'>Ver</a></td>";
                        echo "<td>
                        <form method='post' onsubmit=\"return confirm('¿Está seguro de eliminar al vendedor?');\">
                            <input type='hidden' name='vendedor-id' value='" . intval($fila["idVendedor"]) . "'>
                            <button type='submit' class='borrar-vendedor' name='borrar-vendedor'>Dar de baja</button>
                        </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay vendedores registrados</td></tr>";
                }
                ?>

                <!--Para tabletas y celulares -->
                <div class="cards-container">
                    <?php
                    if ($resultado->num_rows > 0) {
                        $resultado->data_seek(0);
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<div class='card'>";
                            echo "<h3>" . htmlspecialchars($fila['nombre']) . " " . htmlspecialchars($fila['apellidoP']) . "</h3>";
                            echo "<a href='boleteraVendedor.php?idVendedor=" . urlencode($fila['idVendedor']) . "'>Boletera y Clientes</a>";
                            echo "<br>";
                            echo "<a href='datosVendedor.php?id=" . urlencode($fila['idVendedor']) . "'>Datos Personales</a>";
                            echo "<form method='post' onsubmit=\"return confirm('¿Está seguro de eliminar al vendedor?');\">
                                <input type='hidden' name='vendedor-id' value='" . intval($fila["idVendedor"]) . "'>
                                <button type='submit' class='borrar-vendedor-responsive' name='borrar-vendedor'>Dar de baja</button>
                            </form>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No hay vendedores registrados</p>";
                    }
                    ?>
                </div>

            </tbody>
        </table>
    </div>

    <a href="../panelAdmin.php"><i class="fa-solid fa-arrow-left"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>
<?php
$conexion->close();
?>