<?php
require_once __DIR__ . '/../_guard.php';
require_once __DIR__ . '/../../includes/db.php';
if (!headers_sent()) { header('Content-Type: text/html; charset=UTF-8'); }

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ../dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM education WHERE id = ?");
$stmt->execute([$id]);
$edu = $stmt->fetch();
if (!$edu) { echo "Formation introuvable."; exit; }

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Jeton CSRF invalide.";
    }

    $degree = trim($_POST['degree'] ?? '');
    $school = trim($_POST['school'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $user_id = (int)($_POST['user_id'] ?? 0);

    if ($degree === '' || $school === '') {
        $errors[] = "Diplôme et école requis.";
    }

    if (!$errors) {
        $upd = $pdo->prepare("
            UPDATE education 
               SET degree=?, school=?, start_date=?, end_date=?, description=?, user_id=? 
             WHERE id=?
        ");
        $upd->execute([$degree, $school, $start_date, $end_date, $description, $user_id, $id]);
        $success = true;

        $stmt->execute([$id]);
        $edu = $stmt->fetch();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier une formation</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="admin">
  <div class="admin-container">
    <div class="admin-header">
      <h1>Modifier la formation #<?= htmlspecialchars($edu['id']) ?></h1>
      <a href="../dashboard.php" class="btn">← Retour</a>
    </div>

    <?php if ($success): ?>
      <div class="alert success">✅ Formation mise à jour avec succès.</div>
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
        <label>Diplôme / Titre</label>
        <input type="text" name="degree" required value="<?= htmlspecialchars($edu['degree']) ?>">
      </div>

      <div class="form-group">
        <label>École / Université</label>
        <input type="text" name="school" required value="<?= htmlspecialchars($edu['school']) ?>">
      </div>

      <div class="form-group grid-2">
        <div>
          <label>Date de début</label>
          <input type="date" name="start_date" value="<?= htmlspecialchars($edu['start_date']) ?>">
        </div>
        <div>
          <label>Date de fin</label>
          <input type="date" name="end_date" value="<?= htmlspecialchars($edu['end_date']) ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($edu['description']) ?></textarea>
      </div>

      <div class="form-group">
        <label>ID utilisateur</label>
        <input type="number" name="user_id" min="1" required value="<?= htmlspecialchars($edu['user_id']) ?>">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn gold">Enregistrer</button>
        <a href="../dashboard.php" class="btn">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
