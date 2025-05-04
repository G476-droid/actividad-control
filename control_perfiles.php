<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Perfiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🏗️ Control de Perfiles de Aluminio</h2>
    <div class="alert alert-secondary">Gestión del inventario y cortes por proyecto.</div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form>
          <div class="mb-3">
            <label for="perfil" class="form-label">Tipo de perfil</label>
            <input type="text" class="form-control" id="perfil">
          </div>
          <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad disponible</label>
            <input type="number" class="form-control" id="cantidad">
          </div>
          <div class="mb-3">
            <label for="proyecto" class="form-label">Proyecto asignado</label>
            <input type="text" class="form-control" id="proyecto">
          </div>
          <button type="submit" class="btn btn-dark">Registrar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
