const showPassword = document.querySelector(".fa-eye-slash");
const contraseñaLogin = document.getElementById("login-pwd");
const contraseñaVendedor = document.getElementById("contraseña-vendedor");
const contraseñaAdmin = document.getElementById("contraseña-admin");
const inicioSesion = document.getElementById("inicio-sesion");
const registroAdmin = document.getElementById("registro-admin");
const registroVendedor = document.getElementById("registro-vendedor");
const campos = document.querySelectorAll(".inputs-general");
const messageDivAdmin = document.getElementById("passwordMessageAdmin");
const messageDivVendedor = document.getElementById("passwordMessageVendedor");
const regex =
  /^(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>/?])(?=.*[A-Za-z]).{10,}$/;

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

if (contraseñaAdmin) {
  contraseñaAdmin.addEventListener("input", function () {
    const value = contraseñaAdmin.value;

    if (regex.test(value)) {
      messageDivAdmin.textContent = "Contraseña válida";
      messageDivAdmin.style.color = "green";
      messageDivAdmin.style.fontSize = "12px";
    } else {
      messageDivAdmin.textContent =
        "Debe tener al menos 10 caracteres, letras y caractéres especiales";
      messageDivAdmin.style.color = "red";
      messageDivAdmin.style.fontSize = "12px";
      messageDivAdmin.style.width = "310px";
    }
  });
}

if (contraseñaVendedor) {
  contraseñaVendedor.addEventListener("input", function () {
    const value = contraseñaVendedor.value;

    if (regex.test(value)) {
      messageDivVendedor.textContent = "Contraseña válida";
      messageDivVendedor.style.color = "green";
      messageDivVendedor.style.fontSize = "12px";
    } else {
      messageDivVendedor.textContent =
        "Debe tener al menos 10 caracteres, letras y caractéres especiales";
      messageDivVendedor.style.color = "red";
      messageDivVendedor.style.fontSize = "12px";
      messageDivVendedor.style.width = "310px";
    }
  });
}
