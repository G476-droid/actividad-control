<?php
$host = "dpg-d0jacaeuk2gs73bltelg-a.oregon-postgres.render.com";
$user = "actividadesmyv20251_db";
$password = "Yxs6lcIE8T6Fe8sj6v0IuNCdzde9Pdda";
$dbname = "actividadesmyv20251_db_mpfc";
$port = "5432";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}
?>
