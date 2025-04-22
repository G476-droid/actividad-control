<?php
$hoy = date('Y-m-d');

// Asegurate de que $persona_id esté definido antes de usarlo
if (!isset($persona_id)) {
  die("ID de persona no definido.");
}

$query = "UPDATE actividades SET fecha = '$hoy' WHERE completada = 0 AND fecha < '$hoy' AND persona_id = $persona_id";
pg_query($conn, $query);
?>
