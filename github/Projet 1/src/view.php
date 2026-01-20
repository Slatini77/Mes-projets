<?php
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id <= 0) {
  echo "<p>Profil introuvable.</p>";
  include __DIR__ . '/includes/footer.php';
  exit;
}

$u = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$u->execute([$user_id]);
$user = $u->fetch(PDO::FETCH_ASSOC);
if (!$user) {
  echo "<p>Profil introuvable.</p>";
  include __DIR__ . '/includes/footer.php';
  exit;
}

function fetchData($pdo, $table, $user_id) {
  $columns = $pdo->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_COLUMN);
  $order = in_array('start_date', $columns) ? 'ORDER BY start_date DESC' : '';
  $stmt = $pdo->prepare("SELECT * FROM $table WHERE user_id=? $order");
  $stmt->execute([$user_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$experiences = fetchData($pdo, 'experiences', $user_id);
$education   = fetchData($pdo, 'education', $user_id);
$skills      = fetchData($pdo, 'skills', $user_id);
?>

<main class="view-smash">

  <!-- === PROFIL === -->
  <section class="profile">
    <div class="profile-pic-wrap">
      <img src="assets/img/<?= htmlspecialchars($user['picture'] ?: 'default.png') ?>" alt="photo de profil">
    </div>
    <div class="profile-info">
      <h1><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
      <p><a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></p>
      <?php if (!empty($user['phone'])): ?>
        <p><a href="tel:<?= htmlspecialchars($user['phone']) ?>"><?= htmlspecialchars($user['phone']) ?></a></p>
      <?php endif; ?>
      <p class="bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
    </div>
  </section>

  <!-- === TIMELINE EXPÉRIENCES === -->
  <section class="timeline-section">
    <h2>Expériences</h2>
    <?php if (!$experiences): ?>
      <p>Aucune expérience enregistrée.</p>
    <?php else: ?>
      <ul class="timeline">
        <?php foreach ($experiences as $e): ?>
          <li class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h3><?= htmlspecialchars($e['title']) ?></h3>
              <span class="org"><?= htmlspecialchars($e['company']) ?></span><br>
              <small><?= htmlspecialchars($e['start_date']) ?> → <?= htmlspecialchars($e['end_date'] ?: 'Présent') ?></small>
              <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <!-- === ÉDUCATION === -->
  <section class="accordion">
    <h2 class="accordion-title">Éducation</h2>
    <div class="accordion-content">
      <?php if (!$education): ?>
        <p>Aucune formation.</p>
      <?php else: ?>
        <?php foreach ($education as $e): ?>
          <div class="card-line">
            <div class="card-title">
              <?= htmlspecialchars($e['degree']) ?> — <span><?= htmlspecialchars($e['school']) ?></span>
            </div>
            <div class="card-dates">
              <?= htmlspecialchars($e['start_date']) ?> → <?= htmlspecialchars($e['end_date'] ?: 'Présent') ?>
            </div>
            <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- === COMPÉTENCES === -->
  <section class="accordion">
    <h2 class="accordion-title">Compétences</h2>
    <div class="accordion-content">
      <?php if (!$skills): ?>
        <p>Aucune compétence enregistrée.</p>
      <?php else: ?>
        <ul class="skills-grid">
          <?php foreach ($skills as $s): ?>
            <li>
              <strong><?= htmlspecialchars($s['skill_name']) ?></strong> — <?= htmlspecialchars($s['level']) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </section>

  <!-- === BOUTON RETOUR === -->
  <div class="back-btn">
    <a href="index.php" class="btn gold">← Retour au Sélecteur</a>
  </div>

</main>

<script>
document.querySelectorAll('.accordion-title').forEach(title => {
  title.addEventListener('click', () => {
    const content = title.nextElementSibling;
    const isOpen = title.classList.contains('active');

    document.querySelectorAll('.accordion-title').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.accordion-content').forEach(c => c.style.maxHeight = null);

    if (!isOpen) {
      title.classList.add('active');
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
