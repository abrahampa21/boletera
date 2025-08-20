// Selección de boletos
let boletosSeleccionados = [];
let boletosRegistrados = false;

document.querySelectorAll(".boleto-card").forEach(boleto => {
    boleto.addEventListener("click", () => {
        // Si ya se registraron los boletos o el boleto está vendido, no permitir selección
        if (boletosRegistrados || boleto.classList.contains('vendido') || boleto.dataset.vendido === 'true') {
            return;
        }

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

    // Verificar que ningún boleto seleccionado esté ya vendido
    const boletosInvalidos = boletosSeleccionados.filter(folio => {
        const boleto = document.querySelector(`[data-folio="${folio}"]`);
        return boleto && (boleto.classList.contains('vendido') || boleto.dataset.vendido === 'true');
    });

    if (boletosInvalidos.length > 0) {
        alert("Algunos boletos seleccionados ya han sido vendidos. Por favor, actualiza la página.");
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
            // Marcar los boletos como registrados temporalmente
            boletosRegistrados = true;
            
            // Cambiar el estilo de los boletos seleccionados
            document.querySelectorAll(".boleto-card.seleccionado").forEach(boleto => {
                boleto.classList.remove("seleccionado");
                boleto.classList.add("registrado");
            });
            
            // Cambiar el cursor de todos los boletos disponibles
            document.querySelectorAll(".boleto-card:not(.vendido)").forEach(boleto => {
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
            alert("Error al guardar boletos: " + (data.message || "Error desconocido"));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error al procesar la solicitud.");
    });
});

// Inicializar la página: asegurar que los boletos vendidos no sean seleccionables
document.addEventListener('DOMContentLoaded', function() {
    // Si existe la variable boletosVendidos del PHP, marcar esos boletos
    if (typeof boletosVendidos !== 'undefined') {
        boletosVendidos.forEach(folio => {
            const boleto = document.querySelector(`[data-folio="${folio}"]`);
            if (boleto) {
                boleto.classList.add('vendido');
                boleto.dataset.vendido = 'true';
                boleto.style.cursor = 'not-allowed';
            }
        });
    }
});