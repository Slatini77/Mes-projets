<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $st = $pdo->prepare("SELECT * FROM admins WHERE username=?");
  $st->execute([$username]);
  $admin = $st->fetch(PDO::FETCH_ASSOC);
  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    header('Location: dashboard.php'); exit;
  } else {
    $error = 'Identifiants invalides';
  }
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin — Connexion</title>
<link rel="stylesheet" href="../assets/css/style.css"></head><body class="container">
<h1>Administration — Connexion</h1>
<?php if($error): ?><p style="color:#f77"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" class="section" style="max-width:420px">
  <label>Nom d'utilisateur<br><input name="username" required></label><br><br>
  <label>Mot de passe<br><input type="password" name="password" required></label><br><br>
  <button class="btn" type="submit">Se connecter</button>
</form>
<p><a href="../index.php">← Retour au site</a></p>
</body></html>
