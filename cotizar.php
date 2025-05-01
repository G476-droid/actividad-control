<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar'])) {
    // Guardar cotización
    $productos = json_decode($_POST['datos'], true);
    $subtotal = $_POST['subtotal'];
    $iva = $_POST['iva'];
    $total = $_POST['total'];

    $sql = "INSERT INTO cotizaciones (productos, subtotal, iva, total) VALUES ($1, $2, $3, $4)";
    $params = [json_encode($productos), $subtotal, $iva, $total];
    $res = pg_query_params($conn, $sql, $params);

    if ($res) {
        echo "<p>Cotización guardada correctamente.</p>";
    } else {
        echo "<p>Error al guardar la cotización.</p>";
    }

    echo "<a href='productosn.php'>Volver</a>";
    exit;
}

// Continuar con carga de productos
if (!isset($_POST['productos_seleccionados']) || empty($_POST['productos_seleccionados'])) {
    echo "<p>No se seleccionaron productos para cotizar.</p>";
    echo "<a href='productosn.php'>Volver</a>";
    exit;
}

$ids = $_POST['productos_seleccionados'];
$placeholders = [];
$params = [];
$i = 1;
foreach ($ids as $id) {
    $placeholders[] = '$' . $i++;
    $params[] = $id;
}
$sql = "SELECT * FROM productosn WHERE id IN(" . implode(",", $placeholders). ")";
$result = pg_query_params($conn, $sql, $params);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function calcularTotales() {
      let subtotal = 0;
      const filas = document.querySelectorAll(".fila-producto");
      const productos = [];

      filas.forEach(fila => {
        const codigo = fila.querySelector(".codigo").textContent;
        const descripcion = fila.querySelector(".descripcion").textContent;
        const precio = parseFloat(fila.querySelector(".precio").textContent);
        const cantidad = parseFloat(fila.querySelector(".cantidad").value) || 0;
        const descuento = parseFloat(fila.querySelector(".descuento").value) || 0;
        const valor = (precio * cantidad) * (1 - descuento / 100);
        fila.querySelector(".valor").textContent = "$" + valor.toFixed(2);
        subtotal += valor;

        productos.push({ codigo, descripcion, precio, cantidad, descuento, valor });
      });

      const iva = subtotal * 0.15;
      const total = subtotal + iva;

      document.getElementById("subtotal").textContent = "$" + subtotal.toFixed(2);
      document.getElementById("iva").textContent = "$" + iva.toFixed(2);
      document.getElementById("total").textContent = "$" + total.toFixed(2);
      document.getElementById("transferencia").textContent = "$" + total.toFixed(2);

      // Set hidden inputs
      document.getElementById("datos").value = JSON.stringify(productos);
      document.getElementById("subtotal_input").value = subtotal.toFixed(2);
      document.getElementById("iva_input").value = iva.toFixed(2);
      document.getElementById("total_input").value = total.toFixed(2);
    }
  </script>
</head>
<body class="p-4">

<h3 class="mb-4 text-center">NOVOPAN</h3>
<p><strong>REQUERIMIENTO:</strong> Automático</p>
<p><strong>FECHA:</strong> <?= date('d-M-y') ?></p>

<form method="POST" onsubmit="return calcularTotales()">
<table class="table table-bordered mt-3 text-center">
  <thead class="table-secondary">
    <tr>
      <th>CÓDIGO</th>
      <th>DESCRIPCIÓN</th>
      <th>PRECIO</th>
      <th>CANTIDAD</th>
      <th>DESCUENTO (%)</th>
      <th>VALOR</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = pg_fetch_assoc($result)): ?>
      <tr class="fila-producto">
        <td class="codigo"><?= htmlspecialchars($row['codigo']) ?></td>
        <td class="descripcion"><?= htmlspecialchars($row['descripcion']) ?></td>
        <td class="precio"><?= number_format($row['precio_usd'], 2, '.', '') ?></td>
        <td><input type="number" class="form-control cantidad" value="1" min="0" onchange="calcularTotales()"></td>
        <td><input type="number" class="form-control descuento" value="0" min="0" max="100" onchange="calcularTotales()"></td>
        <td class="valor">$<?= number_format($row['precio_usd'], 2) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div class="row justify-content-end mt-4">
  <div class="col-md-4">
    <table class="table">
      <tr><th>SUBTOTAL</th><td id="subtotal">$0.00</td></tr>
      <tr><th>IVA (15%)</th><td id="iva">$0.00</td></tr>
      <tr><th>TOTAL</th><td id="total">$0.00</td></tr>
    </table>
  </div>
</div>

<div class="mt-4">
  <h5><strong>MONTO DE LA TRANSFERENCIA</strong>: <span id="transferencia">$0.00</span></h5>
</div>

<!-- Hidden inputs -->
<input type="hidden" name="datos" id="datos">
<input type="hidden" name="subtotal" id="subtotal_input">
<input type="hidden" name="iva" id="iva_input">
<input type="hidden" name="total" id="total_input">
<input type="hidden" name="aprobar" value="1">

<div class="text-center">
  <button type="submit" class="btn btn-success mt-4">Aprobar Cotización</button>
</div>
</form>

<a href="productosn.php" class="btn btn-secondary mt-3">Volver a Productos</a>

<script>
  window.onload = calcularTotales;
</script>

</body>
</html>
