<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variable para los mensajes
enqueue:
$mensaje = "";

// Guardar cotización al aprobar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar'])) {
    $req       = intval($_POST['requerimiento']);
    $productos = json_decode($_POST['datos'], true);
    $subtotal  = $_POST['subtotal'];
    $iva       = $_POST['iva'];
    $total     = $_POST['total'];

    $verif = pg_query_params($conn, "SELECT 1 FROM cotizaciones WHERE requerimiento = $1", [$req]);
    if (pg_num_rows($verif) > 0) {
        $mensaje = "<div class='alert alert-danger mt-3'>El requerimiento #{$req} ya existe. Debes usar un número diferente.</div>";
    } else {
        // Insertar si no existe
        $sql = "INSERT INTO cotizaciones (requerimiento, productos, subtotal, iva, total) 
                OVERRIDING SYSTEM VALUE VALUES ($1, $2, $3, $4, $5)";
        $params = [$req, json_encode($productos), $subtotal, $iva, $total];
        $res = pg_query_params($conn, $sql, $params);

        if ($res) {
            $mensaje = "<div class='alert alert-success mt-3'>Cotización #{$req} guardada correctamente.</div>";
        } else {
            $err = pg_last_error($conn);
            $mensaje = "<div class='alert alert-danger mt-3'>Error al guardar la cotización: {$err}</div>";
        }
    }
}

// Cargar productos si vienen seleccionados
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productos_seleccionados']) && !empty($_POST['productos_seleccionados'])) {
    $ids = $_POST['productos_seleccionados'];
    $placeholders = [];
    $params = [];
    foreach ($ids as $i => $id) {
        $placeholders[] = '$' . ($i+1);
        $params[] = $id;
    }
    $sql = "SELECT * FROM productosn WHERE id IN(" . implode(',', $placeholders) . ")";
    $result = pg_query_params($conn, $sql, $params);
}

// Obtener historial de cotizaciones
$h = pg_query($conn, "SELECT requerimiento, fecha, productos, subtotal, iva, total FROM cotizaciones ORDER BY fecha DESC, requerimiento DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function calcularTotales() {
      let subtotal = 0;
      const filas = document.querySelectorAll('.fila-producto');
      const productosArr = [];
      filas.forEach(fila => {
        const codigo = fila.querySelector('.codigo').textContent;
        const descripcion = fila.querySelector('.descripcion').textContent;
        const precio = parseFloat(fila.querySelector('.precio').textContent);
        const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
        const descuento = parseFloat(fila.querySelector('.descuento').value) || 0;
        const valor = (precio * cantidad) * (1 - descuento/100);
        fila.querySelector('.valor').textContent = '$' + valor.toFixed(2);
        subtotal += valor;
        productosArr.push({ codigo, descripcion, precio, cantidad, descuento, valor });
      });
      const iva = subtotal * 0.15;
      const total = subtotal + iva;
      document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
      document.getElementById('iva').textContent = '$' + iva.toFixed(2);
      document.getElementById('total').textContent = '$' + total.toFixed(2);
      document.getElementById('transferencia').textContent = '$' + total.toFixed(2);
      document.getElementById('datos').value = JSON.stringify(productosArr);
      document.getElementById('subtotal_input').value = subtotal.toFixed(2);
      document.getElementById('iva_input').value = iva.toFixed(2);
      document.getElementById('total_input').value = total.toFixed(2);
      return true;
    }

    function eliminarCotizacion(req) {
      if (confirm('¿Estás seguro de eliminar esta cotización?')) {
        $.get('eliminar_cotizacion.php', { requerimiento: req }, function(response) {
          const data = JSON.parse(response);
          alert(data.message);
          if (data.success) {
            $('#cotizacion-' + req).remove();
          }
        });
      }
    }
  </script>
</head>
<body class="p-4">
<div class="container">
    <?php if (!empty($mensaje)) echo $mensaje; ?>
  <h3 class="mb-4 text-center">NOVOPAN</h3>
  <a href="productosn.php" class="btn btn-outline-dark mb-4">← Volver al Menú Principal</a>

  <?php if ($result && pg_num_rows($result) > 0): ?>
    <form method="POST" onsubmit="return calcularTotales()">
      <!-- Formulario de cotización aquí -->
    </form>
    <hr class="my-5">
  <?php endif; ?>

  <h4>Historial de Cotizaciones</h4>
  <?php if (pg_num_rows($h) > 0): ?>
    <table class="table table-striped mt-3">
      <thead class="table-info">
        <tr>
          <th>Req.</th><th>Fecha</th><th>Productos</th><th>Subtotal</th><th>IVA</th><th>Total</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while($c = pg_fetch_assoc($h)): ?>
        <?php $items = json_decode($c['productos'], true); ?>
        <tr id="cotizacion-<?= $c['requerimiento'] ?>">
          <td><?= $c['requerimiento'] ?></td>
          <td><?= date('d-M-Y', strtotime($c['fecha'])) ?></td>
          <td>
            <ul class="text-start mb-0">
            <?php foreach($items as $item): ?>
              <li><?= htmlspecialchars($item['descripcion']) ?> (x<?= htmlspecialchars($item['cantidad']) ?>)</li>
            <?php endforeach; ?>
            </ul>
          </td>
          <td>$<?= number_format($c['subtotal'],2) ?></td>
          <td>$<?= number_format($c['iva'],2) ?></td>
          <td><strong>$<?= number_format($c['total'],2) ?></strong></td>
          <td>
            <div class="btn-group" role="group">
              <a href="editar_cotizacion.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-warning">Editar</a>
              <button type="button" class="btn btn-sm btn-danger" onclick="eliminarCotizacion(<?= $c['requerimiento'] ?>)">Eliminar</button>
              <a href="generar_pdf.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-secondary">PDF</a>
              <a href="generar_excel.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-success">Excel</a>
            </div>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">No hay cotizaciones registradas.</p>
  <?php endif; ?>
</div>
</body>
</html>
