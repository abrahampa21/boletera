const registroArticulo = document.getElementById("registro-articulo");

AOS.init();

 // FUNCIÓN PARA CONFIRMAR ELIMINACIÓN
    function confirmarEliminar(idArticulo, nombreArticulo) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el artículo "${nombreArticulo}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Si confirma, enviar el formulario de eliminación
          document.getElementById('id-articulo-eliminar').value = idArticulo;
          document.getElementById('form-eliminar').submit();
        }
      });
    }

//Revelar el formulario para meter un nuevo artículo
function revealFormArticulo() {
  registroArticulo.style.display = "flex";
}

function hideFormArticulo() {
  registroArticulo.style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  const botonesGenerar = document.querySelectorAll(".btn-generar-boletos");
  const modal = document.getElementById("modal-boletera");
  const inputIdArticulo = document.getElementById("modal-id-articulo");

  botonesGenerar.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      e.preventDefault();
      const idArticulo = boton.getAttribute("data-id");
      inputIdArticulo.value = idArticulo;
      modal.style.display = "flex";
    });
  });

  modal.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});

//Verificar que se generó por lo menos un vendedor
document
  .getElementById("generar-boletera")
  .addEventListener("submit", function (e) {
    const select = document.getElementById("select-vendedores");
    const selected = [...select.selectedOptions];
    if (selected.length === 0) {
      e.preventDefault();
      Swal.fire({
        title: "Selecciona al menos un vendedor",
        icon: "warning",
      });
    }
  });

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
