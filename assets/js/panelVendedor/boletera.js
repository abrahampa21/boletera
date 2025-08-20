// Selección de boletos
let boletosSeleccionados = [];

document.querySelectorAll(".boleto-card").forEach(boleto => {
    boleto.addEventListener("click", () => {
        const folio = boleto.dataset.folio;
        boleto.classList.toggle("seleccionado");

        if (boletosSeleccionados.includes(folio)) {
            boletosSeleccionados = boletosSeleccionados.filter(f => f !== folio);
        } else {
            boletosSeleccionados.push(folio);
        }
    });
});

// Enviar al hacer clic en "Registrar cliente"
document.getElementById("registrar-cliente").addEventListener("click", function (e) {
    e.preventDefault();

    if (boletosSeleccionados.length === 0) {
        alert("Selecciona al menos un boleto.");
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = this.href;
        } else {
            alert("Error al guardar boletos.");
        }
    });
});
