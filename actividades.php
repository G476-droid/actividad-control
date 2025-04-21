<?php
include "db.php";

$persona_id = $_GET['persona_id'];
if (!$persona_id) header("Location: index.php");

require "mover_incompletas.php";

$persona = $conn->query("SELECT nombre FROM personas WHERE id = $persona_id")->fetch_assoc();
$actividades = $conn->query("SELECT * FROM actividades WHERE persona_id = $persona_id ORDER BY prioridad DESC, fecha ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actividades de <?= $persona['nombre'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Actividades de <?= $persona['nombre'] ?></h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">Cambiar Persona</a>
  <a href="nueva_actividad.php?persona_id=<?= $persona_id ?>" class="btn btn-sm btn-success mb-3">+ Nueva Actividad</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Fecha</th>
        <th>Prioridad</th>
        <th>Completada</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $actividades->fetch_assoc()): ?>
      <tr class="<?= $row['completada'] ? 'table-success' : 'table-warning' ?>">
        <td><?= $row['titulo'] ?></td>
        <td><?= $row['descripcion'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td><?= ucfirst($row['prioridad']) ?></td>
        <td><?= $row['completada'] ? '✅' : '❌' ?></td>
        <td>
          <?php if (!$row['completada']): ?>
            <a href="completar.php?id=<?= $row['id'] ?>&persona_id=<?= $persona_id ?>" class="btn btn-sm btn-primary">Marcar hecha</a>
          <?php endif; ?>
          <a href="editar_actividad.php?id=<?= $row['id'] ?>&persona_id=<?= $persona_id ?>" class="btn btn-sm btn-info">Editar</a>
          <a href="eliminar.php?id=<?= $row['id'] ?>&persona_id=<?= $persona_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta actividad?')">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>