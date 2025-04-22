<?php
// mover_incompletas.php
// ------------------------------------------------
if (!isset($persona_id)) {
    die("ID de persona no definido.");
}

// Fecha de hoy
$hoy = date('Y-m-d');

// Construir la consulta
$sql = "
    UPDATE actividades
    SET fecha = '$hoy'
    WHERE completada = FALSE
      AND fecha < '$hoy'
      AND persona_id = $persona_id
";

// Ejecutar y comprobar errores
$result = pg_query($conn, $sql);
if (!$result) {
    die("Error al mover actividades incompletas: " . pg_last_error($conn));
}
?>

