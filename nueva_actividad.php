<?php
include "db.php";

$persona_id = $_GET['persona_id'];

if ($_POST) {
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $fecha = $_POST['fecha'];
  $prioridad = $_POST['prioridad'];

  $conn->query("INSERT INTO actividades (persona_id, titulo, descripcion, fecha, prioridad) 
                VALUES ($persona_id, '$titulo', '$descripcion', '$fecha', '$prioridad')");
  header("Location: actividades.php?persona_id=$persona_id");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Actividad</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Nueva Actividad</h2>
  <form method="POST">
    <input name="titulo" placeholder="Título" class="form-control mb-2" required>
    <textarea name="descripcion" placeholder="Descripción" class="form-control mb-2"></textarea>
    <input name="fecha" type="date" class="form-control mb-2" required>
    <select name="prioridad" class="form-select mb-2">
      <option value="alta">Alta</option>
      <option value="media" selected>Media</option>
      <option value="baja">Baja</option>
    </select>
    <button class="btn btn-success">Guardar</button>
    <a href="actividades.php?persona_id=<?= $persona_id ?>" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>
