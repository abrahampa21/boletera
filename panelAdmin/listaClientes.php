<?php
session_start();
include("../src/conexion.php");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
// Consulta: obtener nombre del cliente + sus boletos
$sqlClienteBoletos = "
  SELECT cliente.nombre, cliente.apellidos, GROUP_CONCAT(clienteboleto.folioBoleto SEPARATOR ', ') AS Boletos 
  FROM clienteboleto 
  INNER JOIN cliente ON clienteboleto.idCliente = cliente.idCliente 
  GROUP BY cliente.nombre, cliente.apellidos
";

$resultadoClienteBoletos = $conexion->query($sqlClienteBoletos);

if ($resultadoClienteBoletos->num_rows === 0) {
    die("Cliente no encontrado.");
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
            <caption>Clientes registrados</caption>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Boletos</th>
                </tr>
            </thead>
            <tbody id="lista-clientes">
                <?php while ($row = $resultadoClienteBoletos->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre'] . " " . $row['apellidos'])?></td>
                        <td><?= htmlspecialchars($row['Boletos']) ?></td>
                    </tr>
                <?php endwhile; ?>

                <!--Para tabletas y celulares -->
                <div class="cards-container">
                    <?php
                    if ($resultadoClienteBoletos->num_rows > 0) {
                        $resultadoClienteBoletos->data_seek(0);
                        while ($fila = $resultadoClienteBoletos->fetch_assoc()) {
                            echo "<div class='card'>";
                            echo "<h3>" . htmlspecialchars($fila['nombre']) . " " . htmlspecialchars($fila['apellidoP']) . "</h3>";
                            echo "<h3>" .htmlspecialchars($fila['Boletos']) . " " . "</h3>";
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
    <script src="../assets/js/panelAdmin/listaClientes.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>
