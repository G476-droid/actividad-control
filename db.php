<?php
$host = "dpg-d03gkjadbo4c738camp0-a";
$user = "actividadesmyv20251_db";
$pass = "7VovdXaqf7hoID2n6CkBrxgLx9rEmaoJ";
$db = "actividadesmyv20251_db_6phh";
$port = "5432";

$conn = pg_connect("host=$host port=$port db=$db user=$user pass=$pass");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}
?>
