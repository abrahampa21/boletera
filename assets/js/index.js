// //Cambio de fondo cada cierto tiempo
// const backgroundImages = [
//     "src/img/deco1.jpg",
//     "src/img/deco2.jpg"
// ];

// let cont = 0;

// function changeBackground() {
//   document.body.style.backgroundImage = `url("${backgroundImages[cont]}")`;
//   cont = (cont + 1) % backgroundImages.length;
// }

// setInterval(changeBackground, 3000);

// window.onload = changeBackground;

const showPassword = document.querySelector(".fa-eye-slash");
const contraseñaLogin = document.getElementById("login-pwd");
const inicioSesion = document.getElementById("inicio-sesion");
const registroAdmin = document.getElementById("registro-admin");
const registroVendedor = document.getElementById("registro-vendedor");

//No dejar copiar los contenidos de las contraseñas
function bloquearCopiadoContraseñas(contraseñaLogin) {
  contraseñaLogin.addEventListener("copy", (e) => e.preventDefault());
  contraseñaLogin.addEventListener("contextmenu", (e) => e.preventDefault());
  contraseñaLogin.addEventListener("keydown", (e) => {
    if (
      (e.ctrlKey || e.metaKey) &&
      ["c", "x", "a"].includes(e.key.toLowerCase())
    ) {
      e.preventDefault();
    }
  });
}

function revealPassword(){
    const campoContraseña = contraseñaLogin.type == "password";
    contraseñaLogin.type = campoContraseña ? "text": "password";
    showPassword.classList.toggle("fa-eye");
    showPassword.classList.toggle("fa-eye-slah");

    if(campoContraseña){
        bloquearCopiadoContraseñas(contraseñaLogin);
    }
}

function revealAdminRegister(){
    inicioSesion.style.display = "none";
    registroAdmin.style.display = "block";
}

function revealLogin(){
    registroAdmin.style.display = "none";
    registroVendedor.style.display = "none";
    inicioSesion.style.display = "block";
}
