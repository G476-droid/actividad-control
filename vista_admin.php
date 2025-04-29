<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['persona_id']) || empty($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php"); // Redirigir si no es admin
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Principal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .menu-container {
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .menu-button {
      width: 300px;
      margin: 15px 0;
      font-size: 1.5rem;
      padding: 20px;
      border-radius: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="menu-container">
  <h1 class="mb-5">Menú Principal</h1>

  <a href="administracion.php" class="btn btn-primary menu-button">Administración</a>
  <a href="actividades.php" class="btn btn-success menu-button">Actividades</a>
  <a href="taller.php" class="btn btn-warning menu-button">Taller</a>
  <a href="novopan.php" class="btn btn-info menu-button">Novopan</a>
  <a href="grupo_euro.php" class="btn btn-danger menu-button">Grupo Euro</a>

  <a href="logout.php" class="btn btn-secondary mt-4">Cerrar Sesión</a>
</div>

</body>
</html>
