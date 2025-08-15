const toggle = document.getElementById("toggle");
const menu = document.getElementById("menu");
const bar = document.getElementById("bars");

//Mostrar el menú con la responsividad
toggle.addEventListener("click", () => {
  menu.classList.toggle("menu-open");
  const barOpen = menu.classList.contains("menu-open");

  bar.classList = barOpen ? "fa-solid fa-xmark" : "fa-solid fa-bars";
});

// Cerrar el menú si se hace clic fuera del toggle y del menú
document.addEventListener("click", (event) => {
  const clickedOutsideMenu = !menu.contains(event.target);
  const clickedOutsideToggle = !toggle.contains(event.target);

  if (clickedOutsideMenu && clickedOutsideToggle) {
    if (menu.classList.contains("menu-open")) {
      menu.classList.remove("menu-open");
      bar.className = "fa-solid fa-bars";
    }
  }
});