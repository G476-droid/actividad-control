<?php
include "db.php";

// 1. Validar y castear persona_id
if (!isset($_GET['persona_id']) || !is_numeric($_GET['persona_id'])) {
    header("Location: index.php");
    exit;
}
$persona_id = (int) $_GET['persona_id'];

// 2. Mover actividades incompletas
require "mover_incompletas.php";

// 3. Cargar nombre de la persona
$resPersona = pg_query_params(
    $conn,
    "SELECT nombre FROM personas WHERE id = $1",
    array($persona_id)
);
if (!$resPersona) {
    die("Error al cargar persona: " . pg_last_error($conn));
}
$persona = pg_fetch_assoc($resPersona);

// 4. Cargar actividades de esa persona, ordenadas
$resActividades = pg_query_params(
    $conn,
    "SELECT *
     FROM actividades
     WHERE persona_id = $1
     ORDER BY
       CASE prioridad WHEN 'alta' THEN 1 WHEN 'media' THEN 2 WHEN 'baja' THEN 3 END,
       fecha ASC",
    array($persona_id)
);
if (!$resActividades) {
    die("Error al cargar actividades: " . pg_last_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actividades de <?= htmlspecialchars($persona['nombre']) ?></title>
  <!-- ... -->
</head>
<body>
  <!-- Tu HTML aquí, usando pg_fetch_assoc($resActividades) en el loop -->
</body>
</html>
