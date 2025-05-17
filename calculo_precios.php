<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario sea administrador
if (!isset($_SESSION['persona_id']) || empty($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calculadora de Precio de Perfiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .highlight {
      background-color: #d1e7dd !important;
    }
  </style>
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
    <input type="text" name="ancho" class="form-control" pattern="^\d{3,4}$" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Alto (mm)</label>
    <input type="text" name="alto" class="form-control" pattern="^\d{3,4}$" required>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">Calcular</button>
  </div>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $color = $_POST['color'];
    $ancho = intval($_POST['ancho']);
    $alto = intval($_POST['alto']);

    if ($ancho <= 0 || $alto <= 0) {
        echo "<div class='alert alert-danger mt-4'>Ingrese valores válidos.</div>";
    } else {
        $suma_cliente = $ancho + $alto;

        // Obtener la mejor coincidencia
        $query = "
            SELECT *, 
                ancho + alto AS suma_bd,
                ABS(ancho - $ancho) + ABS(alto - $alto) AS diferencia_total
            FROM precios_perfiles
            WHERE (ancho + alto) = $suma_cliente
            ORDER BY diferencia_total ASC
            LIMIT 1
        ";

        $res = pg_query($conn, $query);
        $mejor_match = pg_fetch_assoc($res);

        if ($mejor_match) {
            $precio = number_format($mejor_match[$color], 2);
            echo "<div class='alert alert-success mt-4'>";
            echo "<h5>Resultado Encontrado:</h5><ul>";
            echo "<li><strong>Ingresado:</strong> Ancho = {$ancho} mm, Alto = {$alto} mm</li>";
            echo "<li><strong>Medida usada (match):</strong> Ancho = {$mejor_match['ancho']} mm, Alto = {$mejor_match['alto']} mm</li>";
            echo "<li><strong>Área:</strong> {$mejor_match['area']} mm²</li>";
            echo "<li><strong>Precio ($color):</strong> $$precio</li>";
            echo "<li><strong>Suma:</strong> {$suma_cliente} mm</li>";
            echo "</ul></div>";
        } else {
            echo "<div class='alert alert-warning mt-4'>No se encontró una coincidencia exacta con esa suma de medidas. Intente con otros valores.</div>";
        }

        // Mostrar tabla
       $todos = pg_query($conn, "SELECT * FROM precios_perfiles ORDER BY alto ASC");

if ($todos && pg_num_rows($todos) > 0) {
    echo "<h5 class='mt-5'>Lista de Medidas Disponibles</h5>";
    echo "<table class='table table-bordered'><thead><tr>
        <th>Ancho</th><th>Alto</th><th>Área</th>
        <th>Natural</th><th>Básico</th><th>Especial</th>
    </tr></thead><tbody>";
    
    while ($fila = pg_fetch_assoc($todos)) {
        $destacar = "";
        if ($fila['alto'] == $mejor_match['alto'] && $fila['ancho'] == $mejor_match['ancho']) {
            $destacar = "class='highlight'";
        }
        echo "<tr $destacar>";
        echo "<td>{$fila['ancho']}</td>";
        echo "<td>{$fila['alto']}</td>";
        echo "<td>{$fila['area']}</td>";
        echo "<td>$" . number_format($fila['naturalc'], 2) . "</td>";
        echo "<td>$" . number_format($fila['basico'], 2) . "</td>";
        echo "<td>$" . number_format($fila['especial'], 2) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-warning mt-4'>No se pudieron recuperar las medidas desde la base de datos.</div>";
}
}
    }
?>

</body>
</html>
