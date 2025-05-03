<?php
include "db.php";
ob_start(); // Inicia el búfer de salida para prevenir errores de encabezado

$req = intval($_GET['requerimiento']);

$res = pg_query_params($conn, "DELETE FROM cotizaciones WHERE requerimiento = $1", [$req]);

if ($res) {
    // Redirige de forma segura
    header("Location: cotizar.php");
    exit;
} else {
    // Mostrar error amigable si falla
    echo "<p>Error al eliminar la cotización.</p>";
    echo "<a href='cotizar.php'>Volver</a>";
}
ob_end_flush(); // Finaliza el búfer
?>

