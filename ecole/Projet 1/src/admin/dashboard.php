<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../includes/db.php';
if (!headers_sent()) { header('Content-Type: text/html; charset=UTF-8'); }

$users = $pdo->query("SELECT * FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$experiences = $pdo->query("SELECT * FROM experiences ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$education = $pdo->query("SELECT * FROM education ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administration — Portfolio</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin">
  <div class="admin-container">
    <div class="admin-header">
      <h1>Panneau d’administration</h1>
      <a href="logout.php" class="btn btn-logout">Se déconnecter</a>
    </div>
    <p><a class="btn" href="../index.php">← Retour au site</a></p>

    <!-- ===================== UTILISATEURS ===================== -->
    <section>
      <h2 class="section-title">Utilisateurs</h2>
      <p><a class="btn" href="add_user.php">+ Ajouter un utilisateur</a></p>
      <table>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Actions</th></tr>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone'] ?? '') ?></td>
            <td class="actions">
              <a href="edit_user.php?id=<?= (int)$u['id'] ?>" class="btn">Modifier</a>
              <a href="delete_user.php?id=<?= (int)$u['id'] ?>" class="btn" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </section>

    <!-- ===================== EXPERIENCES ===================== -->
    <section>
      <h2 class="section-title">Expériences</h2>
      <p><a class="btn" href="experiences/add_experience.php">+ Ajouter une expérience</a></p>
      <table>
        <tr><th>ID</th><th>Titre</th><th>Entreprise</th><th>Période</th><th>User ID</th><th>Actions</th></tr>
        <?php foreach ($experiences as $e): ?>
          <tr>
            <td><?= (int)$e['id'] ?></td>
            <td><?= htmlspecialchars($e['title']) ?></td>
            <td><?= htmlspecialchars($e['company']) ?></td>
            <td><?= htmlspecialchars($e['start_date']) ?> → <?= htmlspecialchars($e['end_date'] ?? 'présent') ?></td>
            <td><?= (int)$e['user_id'] ?></td>
            <td class="actions">
              <a href="experiences/edit_experience.php?id=<?= (int)$e['id'] ?>" class="btn">Modifier</a>
              <a href="experiences/delete_experience.php?id=<?= (int)$e['id'] ?>" class="btn" onclick="return confirm('Supprimer cette expérience ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </section>

    <!-- ===================== FORMATIONS ===================== -->
    <section>
      <h2 class="section-title">Formations</h2>
      <p><a class="btn" href="education/add_education.php">+ Ajouter une formation</a></p>
      <table>
        <tr><th>ID</th><th>Diplôme</th><th>École</th><th>Période</th><th>User ID</th><th>Actions</th></tr>
        <?php foreach ($education as $ed): ?>
          <tr>
            <td><?= (int)$ed['id'] ?></td>
            <td><?= htmlspecialchars($ed['degree']) ?></td>
            <td><?= htmlspecialchars($ed['school']) ?></td>
            <td><?= htmlspecialchars($ed['start_date']) ?> → <?= htmlspecialchars($ed['end_date'] ?? 'présent') ?></td>
            <td><?= (int)$ed['user_id'] ?></td>
            <td class="actions">
              <a href="education/edit_education.php?id=<?= (int)$ed['id'] ?>" class="btn">Modifier</a>
              <a href="education/delete_education.php?id=<?= (int)$ed['id'] ?>" class="btn" onclick="return confirm('Supprimer cette formation ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </section>

    <!-- ===================== COMPÉTENCES ===================== -->
    <section>
      <h2 class="section-title">Compétences</h2>
      <p><a class="btn" href="skills/add_skill.php">+ Ajouter une compétence</a></p>
      <table>
        <tr><th>ID</th><th>Nom</th><th>Niveau</th><th>User ID</th><th>Actions</th></tr>
        <?php foreach ($skills as $s): ?>
          <tr>
            <td><?= (int)$s['id'] ?></td>
            <td><?= htmlspecialchars($s['skill_name']) ?></td>
            <td><?= htmlspecialchars($s['level']) ?></td>
            <td><?= (int)$s['user_id'] ?></td>
            <td class="actions">
              <a href="skills/edit_skill.php?id=<?= (int)$s['id'] ?>" class="btn">Modifier</a>
              <a href="skills/delete_skill.php?id=<?= (int)$s['id'] ?>" class="btn" onclick="return confirm('Supprimer cette compétence ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </section>

    <footer>
      <p style="margin-top:3rem;text-align:center;color:#999;">
        © 2025 — Administration Portfolio Fullstack EPITECH
      </p>
    </footer>
  </div>
</body>
</html>
