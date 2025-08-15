const showPassword = document.querySelector(".fa-eye-slash");
const contraseñaLogin = document.getElementById("login-pwd");
const contraseñaVendedor = document.getElementById("contraseña-vendedor");
const contraseñaAdmin = document.getElementById("contraseña-admin");
const contraseñaRecuperar = document.getElementById("contraseña-recuperar");
const inicioSesion = document.getElementById("inicio-sesion");
const registroAdmin = document.getElementById("registro-admin");
const registroVendedor = document.getElementById("registro-vendedor");
const recuperarContraseña = document.getElementById("recuperar-contraseña");
const campos = document.querySelectorAll(".inputs-general");
const messageDivAdmin = document.getElementById("passwordMessageAdmin");
const messageDivVendedor = document.getElementById("passwordMessageVendedor");
const messageDivRecuperar = document.getElementById("passwordMessageRecuperar");
const registroArticulo = document.getElementById("registro-articulo");
const registrarArticuloBtn = document.getElementById("registrar-articulo");
const regex =
  /^(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>/?])(?=.*[A-Za-z]).{10,}$/;


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

function revealVendedorRegister() {
  inicioSesion.style.display = "none";
  registroVendedor.style.display = "flex";

  campos.forEach((input) => {
    input.value = "";
  });
}

function revealLogin() {
  registroAdmin.style.display = "none";
  recuperarContraseña.style.display = "none";
  registroVendedor.style.display = "none";
  inicioSesion.style.display = "block";
  campos.forEach((input) => {
    input.value = "";
  });
}

function revealRecoverPass() {
  recuperarContraseña.style.display = "flex";
  inicioSesion.style.display = "none";
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
    } else {
      messageDivAdmin.textContent =
        "Debe tener al menos 10 caracteres, letras y caractéres especiales";
      messageDivAdmin.style.color = "red";
    }
  });
}

if (contraseñaVendedor) {
  contraseñaVendedor.addEventListener("input", function () {
    const value = contraseñaVendedor.value;

    if (regex.test(value)) {
      messageDivVendedor.textContent = "Contraseña válida";
      messageDivVendedor.style.color = "green";
    } else {
      messageDivVendedor.textContent =
        "Debe tener al menos 10 caracteres, letras y caractéres especiales";
      messageDivVendedor.style.color = "red";
    }
  });
}

if (contraseñaRecuperar) {
  contraseñaRecuperar.addEventListener("input", function () {
    const value = contraseñaRecuperar.value;

    if (regex.test(value)) {
      messageDivRecuperar.textContent = "Contraseña válida";
      messageDivRecuperar.style.color = "green";
    } else {
      messageDivRecuperar.textContent =
        "Debe tener al menos 10 caracteres, letras y caractéres especiales";
      messageDivRecuperar.style.color = "red";
    }
  });
}

//Revelar el formulario para meter un nuevo artículo
function revealFormArticulo(){
  registroArticulo.style.display = "flex";
}

function hideFormArticulo(){
  registroArticulo.style.display = "none";
}

AOS.init();
