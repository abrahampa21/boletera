<?php
session_start();
include("src/conexion.php");

if (isset($_POST["ingresar"])) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['contraseña']);
    $token    = mysqli_real_escape_string($conexion, $_POST['token']);
    $password_encriptada = sha1($password);
    // Ahora verificamos usuario, password y token
    $sql_admin = "SELECT usuario FROM administrador 
                  WHERE usuario = '$usuario' 
                  AND password = '$password_encriptada' 
                  AND token = '$token' 
                  LIMIT 1";

    $resultado_admin = $conexion->query($sql_admin);

    if ($resultado_admin && $resultado_admin->num_rows > 0) {
        $row = $resultado_admin->fetch_assoc();
        $_SESSION['usuarioAdmin'] = $row['usuario'];
        header("Location: panelAdmin.php");
        exit();
    } // Si no es administrador, verificar si es vendedor
    $sql_vendedor = "SELECT usuario FROM vendedor 
                 WHERE usuario = '$usuario' 
                 AND password = '$password_encriptada' 
                 LIMIT 1";

    $resultado_vendedor = $conexion->query($sql_vendedor);

    if ($resultado_vendedor && $resultado_vendedor->num_rows > 0) {
        $row = $resultado_vendedor->fetch_assoc();
        $_SESSION['usuarioVendedor'] = $row['usuario'];
        header("Location: panelVendedor.php");
        exit();
    } else {
        echo "<script>
        alert('Usuario, contraseña o token incorrectos');
        window.location = 'index.php';
    </script>";
    }
}

// Registro para administrador
if (isset($_POST["registrar-admin"])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['email']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['contraseña']);
    $password_encriptada = sha1($password);
    // Verificar si el usuario ya existe
    $verificar_usuario = "SELECT usuario FROM administrador WHERE usuario = '$usuario' LIMIT 1";
    $resultado_verificar = $conexion->query($verificar_usuario);

    if ($resultado_verificar && $resultado_verificar->num_rows > 0) {
        echo "<script>
            alert('El usuario ya existe. Intenta con otro.');
            window.location = 'index.php';
        </script>";
        exit();
    }

    // Insertar nuevo administrador
    $sql_insert = "INSERT INTO administrador (usuario, nombreCompleto, password, email) 
                   VALUES ('$usuario', '$nombre', '$password_encriptada', '$correo')";

    if ($conexion->query($sql_insert) === TRUE) {
        echo "<script>
            alert('Registro exitoso. Ya puedes iniciar sesión.');
            window.location = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar: " . $conexion->error . "');
            window.location = 'index.php';
        </script>";
    }
}

//Registro del vendedor
if (isset($_POST["registrar-vendedor"])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre-vendedor']);
    $apellidoP = mysqli_real_escape_string($conexion, $_POST['apellidoP-vendedor']);
    $apellidoM = mysqli_real_escape_string($conexion, $_POST['apellidoM-vendedor']);
    $correo = mysqli_real_escape_string($conexion, $_POST['email-vendedor']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario-vendedor']);
    $password = mysqli_real_escape_string($conexion, $_POST['contraseña-vendedor']);
    $numeroCel = mysqli_real_escape_string($conexion, $_POST['noCelular-vendedor']);
    $numeroRef = mysqli_real_escape_string($conexion, $_POST['noReferencia-vendedor']);
    $password_encriptada = sha1($password);
    // Se verifica si se subió la imagen
    if (isset($_FILES['ine-vendedor']) && $_FILES['ine-vendedor']['error'] === 0) {
        $ine_tmp = $_FILES['ine-vendedor']['tmp_name'];
        $ine = addslashes(file_get_contents($ine_tmp)); // listo para BLOB
    } else {
        echo "<script>alert('Error al subir el INE'); window.location='login.php';</script>";
        exit();
    }
    // Verificar si el usuario ya existe
    $verificar_usuario = "SELECT usuario FROM vendedor WHERE usuario = '$usuario' LIMIT 1";
    $resultado_verificar = $conexion->query($verificar_usuario);

    if ($resultado_verificar && $resultado_verificar->num_rows > 0) {
        echo "<script>
            alert('El usuario ya existe. Intenta con otro.');
            window.location = 'index.php';
        </script>";
        exit();
    }

    // Insertar nuevo vendedor
    $sql_insert = "INSERT INTO vendedor (usuario, nombre, apellidoP, apellidoM, email, password, 
    fotoINE, noCelular, noReferencia) 
                   VALUES ('$usuario', '$nombre','$apellidoP','$apellidoM', '$correo' 
                   , '$password_encriptada','$ine','$numeroCel', '$numeroRef')";

    if ($conexion->query($sql_insert) === TRUE) {
        echo "<script>
            alert('Registro exitoso. Ya puedes iniciar sesión.');
            window.location = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar: " . $conexion->error . "');
            window.location = 'login.php';
        </script>";
    }
}

//Recuperar contraseña
if (isset($_POST["recuperar-btn"])) {
    $email = mysqli_real_escape_string($conexion, $_POST['email-recuperar']);
    $contraseñaNueva = mysqli_real_escape_string($conexion, $_POST['contraseña-recuperar']);
    $contraseñaNuevaEncriptada = sha1($contraseñaNueva);

    $esAdministrador = "SELECT * FROM administrador WHERE email = '$email' LIMIT 1";
    $esVendedor = "SELECT * FROM vendedor WHERE email = '$email' LIMIT 1";
    $resultadoAdmin = $conexion->query($esAdministrador);
    $resultadoVendedor = $conexion->query($esVendedor);

    //Para actualizar administrador
    if ($resultadoAdmin && $resultadoAdmin->num_rows > 0) {
        $actualizarAdmin = "UPDATE administrador SET password = '$contraseñaNuevaEncriptada' WHERE email = '$email'";
        if ($conexion->query($actualizarAdmin) === true) {
            echo "<script>alert('Contraseña actualizada correctamente')</script>";
        } else {
            echo "<script>alert('Error al actualizar la contraseña" . $conexion->error . "')</script>";
        }
    } else {
        if ($resultadoVendedor && $resultadoVendedor->num_rows > 0) {
            $actualizarVendedor = "UPDATE vendedor SET password = '$contraseñaNuevaEncriptada' WHERE email = '$email'";
            if ($conexion->query($actualizarVendedor) === true) {
                echo "<script>alert('Contraseña actualizada correctamente')</script>";
            } else {
                echo "<script>alert('Error al actualizar la contraseña" . $conexion->error . "')</script>";
            }
        } else {
            echo "<script>alert('Correo no registrado'); window.location = 'index.php';</script>";
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
    <script src="https://kit.fontawesome.com/e522357059.js" crossorigin="anonymous"></script>
    <title>Inicio de Sesión</title>
</head>

<body>

    <!--Inicio de sesión-->
    <form method="post" action="" class="inicio-sesion forms" autocomplete="off" id="inicio-sesion" data-aos="fade-down" data-aos-duration="1500">
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
                <label>Para el administrador:</label>
                <div class="div-codigo-seguridad">
                    <input type="password" name="token" id="token" class="inputs-login inputs-general" placeholder="Código de seguridad">
                    <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
                </div>
            </div>
            <span class="forgot-pass" onclick="revealRecoverPass()">Olvidé mi contraseña</span>
            <button type="submit" name="ingresar" class="ingresar-btn">Ingresar</button>
            <div class="crear-cuenta">
                <p class="crear">¿Todavía no tienes una cuenta? Regístrate como <span onclick="revealVendedorRegister()">Vendedor</span> o <span onclick="revealAdminRegister()">Administrador</span></p>
            </div>
        </div>
    </form>

    <!--Recuperar contraseña-->
    <form action="" method="post" class="recuperar-contraseña" id="recuperar-contraseña">
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
    <form class="registro-admin forms" id="registro-admin" method="post" action="" autocomplete="off">
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
    <form class="registro-vendedor forms" id="registro-vendedor" action="" method="post" enctype="multipart/form-data" autocomplete="off">
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
            <button type="submit" name="registrar-vendedor">Registrar</button>
        </div>
    </form>
    <!--Librerías JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="assets/js/login.js"></script>
</body>

</html>