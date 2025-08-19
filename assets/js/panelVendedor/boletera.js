document.addEventListener('DOMContentLoaded', () => {
  const boletos = document.querySelectorAll('.boleto-card');
  const registrarBtn = document.getElementById('registrar-cliente');

  let boletosSeleccionados = [];

  boletos.forEach(boleto => {
    boleto.addEventListener('click', () => {
      const folio = boleto.dataset.folio;
      boleto.classList.toggle('seleccionado');

      if (boleto.classList.contains('seleccionado')) {
        boletosSeleccionados.push(folio);
      } else {
        boletosSeleccionados = boletosSeleccionados.filter(f => f !== folio);
      }

      // Actualizar el enlace con los boletos seleccionados
      const params = new URLSearchParams();
      params.set('idArticulo', registrarBtn.dataset.idarticulo);
      params.set('boletos', boletosSeleccionados.join(','));

      registrarBtn.href = `registroCliente.php?${params.toString()}`;
    });
  });
});
