<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../includes/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $st = $pdo->prepare("INSERT INTO users (first_name,last_name,email,phone,bio,picture) VALUES (?,?,?,?,?,?)");
  $st->execute([$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['phone'] ?? '',$_POST['bio'] ?? '',$_POST['picture'] ?? '']);
  header('Location: dashboard.php'); exit;
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin — Ajouter</title>
<link rel="stylesheet" href="../assets/css/style.css"></head><body class="container">
<h1>Ajouter un utilisateur</h1>
<form method="post" class="section" style="max-width:600px">
  <label>Prénom<br><input name="first_name" required></label><br><br>
  <label>Nom<br><input name="last_name" required></label><br><br>
  <label>Email<br><input type="email" name="email" required></label><br><br>
  <label>Téléphone<br><input name="phone"></label><br><br>
  <label>Bio<br><textarea name="bio" rows="4"></textarea></label><br><br>
  <label>Picture (nom de fichier dans assets/img)<br><input name="picture"></label><br><br>
  <button class="btn" type="submit">Enregistrer</button>
  <a class="btn" href="dashboard.php">Annuler</a>
</form>
</body></html>
