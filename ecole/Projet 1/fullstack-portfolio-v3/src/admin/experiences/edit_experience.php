<?php
require_once __DIR__ . '/../_guard.php';
require_once __DIR__ . '/../../includes/db.php';
if (!headers_sent()) { header('Content-Type: text/html; charset=UTF-8'); }

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ../dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
$stmt->execute([$id]);
$exp = $stmt->fetch();
if (!$exp) { echo "Expérience introuvable."; exit; }

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Jeton CSRF invalide.";
    }

    $title = trim($_POST['title'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $user_id = (int)($_POST['user_id'] ?? 0);

    if ($title === '' || $company === '') $errors[] = "Titre et entreprise requis.";

    if (!$errors) {
        $upd = $pdo->prepare("
            UPDATE experiences 
               SET title=?, company=?, start_date=?, end_date=?, description=?, user_id=? 
             WHERE id=?
        ");
        $upd->execute([$title, $company, $start_date, $end_date, $description, $user_id, $id]);
        $success = true;

        $stmt->execute([$id]);
        $exp = $stmt->fetch();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier une expérience</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="admin">
  <div class="admin-container">
    <div class="admin-header">
      <h1>Modifier l’expérience #<?= htmlspecialchars($exp['id']) ?></h1>
      <a href="../dashboard.php" class="btn">← Retour</a>
    </div>

    <?php if ($success): ?>
      <div class="alert success">✅ Expérience mise à jour avec succès.</div>
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
        <label>Titre du poste</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($exp['title']) ?>">
      </div>

      <div class="form-group">
        <label>Entreprise</label>
        <input type="text" name="company" required value="<?= htmlspecialchars($exp['company']) ?>">
      </div>

      <div class="form-group grid-2">
        <div>
          <label>Date de début</label>
          <input type="date" name="start_date" value="<?= htmlspecialchars($exp['start_date']) ?>">
        </div>
        <div>
          <label>Date de fin</label>
          <input type="date" name="end_date" value="<?= htmlspecialchars($exp['end_date']) ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($exp['description']) ?></textarea>
      </div>

      <div class="form-group">
        <label>ID utilisateur</label>
        <input type="number" name="user_id" min="1" required value="<?= htmlspecialchars($exp['user_id']) ?>">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn gold">Enregistrer</button>
        <a href="../dashboard.php" class="btn">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
