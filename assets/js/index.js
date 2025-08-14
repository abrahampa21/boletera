const showPassword = document.querySelector(".fa-eye-slash");
const contraseñaLogin = document.getElementById("login-pwd");
const contraseñaVendedor = document.getElementById("contraseña-vendedor");
const contraseñaAdmin = document.getElementById("contraseña-admin");
const inicioSesion = document.getElementById("inicio-sesion");
const registroAdmin = document.getElementById("registro-admin");
const registroVendedor = document.getElementById("registro-vendedor");
const campos = document.querySelectorAll(".inputs-general");

AOS.init();

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

//Revelar contraseñas
function revealPassword(icono) {
  const contenedor = icono.parentElement;
  const input = contenedor.querySelector("input");

  const esOculta = input.type === "password";
  input.type = esOculta ? "text" : "password";

  icono.classList.toggle("fa-eye");
  icono.classList.toggle("fa-eye-slash");

  if (esOculta) {
    bloquearCopiadoContraseñas(input);
  }
}

//Funciones para mostrar los componentes
function revealAdminRegister() {
  inicioSesion.style.display = "none";
  registroAdmin.style.display = "flex";

  campos.forEach((input) => {
    input.value = "";
  });
}

function revealLogin() {
  registroAdmin.style.display = "none";
  registroVendedor.style.display = "none";
  inicioSesion.style.display = "block";
  campos.forEach((input) => {
    input.value = "";
  });
}

function revealVendedorRegister() {
  inicioSesion.style.display = "none";
  registroVendedor.style.display = "block";

  campos.forEach((input) => {
    input.value = "";
  });
}

// //Cambio de fondo cada cierto tiempo
// const backgroundImages = [
//     "src/img/fondo.webp",
//     "src/img/fondo2.webp"
// ];

// let cont = 0;

// function changeBackground() {
//   document.body.style.backgroundImage = `url("${backgroundImages[cont]}")`;
//   cont = (cont + 1) % backgroundImages.length;
// }

// setInterval(changeBackground, 3000);

// window.onload = changeBackground;
