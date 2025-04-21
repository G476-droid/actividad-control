<?php
include "db.php";
$id = $_GET['id'];
$persona_id = $_GET['persona_id'];

if ($_POST) {
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $fecha = $_POST['fecha'];
  $prioridad = $_POST['prioridad'];
  $conn->query("UPDATE actividades SET titulo='$titulo', descripcion='$descripcion', fecha='$fecha', prioridad='$prioridad' WHERE id = $id");
  header("Location: actividades.php?persona_id=$persona_id");
}

$act = $conn->query("SELECT * FROM actividades WHERE id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Actividad</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Editar Actividad</h2>
  <form method="POST">
    <input name="titulo" value="<?= $act['titulo'] ?>" class="form-control mb-2" required>
    <textarea name="descripcion" class="form-control mb-2"><?= $act['descripcion'] ?></textarea>
    <input name="fecha" type="date" value="<?= $act['fecha'] ?>" class="form-control mb-2" required>
    <select name="prioridad" class="form-select mb-2">
      <option value="alta" <?= $act['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
      <option value="media" <?= $act['prioridad'] == 'media' ? 'selected' : '' ?>>Media</option>
      <option value="baja" <?= $act['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
    </select>
    <button class="btn btn-success">Guardar</button>
    <a href="actividades.php?persona_id=<?= $persona_id ?>" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>