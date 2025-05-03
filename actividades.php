<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirigir si no está logueado
if (!isset($_SESSION['persona_id'])) {
  header("Location: index.php");
  exit;
}

// Validar persona_id
$persona_id = $_SESSION['persona_id'];
$es_admin = $_SESSION['es_admin'] ?? false;

require "mover_incompletas.php";

if ($es_admin) {
  $persona = ['nombre' => 'Administrador'];
  $actividades_sql = pg_query($conn, "SELECT actividades.*, personas.nombre AS nombre_persona 
    FROM actividades 
    JOIN personas ON personas.id = actividades.persona_id 
    WHERE borrado = FALSE AND completada = FALSE
    ORDER BY 
      CASE prioridad 
        WHEN 'alta' THEN 1 
        WHEN 'media' THEN 2 
        WHEN 'baja' THEN 3 
        ELSE 4 
      END, fecha ASC");
} else {
  $persona_sql = pg_query_params($conn, "SELECT nombre FROM personas WHERE id = $1", array($persona_id));
  $persona = pg_fetch_assoc($persona_sql);
  $actividades_sql = pg_query_params($conn, "SELECT * FROM actividades 
    WHERE persona_id = $1 AND borrado = FALSE AND completada = FALSE
    ORDER BY 
      CASE prioridad 
        WHEN 'alta' THEN 1 
        WHEN 'media' THEN 2 
        WHEN 'baja' THEN 3 
        ELSE 4 
    END, fecha ASC", array($persona_id));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actividades de <?= htmlspecialchars($persona['nombre']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="style.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Actividades de <?= htmlspecialchars($persona['nombre']) ?></h2>
  <a href="nueva_actividad.php?persona_id=<?= $persona_id ?>" class="btn btn-sm btn-success mb-3">+ Nueva Actividad</a>
  <a href="actividad_completa.php?persona_id=<?= $persona_id ?>" class="btn btn-sm btn-primary mb-3">✅ Ver Completadas</a>
  <a href="logout.php" class="btn btn-sm btn-outline-danger float-end">Cerrar sesión</a>
  <table class="table table-bordered">
   <thead>
  <tr>
    <?php if ($es_admin): ?><th>Persona</th><?php endif; ?>
    <th>Título</th>
    <th>Descripción</th>
    <th>Fecha</th>
    <th>Prioridad</th>
    <th>Completada</th>
    <th>Acciones</th>
  </tr>
</thead>
    <?php
  // Obtener fecha actual (formato 'Y-m-d')
  $hoy = date('Y-m-d');

  // Reiniciar puntero de resultados
  pg_result_seek($actividades_sql, 0);

  // Mensaje inicial
  $mensaje = "📅 *Actividades pendientes para hoy ($hoy):*\n\n";

  $hay_actividades = false;

  while ($row = pg_fetch_assoc($actividades_sql)) {
    if ($row['fecha'] === $hoy) {
      $hay_actividades = true;
      $nombre = $es_admin ? $row['nombre_persona'] : $persona['nombre'];
      $mensaje .= "👤 *{$nombre}*\n";
      $mensaje .= "📌 *" . $row['titulo'] . "*\n";
      $mensaje .= "🗒️ " . $row['descripcion'] . "\n";
      $mensaje .= "⚠️ Prioridad: " . ucfirst($row['prioridad']) . "\n";
      $mensaje .= "-----------------------\n";
    }
  }

  if (!$hay_actividades) {
    $mensaje = "✅ No hay actividades pendientes para hoy ($hoy).";
  }

  // Codificar para URL
  $mensaje_url = urlencode($mensaje);
  $telefono_destino = ""; // Puedes poner algo como "5215512345678"
  $whatsapp_link = "https://wa.me/{$telefono_destino}?text={$mensaje_url}";
?>
<div class="mb-4">
  <a href="<?= $whatsapp_link ?>" target="_blank" class="btn btn-success">
    📲 Enviar Actividades de Hoy por WhatsApp
  </a>
</div>

<tbody>
 <?php while ($row = pg_fetch_assoc($actividades_sql)): ?>
    <?php
      $clase_prioridad = '';
      switch ($row['prioridad']) {
        case 'alta':
          $clase_prioridad = 'table-danger'; // rojo
          break;
        case 'media':
          $clase_prioridad = 'table-warning'; // amarillo
          break;
        case 'baja':
          $clase_prioridad = 'table-success'; // verde
          break;
        default:
          $clase_prioridad = '';
      }
    ?>
    <tr class="<?= $clase_prioridad ?>">
    <?php if ($es_admin): ?><td><?= htmlspecialchars($row['nombre_persona']) ?></td><?php endif; ?>
    <td><?= htmlspecialchars($row['titulo']) ?></td>
    <td><?= htmlspecialchars($row['descripcion']) ?></td>
    <td><?= htmlspecialchars($row['fecha']) ?></td>
    <td><?= ucfirst($row['prioridad']) ?></td>
    <td><?= $row['completada'] === 't' ? '✅' : '❌' ?></td>
    <td>

         <?php if ($row['completada'] === 'f'): ?>
    <a href="completar.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary mb-1">Marcar hecha</a>
    <a href="editar_actividad.php?id=<?= $row['id'] ?>&persona_id=<?= $persona_id ?>" class="btn btn-sm btn-info">Editar</a>
    <a href="eliminar.php?id=<?= $row['id'] ?>&persona_id=<?= $persona_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta actividad?')">Eliminar</a>
  <?php endif; ?>

    </td>
  </tr>
  <?php endwhile; ?>
</tbody>
  </table>
</body>
</html>
