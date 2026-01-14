<?php
require_once __DIR__ . '/includes/db.php';
$users = $pdo->query("SELECT * FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smash Select — Portfolio</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script defer src="assets/js/select_v2.js"></script>
</head>
<body class="smash-select">

<header class="site-header">
  <div class="brand">Select your Developer</div>
  <nav><a href="admin/login.php" class="btn">Admin</a></nav>
</header>

<main class="select-screen">
  <section class="top-row">
    <?php foreach ($users as $u): ?>
      <div class="mini-card" data-id="<?= (int)$u['id'] ?>">
        <img src="assets/img/<?= htmlspecialchars($u['picture'] ?: 'default.png') ?>" alt="<?= htmlspecialchars($u['first_name']) ?>">
      </div>
    <?php endforeach; ?>
  </section>

  <section class="bottom-row">
    <div id="selectedCard" class="profile-card-large"></div>
    <a id="viewProfileBtn" class="btn" href="#" style="display:none;">Voir le profil</a>
  </section>
</main>

<footer class="site-footer">© 2025 — Portfolio EPITECH</footer>
<audio id="clickSound" src="assets/sfx/select.wav" preload="auto"></audio>
<script defer src="assets/js/select_v2.js"></script>

</body>
</html>
