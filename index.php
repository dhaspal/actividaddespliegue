<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Laboratorio Linux</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #0D1117; color: #e6edf3; padding: 40px; }
    h1 { color: #2E75B6; margin-bottom: 8px; }
    p.sub { color: #8B949E; margin-bottom: 32px; }
    .card { background: #161B22; border: 1px solid #30363D; border-radius: 8px; padding: 24px; margin-bottom: 24px; }
    h2 { color: #58A6FF; margin-bottom: 16px; font-size: 16px; }
    form input, form textarea {
      width: 100%; padding: 10px; margin-bottom: 12px;
      background: #21262D; border: 1px solid #30363D;
      border-radius: 6px; color: #e6edf3; font-size: 14px;
    }
    form button {
      background: #2E75B6; color: white; border: none;
      padding: 10px 24px; border-radius: 6px; cursor: pointer; font-size: 14px;
    }
    form button:hover { background: #1F4E79; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { background: #1F4E79; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #21262D; color: #8B949E; }
    tr:hover td { background: #21262D; }
    .msg-ok  { color: #30D158; margin-bottom: 16px; font-size: 14px; }
    .msg-err { color: #FF3B30; margin-bottom: 16px; font-size: 14px; }
    .badge { background: #2E75B6; color: white; border-radius: 12px; padding: 2px 10px; font-size: 12px; }
  </style>
</head>
<body>

<h1>Laboratorio Linux</h1>
<p class="sub">WSL + NGINX + PHP 8.3 + MySQL — Despliegue local</p>

<?php
$mensaje_estado = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $correo  = trim($_POST['correo']  ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    if ($nombre && $correo) {
        $stmt = $pdo->prepare("INSERT INTO registros (nombre, correo, mensaje) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $mensaje]);
        $mensaje_estado = '<p class="msg-ok">✅ Registro guardado correctamente.</p>';
    } else {
        $mensaje_estado = '<p class="msg-err">❌ Nombre y correo son obligatorios.</p>';
    }
}
?>

<div class="card">
  <h2>Nuevo Registro PARA TODO</h2>
  <?= $mensaje_estado ?>
  <form method="POST">
    <input type="text"  name="nombre"  placeholder="Nombre completo" required>
    <input type="email" name="correo"  placeholder="Correo electrónico" required>
    <textarea name="mensaje" rows="3"  placeholder="Mensaje (opcional)"></textarea>
    <button type="submit">Guardar</button>
  </form>
</div>

<div class="card">
  <h2> pase pues <span class="badge">laboratorio_db</span></h2>
  <table>
    <thead>
      <tr><th>#</th><th>Nombre</th><th>Correo</th><th>Mensaje</th><th>Fecha</th></tr>
    </thead>
    <tbody>
      <?php
        $rows = $pdo->query("SELECT * FROM registros ORDER BY fecha DESC")->fetchAll();
        if (count($rows) === 0) {
            echo '<tr><td colspan="5" style="text-align:center;color:#555">Sin registros</td></tr>';
        }
        foreach ($rows as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['nombre']) ?></td>
          <td><?= htmlspecialchars($r['correo']) ?></td>
          <td><?= htmlspecialchars($r['mensaje']) ?></td>
          <td><?= $r['fecha'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
