<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['productos_seleccionados']) || empty($_POST['productos_seleccionados'])) {
    echo "<p>No se seleccionaron productos para cotizar.</p>";
    echo "<a href='productosn.php'>Volver</a>";
    exit;
}

$ids = $_POST['productos_seleccionados'];
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT * FROM productosn WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Cotización Generada</h2>

<table class="table table-bordered mt-3">
  <thead class="table-info">
    <tr>
      <th>Código</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Precio</th>
    </tr>
  </thead>
  <tbody>
    <?php $total = 0; ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['codigo']) ?></td>
        <td><?= htmlspecialchars($row['producto']) ?></td>
        <td><?= htmlspecialchars($row['descripcion']) ?></td>
        <td>$<?= number_format($row['precio'], 2) ?></td>
      </tr>
      <?php $total += $row['precio']; ?>
    <?php endwhile; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="3">Total</th>
      <th>$<?= number_format($total, 2) ?></th>
    </tr>
  </tfoot>
</table>

<a href="productosn.php" class="btn btn-secondary mt-3">Volver a Productos</a>

</body>

</html>
