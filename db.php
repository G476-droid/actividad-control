<?php
$host = "dpg-d03gkjadbo4c738camp0-a.oregon-postgres.render.com";
$user = "actividadesmyv20251_db";
$password = "7VovdXaqf7hoID2n6CkBrxgLx9rEmaoJ";
$dbname = "actividadesmyv20251_db_6phh";
$port = "5432";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

