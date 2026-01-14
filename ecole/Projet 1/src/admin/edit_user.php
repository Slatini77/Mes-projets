<?php
require_once __DIR__ . '/_guard.php';
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, bio, picture FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    http_response_code(404);
    echo "Utilisateur introuvable.";
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Jeton CSRF invalide. Veuillez réessayer.";
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name']  ?? '');
    $email      = trim($_POST['email']      ?? '');
    $phone_raw  = trim($_POST['phone']      ?? '');
    $bio        = trim($_POST['bio']        ?? '');

    $phone_clean = preg_replace('/[^0-9+]/', '', $phone_raw);

    if ($first_name === '') $errors[] = "Le prénom est requis.";
    if ($last_name  === '') $errors[] = "Le nom est requis.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (mb_strlen($first_name) > 100) $errors[] = "Prénom trop long.";
    if (mb_strlen($last_name)  > 100) $errors[] = "Nom trop long.";
    if (mb_strlen($email)      > 190) $errors[] = "Email trop long.";
    if (mb_strlen($phone_clean)> 30)  $errors[] = "Téléphone trop long.";
    if (mb_strlen($bio)        > 2000)$errors[] = "Bio trop longue (2000 caractères max).";

    $newPictureName = $user['picture'];
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['picture']['tmp_name'];
            $name = $_FILES['picture']['name'];

            $allowedExt = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                $errors[] = "Format d'image non supporté (jpg, jpeg, png, gif, webp).";
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $tmp);
                finfo_close($finfo);

                $allowedMime = ['image/jpeg','image/png','image/gif','image/webp'];
                if (!in_array($mime, $allowedMime, true)) {
                    $errors[] = "Le fichier envoyé n'est pas une image valide.";
                } else {
                    $newPictureName = 'user_'.$user['id'].'_'.bin2hex(random_bytes(6)).'.'.$ext;

                    $destDir  = __DIR__ . '/../assets/img';
                    if (!is_dir($destDir)) {
                        @mkdir($destDir, 0775, true);
                    }
                    $destPath = $destDir . '/' . $newPictureName;

                    if (!move_uploaded_file($tmp, $destPath)) {
                        $errors[] = "Échec d'upload de l'image.";
                        $newPictureName = $user['picture'];
                    }
                }
            }
        } else {
            $errors[] = "Erreur d'upload (code: ".$_FILES['picture']['error'].").";
        }
    }

    if (!$errors) {
        $upd = $pdo->prepare("
            UPDATE users
               SET first_name = ?, last_name = ?, email = ?, phone = ?, bio = ?, picture = ?
             WHERE id = ?
        ");
        $upd->execute([
            $first_name,
            $last_name,
            $email,
            $phone_clean,
            $bio,
            $newPictureName,
            $user_id
        ]);

        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, bio, picture FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        $success = true;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier un utilisateur</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin">
  <div class="admin-container">
    <div class="admin-header">
      <h1>Modifier l’utilisateur #<?= htmlspecialchars($user['id']) ?></h1>
      <a href="dashboard.php" class="btn">← Retour</a>
    </div>

    <?php if ($success): ?>
      <div class="alert success">✅ Profil mis à jour avec succès.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="alert error">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="first_name" required maxlength="100" value="<?= htmlspecialchars($user['first_name']) ?>">
      </div>

      <div class="form-group">
        <label>Nom</label>
        <input type="text" name="last_name" required maxlength="100" value="<?= htmlspecialchars($user['last_name']) ?>">
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required maxlength="190" value="<?= htmlspecialchars($user['email']) ?>">
      </div>

      <div class="form-group">
        <label>Téléphone</label>
        <input type="text" name="phone" maxlength="30" value="<?= htmlspecialchars($user['phone']) ?>">
      </div>

      <div class="form-group">
        <label>Bio</label>
        <textarea name="bio" rows="5" maxlength="2000"><?= htmlspecialchars($user['bio']) ?></textarea>
      </div>

      <div class="form-group">
        <label>Photo (optionnel)</label>
        <?php if (!empty($user['picture'])): ?>
          <div class="current-picture">
            <img src="../assets/img/<?= htmlspecialchars($user['picture']) ?>" alt="Photo actuelle" class="preview">
          </div>
        <?php endif; ?>
        <input type="file" name="picture" accept="image/*">
        <small>Formats acceptés : jpg, jpeg, png, gif, webp.</small>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn gold">Enregistrer</button>
        <a href="dashboard.php" class="btn">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
