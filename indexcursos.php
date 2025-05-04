<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cursos Profesionales</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4">🎓 Plataforma de Cursos</h2>
  <div class="row">
    <?php
    $result = pg_query($conn, "SELECT * FROM cursos");
    while ($curso = pg_fetch_assoc($result)): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="<?= $curso['imagen_url'] ?>" class="card-img-top" alt="Curso">
          <div class="card-body">
            <h5 class="card-title"><?= $curso['titulo'] ?></h5>
            <p class="card-text"><?= substr($curso['descripcion'], 0, 100) ?>...</p>
            <a href="curso.php?id=<?= $curso['id'] ?>" class="btn btn-primary">Ver Curso</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
