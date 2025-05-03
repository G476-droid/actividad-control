<?php
include "db.php";
$req = intval($_GET['requerimiento']);
pg_query_params($conn, "DELETE FROM cotizaciones WHERE requerimiento = $1", [$req]);
header("Location: cotizar.php"); // O donde esté tu historial
exit;
?>
