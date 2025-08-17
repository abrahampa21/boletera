  function agrandarImagen(src) {
    var modal = document.getElementById("modal-img");
    var modalImg = document.getElementById("imgModal");
    modal.style.display = "block";
    modalImg.src = src;
  }

  function cerrarModal() {
    document.getElementById("modal-img").style.display = "none";
  }

AOS.init();