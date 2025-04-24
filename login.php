<?php
include "db.php";

if ($_POST) {
  $nombre = $_POST['nombre'];
  $clave = $_POST['clave'];

  $query = "SELECT * FROM personas WHERE nombre = $1 AND clave = $2";
  $res = pg_query_params($conn, $query, array($nombre, $clave));

  if (pg_num_rows($res) == 1) {
    session_start();
    $persona = pg_fetch_assoc($res);
    $_SESSION['persona_id'] = $persona['id'];
    $_SESSION['es_admin'] = $persona['es_admin'];

    header("Location: actividades.php");
    exit;
  } else {
    $error = "Nombre o clave incorrectos.";
  }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
  <h2>Iniciar Sesión</h2>
  <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
  <form method="POST">
    <input name="nombre" placeholder="Nombre"><br>
    <input name="clave" placeholder="Clave" type="password"><br>
    <button>Entrar</button>
  </form>
</body>
</html>
