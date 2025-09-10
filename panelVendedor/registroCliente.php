<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../src/conexion.php");

if (isset($_GET['idArticulo'])) {
    $_SESSION['idArticuloSel'] = intval($_GET['idArticulo']);
}
$idArticuloSel = $_SESSION['idArticuloSel'] ?? 0;


use Dompdf\Dompdf;

require_once '../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar-cliente'])) {
    $nombre = $_POST['name'];
    $apellidos = $_POST['apellidos'];
    $noCelular = $_POST['noCelular'];
    $email = $_POST['email'];
    $direccionFisica = $_POST['direcion-fisica'];
    $entidad = $_POST['entidad'];
    $metodoPago = $_POST['metodo-pago'];
    $comprobante = $_FILES['comprobante']['tmp_name'];

    $comprobanteBinario = !empty($comprobante) ? file_get_contents($comprobante) : null;

    // Insertar cliente
    $stmt = $conexion->prepare("INSERT INTO cliente (nombre, apellidos, noCelular, email, direccionFisica, entidad, metodoPago, comprobante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssb", $nombre, $apellidos, $noCelular, $email, $direccionFisica, $entidad, $metodoPago, $comprobanteBinario);
    $stmt->send_long_data(7, $comprobanteBinario);
    $stmt->execute();

    $idCliente = $stmt->insert_id;

    // Obtener boletos seleccionados
    $boletosSeleccionados = $_SESSION['boletosSeleccionados'] ?? [];

    // Registrar boletos del cliente
    foreach ($boletosSeleccionados as $folio) {
        $stmtBoleto = $conexion->prepare("INSERT INTO clienteboleto (idCliente, folioBoleto, idVendedor) VALUES (?, ?, ?)");
        $stmtBoleto->bind_param("isi", $idCliente, $folio, $_SESSION['idVendedor']);
        $stmtBoleto->execute();
    }

    // Obtener datos extra para el PDF
    // Obtener datos extra para el PDF
    $stmtVend = $conexion->prepare("SELECT nombre, apellidoP FROM vendedor WHERE idVendedor = ?");
    $stmtVend->bind_param("i", $_SESSION['idVendedor']);
    $stmtVend->execute();
    $resVend = $stmtVend->get_result();
    $vendedor = $resVend->fetch_assoc();

    $stmtArt = $conexion->prepare("SELECT nombreArticulo FROM articulo WHERE idArticulo = ?");
    $stmtArt->bind_param("i", $idArticuloSel);
    $stmtArt->execute();
    $resArt = $stmtArt->get_result();
    $articulo = $resArt->fetch_assoc();

    // Obtener nombre completo del vendedor
    $stmtVend = $conexion->prepare("SELECT nombre, apellidoP FROM vendedor WHERE idVendedor = ?");
    $stmtVend->bind_param("i", $_SESSION['idVendedor']);
    $stmtVend->execute();
    $resVend = $stmtVend->get_result();
    $vendedor = $resVend->fetch_assoc();

    // Obtener nombre del artículo
    $stmtArt = $conexion->prepare("SELECT nombreArticulo FROM articulo WHERE idArticulo = ?");
    $stmtArt->bind_param("i", $idArticuloSel);
    $stmtArt->execute();
    $resArt = $stmtArt->get_result();
    $articulo = $resArt->fetch_assoc();

    // Obtener boletos seleccionados
    $boletosSeleccionados = $_SESSION['boletosSeleccionados'] ?? [];
    $boletosTexto = !empty($boletosSeleccionados) ? implode(', ', $boletosSeleccionados) : 'No se seleccionaron boletos.';

    // Generar HTML del PDF
    $html = '
    <h1>Registro de Cliente</h1>
    <p><strong>Nombre del Cliente:</strong> ' . $nombre . ' ' . $apellidos . '</p>
    <p><strong>Ciudad:</strong> ' . $entidad . '</p>
    <p><strong>Domicilio:</strong> ' . $direccionFisica . '</p>
    <p><strong>Teléfono:</strong> ' . $noCelular . '</p>
    <p><strong>Nombre del Vendedor:</strong> ' . $vendedor['nombre'] . ' ' . $vendedor['apellidoP'] . '</p>
    <p><strong>Artículo:</strong> ' . $articulo['nombreArticulo'] . '</p>
    <p><strong>Método de Pago:</strong> ' . $metodoPago . '</p>
    <p><strong>Folio(s) de boletos:</strong><br>' . $boletosTexto . '</p>
    <h3>Gracias, ya estás participando</h3>';

    // Renderizar PDF con DomPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("registro_cliente.pdf", ["Attachment" => false]);
    exit;
}
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

    <form action="" class="registro-cliente" enctype="multipart/form-data" method="post" id="registro-cliente" autocomplete="off" data-aos="fade-down">
        <input type="hidden" name="boletosSeleccionados" value="<?= implode(',', $_SESSION['boletosSeleccionados'] ?? []) ?>">
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
