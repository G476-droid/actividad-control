<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Comprobación de sesión
if (!isset($_SESSION['persona_id']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Filtro
$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
$sql = "SELECT * FROM productosn";
if ($codigo !== '') {
    $sql .= " WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
} else {
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos Novopan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 30px; background-color: #f2f2f2; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>

<h2 class="mb-4">Productos Novopan</h2>

<form method="GET" class="mb-4">
  <div class="input-group w-50">
    <input type="text" name="codigo" class="form-control" placeholder="Buscar por código..." value="<?= htmlspecialchars($codigo) ?>">
    <button type="submit" class="btn btn-primary">Buscar</button>
    <a href="productosn.php" class="btn btn-secondary">Limpiar</a>
  </div>
</form>

<form method="POST" action="cotizar.php">
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-primary">
      <tr>
        <th>Seleccionar</th>
        <th>Código</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><input type="checkbox" name="productos_seleccionados[]" value="<?= $row['id'] ?>"></td>
            <td><?= htmlspecialchars($row['codigo']) ?></td>
            <td><?= htmlspecialchars($row['producto']) ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td>$<?= number_format($row['precio'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No se encontraron productos.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <button type="submit" class="btn btn-success">Enviar a Cotización</button>
</form>

</body>
</html>

