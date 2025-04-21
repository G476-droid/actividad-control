<?php
include "db.php";
$id = $_GET['id'];
$persona_id = $_GET['persona_id'];
$conn->query("DELETE FROM actividades WHERE id = $id");
header("Location: actividades.php?persona_id=$persona_id");