<?php
require 'db.php';

// Guardar si hay envío POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombreTrabajo'];
    $materiales = $_POST['materiales'];
    $tiempo = $_POST['tiempo'];

    $stmt = $pdo->prepare("INSERT INTO control_pintura (nombre_trabajo, materiales, tiempo) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $materiales, $tiempo]);
}

// Consultar todos los registros
$registros = $pdo->query("SELECT * FROM control_pintura ORDER BY creado_en DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Pintura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Control de Pintura</h2>

    <!-- Formulario -->
    <form method="POST" class="card p-4 mb-5 shadow">
        <div class="mb-3">
            <label for="nombreTrabajo" class="form-label">Nombre del Trabajo</label>
            <input type="text" class="form-control" name="nombreTrabajo" required>
        </div>
        <div class="mb-3">
            <label for="materiales" class="form-label">Materiales</label>
            <textarea class="form-control" name="materiales" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="tiempo" class="form-label">Tiempo Estimado (horas)</label>
            <input type="number" class="form-control" name="tiempo" step="0.1" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <!-- Tabla de registros -->
    <div class="card p-4 shadow">
        <h5>Registros guardados</h5>
        <table class="table table-striped table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Trabajo</th>
                    <th>Materiales</th>
                    <th>Tiempo (h)</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $fila): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['nombre_trabajo']) ?></td>
                    <td><?= htmlspecialchars($fila['materiales']) ?></td>
                    <td><?= $fila['tiempo'] ?></td>
                    <td><?= $fila['creado_en'] ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($registros) === 0): ?>
                <tr><td colspan="5" class="text-center text-muted">No hay registros</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
