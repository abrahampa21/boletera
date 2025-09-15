<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("src/conexion.php");

function validar_contraseña($contraseña)
{
    // Mínimo 10 caracteres
    if (strlen($contraseña) < 10) {
        return false;
    }

    // Al menos una letra
    if (!preg_match('/[A-Za-z]/', $contraseña)) {
        return false;
    }

    // Al menos un carácter especial
    if (!preg_match('/[!@#$%^&*()_\-=\[\]{};\'":\\|,.<>\/?]/', $contraseña)) {
        return false;
    }

    return true;
}

// Inicializar variables de mensajes
$mensajeusuarioexiste = "";
$mensajeadmin = "";
$mensajeine = "";
$mensajeusuario = "";
$mensajevendedor = "";
$mensajecontraseña = "";
$mensajeresultado = "";

if (isset($_POST["ingresar"])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['contraseña'];
    $token    = $_POST['token'];
    $password_encriptada = sha1($password);

    // Administrador
    $stmt_admin = $conexion->prepare("SELECT usuario FROM administrador WHERE usuario = ? AND password = ? AND token = ? LIMIT 1");
    $stmt_admin->bind_param("sss", $usuario, $password_encriptada, $token);
    $stmt_admin->execute();
    $resultado_admin = $stmt_admin->get_result();

    if ($resultado_admin && $resultado_admin->num_rows > 0) {
        $row = $resultado_admin->fetch_assoc();
        $_SESSION['usuarioAdmin'] = $row['usuario'];
        header("Location: panelAdmin.php");
        exit();
    }

    // Vendedor
    $stmt_vendedor = $conexion->prepare("SELECT idVendedor, usuario FROM vendedor WHERE usuario = ? AND password = ? LIMIT 1");
    $stmt_vendedor->bind_param("ss", $usuario, $password_encriptada);
    $stmt_vendedor->execute();
    $resultado_vendedor = $stmt_vendedor->get_result();

    if ($resultado_vendedor && $resultado_vendedor->num_rows > 0) {
        $row = $resultado_vendedor->fetch_assoc();
        $_SESSION['usuarioVendedor'] = $row['usuario'];
        $_SESSION['idVendedor'] = $row['idVendedor'];
        header("Location: panelVendedor.php");
        exit();
    } else {
        $mensajeresultado = "error";
    }
}

// Registro para administrador
if (isset($_POST["registrar-admin"])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['email'];
    $usuario = $_POST['usuario'];
    $password = $_POST['contraseña'];

    if (!validar_contraseña($password)) {
        $mensajeadmin = "contraseña-invalida";
    } else {
        $password_encriptada = sha1($password);

        $stmt_verificar = $conexion->prepare("SELECT usuario FROM administrador WHERE usuario = ? LIMIT 1");
        $stmt_verificar->bind_param("s", $usuario);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result();

        if ($resultado_verificar && $resultado_verificar->num_rows > 0) {
            $mensajeusuarioexiste = "error";
        } else {
            $stmt_insert = $conexion->prepare("INSERT INTO administrador (usuario, nombreCompleto, password, email) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $usuario, $nombre, $password_encriptada, $correo);
            if ($stmt_insert->execute()) {
                $mensajeadmin = "exito";
            } else {
                $mensajeadmin = "error";
            }
        }
    }
}

// Registro del vendedor
if (isset($_POST["registrar-vendedor"])) {
    // Recoge datos del formulario...
    $nombre = $_POST['nombre-vendedor'];
    $apellidoP = $_POST['apellidoP-vendedor'];
    $apellidoM = $_POST['apellidoM-vendedor'];
    $correo = $_POST['email-vendedor'];
    $usuario = $_POST['usuario-vendedor'];
    $password = $_POST['contraseña-vendedor'];
    $numeroCel = $_POST['noCelular-vendedor'];
    $numeroRef = $_POST['noReferencia-vendedor'];

    if (!validar_contraseña($password)) {
        $mensajevendedor = "contraseña-invalida";
    } else {
        $password_encriptada = sha1($password);

        // Procesar INE (igual que antes)
        if (isset($_FILES['ine-vendedor']) && $_FILES['ine-vendedor']['error'] === 0) {
            $ine = file_get_contents($_FILES['ine-vendedor']['tmp_name']);
        } else {
            $mensajeine = "error-foto";
        }

        // Procesar video para moverlo a la carpeta uploads/videos/
        $video_ruta = null;
        if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
            $nombreArchivo = basename($_FILES['video']['name']);
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

            // Validar extensión si quieres (mp4, avi, etc)
            $ext_permitidas = ['mp4', 'avi', 'mov', 'wmv', 'mkv'];
            if (in_array(strtolower($extension), $ext_permitidas)) {
                $nuevoNombre = uniqid('video_') . '.' . $extension;
                $rutaDestino = __DIR__ . '/uploads/videos/' . $nuevoNombre;

                if (move_uploaded_file($_FILES['video']['tmp_name'], $rutaDestino)) {
                    $video_ruta = 'uploads/videos/' . $nuevoNombre; // Ruta relativa para guardar en DB
                } else {
                    $mensajevideo = "error-subida-video";
                }
            } else {
                $mensajevideo = "extension-no-permitida";
            }
        }

        if ($mensajeine !== "error-foto" && !isset($mensajevideo)) {
            $stmt_verificar = $conexion->prepare("SELECT usuario FROM vendedor WHERE usuario = ? LIMIT 1");
            $stmt_verificar->bind_param("s", $usuario);
            $stmt_verificar->execute();
            $resultado_verificar = $stmt_verificar->get_result();

            if ($resultado_verificar && $resultado_verificar->num_rows > 0) {
                $mensajeusuario = "error";
            } else {
                // Insertar datos con ruta de video
                $stmt_insert = $conexion->prepare("INSERT INTO vendedor(usuario, nombre, apellidoP, apellidoM, email, password, fotoINE, noCelular, noReferencia, video) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt_insert->bind_param("ssssssssss", $usuario, $nombre, $apellidoP, $apellidoM, $correo, $password_encriptada, $ine, $numeroCel, $numeroRef, $video_ruta);

                if ($stmt_insert->execute()) {
                    $mensajevendedor = "exito";
                } else {
                    $mensajevendedor = "error";
                }
            }
        }
    }
}


// Recuperar contraseña
if (isset($_POST["recuperar-btn"])) {
    $email = $_POST['email-recuperar'];
    $contraseñaNueva = $_POST['contraseña-recuperar'];

    if (!validar_contraseña($contraseñaNueva)) {
        $mensajecontraseña = "contraseña-invalida";
    } else {
        $contraseñaNuevaEncriptada = sha1($contraseñaNueva);

        $stmt_admin = $conexion->prepare("SELECT * FROM administrador WHERE email = ? LIMIT 1");
        $stmt_admin->bind_param("s", $email);
        $stmt_admin->execute();
        $resultadoAdmin = $stmt_admin->get_result();

        $stmt_vendedor = $conexion->prepare("SELECT * FROM vendedor WHERE email = ? LIMIT 1");
        $stmt_vendedor->bind_param("s", $email);
        $stmt_vendedor->execute();
        $resultadoVendedor = $stmt_vendedor->get_result();

        if ($resultadoAdmin && $resultadoAdmin->num_rows > 0) {
            $stmt_update = $conexion->prepare("UPDATE administrador SET password = ? WHERE email = ?");
            $stmt_update->bind_param("ss", $contraseñaNuevaEncriptada, $email);
            $mensajecontraseña = $stmt_update->execute() ? "exito" : "error";
        } elseif ($resultadoVendedor && $resultadoVendedor->num_rows > 0) {
            $stmt_update = $conexion->prepare("UPDATE vendedor SET password = ? WHERE email = ?");
            $stmt_update->bind_param("ss", $contraseñaNuevaEncriptada, $email);
            $mensajecontraseña = $stmt_update->execute() ? "exito" : "error";
        } else {
            $mensajecontraseña = "error1";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="icon" href="src/img/logoPaginas.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Inicio de Sesión</title>
</head>

<body>

    <!--Inicio de sesión-->
    <form method="post" action="" class="inicio-sesion forms" autocomplete="on" id="inicio-sesion" data-aos="fade-down" data-aos-duration="1000">
        <div class="form-container">
            <h1>Inicio de Sesión</h1>
            <img src="src/img/logo.png" alt="Décori" title="Décori">
            <div class="usuario-campo div-login">
                <input type="text" name="usuario" class="inputs-login inputs-general" placeholder="Usuario" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="contraseña-campo div-login">
                <input type="password" name="contraseña" class="inputs-login inputs-general" id="login-pwd" placeholder="Contraseña" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <div class="codigo-seguridad div-login">
                <label>Para el administrador (NO vendedores) :</label>
                <div class="div-codigo-seguridad">
                    <input type="password" name="token" id="token" class="inputs-login inputs-general" placeholder="Código de seguridad">
                    <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
                </div>
            </div>
            <span class="forgot-pass" onclick="revealRecoverPass()">Olvidé mi contraseña</span>
            <button type="submit" name="ingresar" class="ingresar-btn">Ingresar</button>
            <div class="crear-cuenta">
                <p class="crear">¿Quieres formar parte de Décori? <span onclick="revealVendedorRegister()">Regístrate como VENDEDOR</span></p>
            </div>
        </div>
    </form>

    <!--Recuperar contraseña-->
    <form action="" method="post" autocomplete="on" class="recuperar-contraseña" id="recuperar-contraseña">
        <i id="regresar-icono" class="arrow fa-solid fa-arrow-left" title="Regresar" onclick="revealLogin()"></i>
        <h2>Recuperar contraseña</h2>
        <p>Introduce los siguientes datos</p>
        <div class="campos">
            <div class="email campos-recuperar">
                <input type="text" name="email-recuperar" placeholder="Correo electrónico" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="contraseña campos-recuperar">
                <input type="password" name="contraseña-recuperar" id="contraseña-recuperar" placeholder="Escribe la nueva contraseña" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <p id="passwordMessageRecuperar" class="passwordMessage"></p>
            <button type="submit" name="recuperar-btn">Enviar</button>
        </div>
    </form>

    <!--Registro para administrador-->
    <form class="registro-admin forms" id="registro-admin" method="post" action="" autocomplete="on">
        <i id="regresar-icono" class="arrow fa-solid fa-arrow-left" title="Regresar" onclick="revealLogin()"></i>
        <h1>Registro para administrador</h1>
        <div class="container-campos">
            <div class="div-nombre div-campos">
                <input type="text" name="nombre" class="campos-registro inputs-general" placeholder="Nombre completo" id="nombre-admin" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="div-email div-campos">
                <input type="email" name="email" class="campos-registro inputs-general" placeholder="Correo electrónico" id="email-admin" required value="">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="div-usuario div-campos">
                <input type="text" name="usuario" class="campos-registro inputs-general" placeholder="Usuario" id="usuario-admin" required value="">
                <i class="fa-solid fa-pen-nib"></i>
            </div>
            <div class="div-contraseña div-campos">
                <input type="password" name="contraseña" class="campos-registro inputs-general" placeholder="Contraseña" id="contraseña-admin" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <p id="passwordMessageAdmin" class="passwordMessage"></p>
            <button type="submit" name="registrar-admin" class="registro-admin-btn">Registrar</button>
        </div>
        <p class="login-back">¿Ya tienes una cuenta? <span onclick="revealLogin()">Ingresa aquí</span></p>
    </form>

    <!--Registro para vendedor-->
    <form class="registro-vendedor forms" id="registro-vendedor" action="" method="post" enctype="multipart/form-data" autocomplete="on">
        <i id="regresar-icono" class="arrow fa-solid fa-arrow-left" title="Regresar" onclick="revealLogin()"></i>
        <h1>Registro para vendedor</h1>
        <div class="container-campos">
            <div class="div-nombre div-campos">
                <input type="text" name="nombre-vendedor" class="campos-registro inputs-general" placeholder="Nombre completo" id="nombre-vendedor" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="div-apellidos">
                <div class="apellidoP apellido">
                    <input type="text" name="apellidoP-vendedor" class="inputs-general" id="apellidoP" Placeholder="Apellido Paterno" required>
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="apellidoM apellido">
                    <input type="text" name="apellidoM-vendedor" class="inputs-general" id="apellidoM" placeholder="Apellido Materno" required>
                    <i class="fa-solid fa-user-nurse"></i>
                </div>
            </div>
            <div class="div-email div-campos">
                <input type="email" name="email-vendedor" class="campos-registro inputs-general" placeholder="Correo electrónico" id="email-vendedor" required value="">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="div-usuario div-campos">
                <input type="text" name="usuario-vendedor" class="campos-registro inputs-general" placeholder="Usuario" id="usuario-vendedor" required value="">
                <i class="fa-solid fa-pen-nib"></i>
            </div>
            <div class="div-contraseña div-campos">
                <input type="password" name="contraseña-vendedor" class="campos-registro inputs-general" placeholder="Contraseña" id="contraseña-vendedor" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <p id="passwordMessageVendedor" class="password-message"></p>
            <div class="div-ine div-campos">
                <label for="ine">Sube foto de tu credencial de lector INE</label>
                <input type="file" class="inputs-general" name="ine-vendedor" id="ine" placeholder="Sube foto de tu INE" required>
            </div>
            <div class="div-celular div-campos">
                <input type="text" name="noCelular-vendedor" class="campos-registro inputs-general" id="noCelular-vendedor" placeholder="Número de celular">
                <i class="fa-solid fa-mobile-screen"></i>
            </div>
            <div class="div-referencia div-campos">
                <input type="text" name="noReferencia-vendedor" class="campos-registr inputs-general" id="noReferencia-vendedor" placeholder="Número de referencia">
                <i class="fa-solid fa-mobile"></i>
            </div>
            <div class="div-video div-campos">
                <label for="video">Sube un video de 30 segundos mencionando lo siguiente: </label>
                <p>
                    Yo (Tu nombre) me comprometo a manejar responsablemente <br> los datos y confianza que Décori ha depositado en mi persona
                </p>
                <input type="file" name="video" id="video">
            </div>
            <button type="submit" name="registrar-vendedor">Registrar</button>
        </div>
    </form>
    <!--Librerías JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="assets/js/login.js"></script>

    <script>
        <?php if ($mensajeusuarioexiste === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'El usuario ya existe. Intenta con otro.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajeadmin === "exito"): ?>
            Swal.fire({
                title: 'Éxito!',
                text: 'Has sido registrado exitosamente. En unos momentos se te asignará tu código de seguridad.',
                icon: 'success'
            }).then(() => {
                window.location = 'index.php';
            });
        <?php elseif ($mensajeadmin === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Error al registrar el administrador. El usuario ya existe.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajeine === "error-foto"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Error al subir el INE.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajeusuario === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'El usuario ya existe. Intenta con otro.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajevendedor === "exito"): ?>
            Swal.fire({
                title: 'Éxito!',
                text: 'Has sido registrado exitosamente. Ya puedes iniciar sesión.',
                icon: 'success'
            }).then(() => {
                window.location = 'index.php';
            });
        <?php elseif ($mensajevendedor === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Error al registrar el vendedor. El usuario ya existe.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajecontraseña === "exito"): ?>
            Swal.fire({
                title: 'Éxito!',
                text: 'La contraseña ha sido actualizada correctamente.',
                icon: 'success'
            }).then(() => {
                window.location = 'index.php';
            });
        <?php elseif ($mensajecontraseña === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Error al actualizar la contraseña.',
                icon: 'error'
            });
        <?php elseif ($mensajecontraseña === "error1"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'El correo no está registrado.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if ($mensajeresultado === "error"): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Usuario, token o contraseña incorrectos.',
                icon: 'error'
            });
        <?php endif; ?>

        <?php if (
            $mensajeadmin === "contraseña-invalida" ||
            $mensajevendedor === "contraseña-invalida" ||
            $mensajecontraseña === "contraseña-invalida"
        ): ?>
            Swal.fire({
                title: 'Contraseña inválida',
                text: 'Debe tener al menos 10 caracteres, letras y por lo menos 1 carácter especial (ej. !2%4a9328#)',
                icon: 'warning'
            });
        <?php endif; ?>
    </script>
</body>

</html>