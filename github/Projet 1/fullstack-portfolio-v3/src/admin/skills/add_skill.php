<?php
require_once __DIR__ . '/../_guard.php';
require_once __DIR__ . '/../../includes/db.php';
if (!headers_sent()) { header('Content-Type: text/html; charset=UTF-8'); }

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Jeton CSRF invalide.";
    }

    $skill_name = trim($_POST['skill_name'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $user_id = (int)($_POST['user_id'] ?? 0);

    if ($skill_name === '') {
        $errors[] = "Le nom de la compétence est requis.";
    }

    if (!$errors) {
        $st = $pdo->prepare("INSERT INTO skills (user_id, skill_name, level) VALUES (?, ?, ?)");
        $st->execute([$user_id, $skill_name, $level]);
        $success = true;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter une compétence</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="admin">
  <div class="admin-container">
    <div class="admin-header">
      <h1>⚙️ Ajouter une compétence</h1>
      <a href="../dashboard.php" class="btn">← Retour</a>
    </div>

    <?php if ($success): ?>
      <div class="alert success">✅ Compétence ajoutée avec succès.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="alert error">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="admin-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="form-group">
        <label>Nom de la compétence</label>
        <input type="text" name="skill_name" required placeholder="Ex : HTML, Python, Photoshop...">
      </div>

      <div class="form-group">
        <label>Niveau (optionnel)</label>
        <input type="text" name="level" placeholder="Ex : Débutant, Avancé, Expert...">
      </div>

      <div class="form-group">
        <label>ID utilisateur</label>
        <input type="number" name="user_id" min="1" required placeholder="Ex : 1">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn gold">➕ Ajouter</button>
        <a href="../dashboard.php" class="btn">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
