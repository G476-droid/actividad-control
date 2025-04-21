<?php
$hoy = date('Y-m-d');
$conn->query("  UPDATE actividades   SET fecha = '$hoy'  WHERE completada = 0 AND fecha < '$hoy' AND persona_id = $persona_id");
