<?php
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Fullstack Portfolio</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script defer src="assets/js/main.js"></script>

  <link rel="icon" href="assets/img/favicon.png" type="image/png">
</head>

<body>
  <header class="site-header">
    <div class="brand">Fullstack Portfolio</div>
    <nav>
      <a href="index.php">Accueil</a> · 
      <a href="admin/login.php">Admin</a>
    </nav>
  </header>

  <main class="container">
