<?php
include "db.php";

// Validar y castear parámetros
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$persona_id = isset($_GET['persona_id']) ? (int) $_GET['persona_id'] : 0;

// Ejecutar el DELETE usando pg_query_params
$result = pg_query_params(
    $conn,
    "DELETE FROM actividades WHERE id = $1",
    array($id)
);

if (!$result) {
    die("Error al eliminar la actividad: " . pg_last_error($conn));
}

// Redirigir de vuelta a la lista de actividades
header("Location: actividades.php?persona_id=" . $persona_id);
exit;
?>
