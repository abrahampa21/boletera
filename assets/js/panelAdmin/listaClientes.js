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
