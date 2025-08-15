<?php
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
        header("Location: panelAdmin.html");
        exit();
    } else {
        echo "<script>
            alert('Usuario, contraseña o token incorrectos');
            window.location = 'index.php';
        </script>";
    }
}

// Registro para administrador
if (isset($_POST["registrar"])) {
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
                <input type="text" name="usuario" class="inputs-login inputs-general" placeholder="Usuario" maxlength="7" required>
                <i class="fa-solid fa-user" ></i>
            </div>
            <div class="contraseña-campo div-login">
                <input type="password" name="contraseña" class="inputs-login inputs-general" id="login-pwd" placeholder="Contraseña" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <div class="codigo-seguridad div-login">
                <label>Para el administrador:</label>
                <input type="text" name="token" id="token" class="inputs-login inputs-general" placeholder="Código de seguridad">
            </div>
            <span class="forgot-pass" onclick="revealRecoverPass()">Olvidé mi contraseña</span>
            <button type="submit" name="ingresar" class="ingresar-btn">Ingresar</button>
            <div class="crear-cuenta">
                <p class="crear">¿Todavía no tienes una cuenta? <span onclick="revealAdminRegister()">Regístrate aquí</span></p>
            </div>
        </div>
    </form>

    <!--Recuperar contraseña-->
    <form action="" class="recuperar-contraseña" id="recuperar-contraseña">
        <i id="regresar-icono" class="arrow fa-solid fa-arrow-left" title="Regresar" onclick="revealLogin()"></i>
        <h2>Recuperar contraseña</h2>
        <p>Introduce los siguientes datos</p>
        <div class="campos">
            <div class="email campos-recuperar">
                <input type="text" name="email-recuperar" placeholder="Correo electrónico" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="contraseña campos-recuperar">
                <input type="password" name="" id="contraseña-recuperar" placeholder="Escribe la nueva contraseña" required>
                <i class="fa-regular fa-eye-slash" onclick="revealPassword(this)"></i>
            </div>
            <p id="passwordMessageRecuperar" class="passwordMessage"></p>
            <button type="submit">Enviar</button>
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
            <button type="submit" name="registrar" class="registro-admin-btn">Registrar</button>
        </div>
        <p class="login-back">¿Ya tienes una cuenta? <span onclick="revealLogin()" >Ingresa aquí</span></p>
    </form>

    <!--Librerías JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="assets/js/login.js"></script>
</body>
</html>
