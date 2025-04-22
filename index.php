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
    <div class="col-md-6 d-flex justify-content-center align-items-center">
      <img src="logo.jpeg" alt="Logo de la Empresa" class="logo-img">
    </div>

    <div class="col-md-6 text-center text-md-start">
      <h2 class="mb-4">Selecciona una persona</h2>
      <form action="actividades.php" method="GET" class="w-75 mx-auto">
        <select name="persona_id" class="form-select mb-3" required>
          <option value="">-- Selecciona --</option>
          <?php
          $result = pg_query($conn, "SELECT * FROM personas");
          if ($result) {
            while ($row = pg_fetch_assoc($result)) {
              echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
            }
          } else {
            echo "<option>Error al cargar personas</option>";
          }
          ?>
        </select>
        <button class="btn btn-primary">Ver Actividades</button>
      </form>
    </div>
  </div>
</body>
</html>



