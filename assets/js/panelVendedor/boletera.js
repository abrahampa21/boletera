// Selección de boletos
let boletosSeleccionados = [];
let boletosRegistrados = false;
const boletosCard = document.querySelectorAll(".boleto-card");
const registrarCliente = document.getElementById("registrar-cliente");
const imprimirBoletera = document.getElementById("imprimir-boletera");

boletosCard.forEach((boleto) => {
  boleto.addEventListener("click", () => {
    if (
      boletosRegistrados ||
      boleto.classList.contains("vendido") ||
      boleto.dataset.vendido === "true"
    ) {
      return;
    }

    const folio = boleto.dataset.folio;
    boleto.classList.toggle("seleccionado");

    if (boletosSeleccionados.includes(folio)) {
      boletosSeleccionados = boletosSeleccionados.filter((f) => f !== folio);
    } else {
      boletosSeleccionados.push(folio);
    }
  });
});

// Enviar al hacer clic en "Registrar cliente"
registrarCliente.addEventListener("click", function (e) {
  e.preventDefault();

  if (boletosSeleccionados.length === 0) {
    alert("Selecciona al menos un boleto.");
    return;
  }

  const boletosInvalidos = boletosSeleccionados.filter((folio) => {
    const boleto = document.querySelector(`[data-folio="${folio}"]`);
    return (
      boleto &&
      (boleto.classList.contains("vendido") ||
        boleto.dataset.vendido === "true")
    );
  });

  if (boletosInvalidos.length > 0) {
    alert(
      "Algunos boletos seleccionados ya han sido vendidos. Por favor, actualiza la página."
    );
    return;
  }

  // Guardar en sesión por llamada AJAX
  fetch("guardarBoletos.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ boletos: boletosSeleccionados }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Marcar los boletos como registrados temporalmente
        boletosRegistrados = true;

        // Cambiar el estilo de los boletos seleccionados
        document
          .querySelectorAll(".boleto-card.seleccionado")
          .forEach((boleto) => {
            boleto.classList.remove("seleccionado");
            boleto.classList.add("registrado");
          });

        // Cambiar el cursor de todos los boletos disponibles
        document
          .querySelectorAll(".boleto-card:not(.vendido)")
          .forEach((boleto) => {
            boleto.style.cursor = "not-allowed";
          });

        // Cambiar el texto del botón
        this.textContent = "Procesando...";
        this.style.backgroundColor = "#6c757d";
        this.style.cursor = "not-allowed";

        // Redirigir después de un breve delay
        setTimeout(() => {
          window.location.href = this.href;
        }, 1000);
      } else {
        alert(
          "Error al guardar boletos: " + (data.message || "Error desconocido")
        );
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error al procesar la solicitud.");
    });
});

document.addEventListener("DOMContentLoaded", function () {
  if (typeof boletosVendidos !== "undefined") {
    boletosVendidos.forEach((folio) => {
      const boleto = document.querySelector(`[data-folio="${folio}"]`);
      if (boleto) {
        boleto.classList.add("vendido");
        boleto.dataset.vendido = "true";
        boleto.style.cursor = "not-allowed";
      }
    });
  }
});

imprimirBoletera.addEventListener("click", function (e) {
  e.preventDefault();

  const gridContent = document.querySelector(".boletos-grid");

  if (!gridContent) {
    alert("No hay boletos para imprimir.");
    return;
  }

  const contenidoOriginal = document.body.innerHTML;

  // Crear nuevo contenido imprimible
  const contenidoImprimir = `
    <style>
      body { font-family: Arial, sans-serif; padding: 20px; }
      .boleto-card {
        border: 1px solid #000;
        padding: 10px;
        margin: 5px;
        display: inline-block;
        text-align: center;
        width: 100px;
        height: 100px;
        vertical-align: top;
      }
      .vendido {
        background-color: #f8d7da;
        color: #721c24;
      }
      .vendido-label {
        display: block;
        font-size: 0.8em;
        margin-top: 5px;
        color: red;
        font-weight: bold;
      }
    </style>
    ${gridContent.outerHTML}
  `;

  document.body.innerHTML = contenidoImprimir;
  window.print();
  document.body.innerHTML = contenidoOriginal;

  // Recargar scripts después del cambio de body
  location.reload(); // o mejor: guarda el contenido en variables si no quieres recargar
});
