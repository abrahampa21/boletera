const registroArticulo = document.getElementById("registro-articulo");

AOS.init();

function eliminarArticulo(e){
    const confirmacion = confirm(
      "¿Estás seguro que deseas eliminar el artículo?"
    );
    if (!confirmacion) {
      e.preventDefault();
    }
}

//Revelar el formulario para meter un nuevo artículo
function revealFormArticulo() {
  registroArticulo.style.display = "flex";
}

function hideFormArticulo() {
  registroArticulo.style.display = "none";
}

// <?php if ($mensaje === "exito1"): ?>
//   Swal.fire({
//     title: 'El artículo ha sido eliminado correctamente.',
//     icon: 'success'
//   }).then(() => {
//     window.location = 'articulos.php';
//   });
// <?php elseif ($mensaje === "error1"): ?>
//   Swal.fire({
//     title: 'Error!',
//     text: 'Error al eliminar el artículo.',
//     icon: 'error'
//   }).then(() => {
//     window.location = 'articulos.php';
//   });
// <?php endif; ?>
