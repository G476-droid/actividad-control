<?php
// control_pintura.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Pintura</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🖌️ Control de Pintura</h2>
    <div class="alert alert-info">Aquí podrás registrar trabajos, materiales usados y tiempos.</div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form>
          <div class="mb-3">
            <label for="nombreTrabajo" class="form-label">Nombre del trabajo</label>
            <input type="text" class="form-control" id="nombreTrabajo">
          </div>
          <div class="mb-3">
            <label for="materiales" class="form-label">Materiales usados</label>
            <textarea class="form-control" id="materiales" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="tiempo" class="form-label">Tiempo estimado (hrs)</label>
            <input type="number" class="form-control" id="tiempo">
          </div>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
