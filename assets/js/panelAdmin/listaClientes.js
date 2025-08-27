document
  .getElementById("lista-clientes")
  .addEventListener("click", function () {
    const filas = this.querySelectorAll("tr");
    let texto = "";

    filas.forEach((fila) => {
      const columnas = fila.querySelectorAll("td");
      const filaTexto = Array.from(columnas)
        .map((col) => col.textContent.trim())
        .join("\t");
      texto += filaTexto + "\n";
    });

    // Copiar al portapapeles
    navigator.clipboard
      .writeText(texto)
      .then(() => {
        alert("Contenido copiado al portapapeles.");
      })
      .catch((err) => {
        console.error("Error al copiar: ", err);
        alert("Error al copiar al portapapeles.");
      });
  });

//Copiar clientes en responsividad
document
  .getElementById("copiar-clientes")
  .addEventListener("click", function () {
    const cards = document.querySelectorAll(".cards-container .card");
    let texto = "";

    cards.forEach((card) => {
      const nombreRaw =
        card.querySelector("h3:nth-child(1)")?.textContent.trim() || "";
      const boletosRaw =
        card.querySelector("h3:nth-child(2)")?.textContent.trim() || "";

      const nombre = nombreRaw.replace(/^Nombre:\s*/i, "");
      const boletos = boletosRaw.replace(/^Boletos:\s*/i, "");

      texto += nombre + "\n" + boletos + "\n\n";
    });

    if (texto.trim() === "") {
      texto = "No hay clientes registrados";
    }

    navigator.clipboard
      .writeText(texto)
      .then(() => {
        alert("Contenido copiado al portapapeles.");
      })
      .catch((err) => {
        console.error("Error al copiar: ", err);
        alert("Error al copiar al portapapeles.");
      });
  });
