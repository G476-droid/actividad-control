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
      <option value="basico">Básico (Nehro, Antracita, Blanco)</option>
      <option value="especial">Especial(Dorado, Champagne, Bronce, Beige, U otros)</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Ancho (m)</label>
    <input type="number" step="0.01" name="ancho" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Alto (m)</label>
    <input type="number" step="0.01" name="alto" class="form-control" required>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">Calcular</button>
  </div>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $color = $_POST['color'];
    $ancho = floatval($_POST['ancho']);
    $alto = floatval($_POST['alto']);

    // Detectar cuál es el alto real
    $mayor = max($ancho, $alto);
    $menor = min($ancho, $alto);

    // Suma de medidas
    $suma = $mayor + $menor;

    // Buscar en la tabla el registro más cercano a esa suma
    $stmt = $pdo->prepare("
        SELECT *, ABS((ancho + alto) - :suma) AS diferencia
        FROM precios_perfiles
        ORDER BY diferencia ASC
        LIMIT 1
    ");
    $stmt->execute(['suma' => $suma]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo "<div class='alert alert-success mt-4'>";
        echo "<h5>Resultado encontrado:</h5>";
        echo "<ul>";
        echo "<li><strong>Ancho:</strong> {$resultado['ancho']} m</li>";
        echo "<li><strong>Alto:</strong> {$resultado['alto']} m</li>";
        echo "<li><strong>Área:</strong> {$resultado['area']} m²</li>";
        echo "<li><strong>Precio ($color):</strong> $" . number_format($resultado[$color], 2) . "</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-warning mt-4'>No se encontró un valor cercano.</div>";
    }
}
?>

</body>
</html>
