<?php
if (!isset($conn)) {
    include "db.php";
}
if (!isset($h)) {
    // Si no se ha definido la variable $h, realiza la consulta
    $h = pg_query($conn, "SELECT requerimiento, fecha, productos, subtotal, iva, total FROM cotizaciones ORDER BY fecha DESC, requerimiento DESC");
}
?>
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
