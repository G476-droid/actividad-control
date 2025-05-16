<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario sea administrador
if (!isset($_SESSION['persona_id']) || empty($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php"); // Redirigir si no es admin
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calculadora de Precio de Perfiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2 class="mb-4">Calculadora de Precio por Medidas</h2>

<form method="POST" class="row g-3">
  <div class="col-md-3">
    <label for="color" class="form-label">Color del Perfil</label>
    <select name="color" id="color" class="form-select" required>
      <option value="naturalc">Natural</option>
      <option value="basico">Básico (Negro, Antracita, Blanco)</option>
      <option value="especial">Especial (Dorado, Champagne, Bronce, Beige, u otros)</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Ancho (mm)</label>
    <input type="text" name="ancho" class="form-control" pattern="^\d{3,4}$" title="Ingrese un número entero de 3 a 4 dígitos sin puntos ni comas (ej: 800, 1050)" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Alto (mm)</label>
    <input type="text" name="alto" class="form-control" pattern="^\d{3,4}$" title="Ingrese un número entero de 3 a 4 dígitos sin puntos ni comas (ej: 800, 1050)" required>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">Calcular</button>
  </div>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $color = $_POST['color'];
    $ancho = $_POST['ancho'];
    $alto = $_POST['alto'];

    // Validación de formato
    if (!preg_match('/^\d{3,4}$/', $ancho) || !preg_match('/^\d{3,4}$/', $alto)) {
        echo "<div class='alert alert-danger mt-4'>Por favor ingrese solo números enteros de 3 o 4 dígitos sin puntos ni comas.</div>";
    } else {
        $ancho = intval($ancho);
        $alto = intval($alto);

        $mayor = max($ancho, $alto);
        $menor = min($ancho, $alto);
        $suma = $mayor + $menor;

        $suma_escapada = pg_escape_literal($conn, $suma);

        $query = "
            SELECT *, ABS((ancho + alto) - $suma) AS diferencia
            FROM precios_perfiles
            ORDER BY diferencia ASC
            LIMIT 1
        ";
        $result = pg_query($conn, $query);

        if ($result && pg_num_rows($result) > 0) {
            $resultado = pg_fetch_assoc($result);
            echo "<div class='alert alert-success mt-4'>";
            echo "<h5>Resultado encontrado:</h5>";
            echo "<ul>";
            echo "<li><strong>Ancho:</strong> {$resultado['ancho']} mm</li>";
            echo "<li><strong>Alto:</strong> {$resultado['alto']} mm</li>";
            echo "<li><strong>Área:</strong> {$resultado['area']} mm²</li>";
            echo "<li><strong>Precio ($color):</strong> $" . number_format($resultado[$color], 2) . "</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning mt-4'>No se encontró un valor cercano.</div>";
        }
    }
}
?>

</body>
</html>
