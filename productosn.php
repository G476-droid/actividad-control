<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $producto = $_POST['producto'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    
    // Consulta con filtro
    $sql = "SELECT * FROM productosn WHERE 
            codigo LIKE :codigo AND 
            producto LIKE :producto AND 
            descripcion LIKE :descripcion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':codigo' => "%$codigo%",
        ':producto' => "%$producto%",
        ':descripcion' => "%$descripcion%"
    ]);
    $productosn = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $productosn = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Buscar Productos</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" value="<?= htmlspecialchars($codigo ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="producto" class="form-label">Producto</label>
            <input type="text" class="form-control" id="producto" name="producto" value="<?= htmlspecialchars($producto ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="descripcion" class="form-label">Descripcion</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= htmlspecialchars($descripcion ?? '') ?>">
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <h3 class="mt-5">Productos Encontrados</h3>
    <?php if (!empty($productosn)): ?>
        <form action="procesar_compra.php" method="POST">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosn as $producto): ?>
                        <tr>
                            <td><input type="checkbox" name="productosn[]" value="<?= $producto['id'] ?>"></td>
                            <td><?= htmlspecialchars($producto['codigo']) ?></td>
                            <td><?= htmlspecialchars($producto['producto']) ?></td>
                            <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                            <td><?= number_format($producto['precio'], 2) ?> USD</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success mt-3">Realizar Compra</button>
        </form>
    <?php else: ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
