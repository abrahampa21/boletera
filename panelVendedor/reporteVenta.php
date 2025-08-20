<?php

use Dompdf\Dompdf;
use Dompdf\Option;
use Dompdf\Exception as DomException;
use Dompdf\Options;

function post_crear_pdf()
{
      $download = true;
      $contenido = '<!DOCTYPE html>
      <html>
        <head>
          <style>
            table {
              width: 100%%;
              text-align: center;
            } 
          </style>
        </head>
        <body>
          <img src="%s" alt="%s" style="width: 100px;"><br>
    
          <h1>Bienvenido de nuevo a %s</h1>
          <p>Versión <b>%s</b></p>
          <p>%s</p>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$2,532</td>
              </tr>
              <tr>
                <td>2</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$712</td>
              </tr>
              <tr>
                <td>3</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$6,250</td>
              </tr>
              <tr>
                <td>4</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$8,152</td>
              </tr>
              <tr>
                <td>5</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$596</td>
              </tr>
              <tr>
                <td>6</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$1,756</td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>';
      $contenido = sprintf($contenido);

      // Nombre del pdf
      $filename = 'Folio de Venta.pdf';

      // Opciones para prevenir errores con carga de imágenes
      $options = new Options();
      $options->set('isRemoteEnabled', true);

      // Instancia de la clase
      $dompdf = new Dompdf($options);

      // Cargar el contenido HTML
      $dompdf->loadHtml($contenido);

      // Formato y tamaño del PDF
      $dompdf->setPaper('A4', 'portrait');

      // Renderizar HTML como PDF
      $dompdf->render();

      // Salida para descargar
      $dompdf->stream($filename, ['Attachment' => $download]);
}

?>