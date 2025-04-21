<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Seleccionar Persona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="container py-5">
  <div class="row align-items-center" style="min-height: 60vh;">
    <!-- Logo a la izquierda, centrado verticalmente -->
    <div class="col-md-6 d-flex justify-content-center align-items-center">
      <img src="logo.jpeg" alt="Logo de la Empresa" class="logo-img">
    </div>

    <!-- Formulario a la derecha -->
    <div class="col-md-6 text-center text-md-start">
      <h2 class="mb-4">Selecciona una persona</h2>
      <form action="actividades.php" method="GET" class="w-75 mx-auto">
        <select name="persona_id" class="form-select mb-3" required>
          <option value="">-- Selecciona --</option>
          <?php
          $res = $conn->query("SELECT * FROM personas");
          while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
          }
          ?>
        </select>
        <button class="btn btn-primary">Ver Actividades</button>
      </form>
    </div>
  </div>
</body>
</html>


