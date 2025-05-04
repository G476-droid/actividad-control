<?php
// control_herramientas.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Herramientas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🔧 Control de Herramientas</h2>
    <div class="alert alert-warning">Registro de préstamos y devoluciones de herramientas.</div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form>
          <div class="mb-3">
            <label for="herramienta" class="form-label">Nombre de herramienta</label>
            <input type="text" class="form-control" id="herramienta">
          </div>
          <div class="mb-3">
            <label for="responsable" class="form-label">Responsable</label>
            <input type="text" class="form-control" id="responsable">
          </div>
          <div class="mb-3">
            <label for="fechaPrestamo" class="form-label">Fecha de préstamo</label>
            <input type="date" class="form-control" id="fechaPrestamo">
          </div>
          <button type="submit" class="btn btn-success">Registrar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
