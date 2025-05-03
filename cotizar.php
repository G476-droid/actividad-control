<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = ""; // Mensaje a mostrar
$tipo_msg = ""; // success, danger, info, etc.

// Mostrar mensaje si viene desde eliminación
if (isset($_GET['msg']) && $_GET['msg'] === 'eliminado') {
    $msg = "Cotización eliminada correctamente.";
    $tipo_msg = "success";
}

// Guardar cotización al aprobar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar'])) {
    $req       = intval($_POST['requerimiento']);
    $productos = json_decode($_POST['datos'], true);
    $subtotal  = $_POST['subtotal'];
    $iva       = $_POST['iva'];
    $total     = $_POST['total'];

    $verif = pg_query_params($conn, "SELECT 1 FROM cotizaciones WHERE requerimiento = $1", [$req]);
    if (pg_num_rows($verif) > 0) {
        $msg = "⚠️ El requerimiento #{$req} ya existe. Debes usar un número diferente.";
        $tipo_msg = "danger";
    } else {
        $sql = "INSERT INTO cotizaciones (requerimiento, productos, subtotal, iva, total) 
                OVERRIDING SYSTEM VALUE VALUES ($1, $2, $3, $4, $5)";
        $params = [$req, json_encode($productos), $subtotal, $iva, $total];
        $res = pg_query_params($conn, $sql, $params);

        if ($res) {
            $msg = "✅ Cotización #{$req} guardada correctamente.";
            $tipo_msg = "success";
        } else {
            $err = pg_last_error($conn);
            $msg = "❌ Error al guardar la cotización: {$err}";
            $tipo_msg = "danger";
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
  </script>
</head>
<body class="p-4">
<div class="container">
<?php if (!empty($msg)): ?>
  <div class="alert alert-<?= $tipo_msg ?> alert-dismissible fade show" role="alert">
    <?= $msg ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
...
</div>
</body>
</html>
