<?php 
// Connexion à la base de données
require_once 'config.php';  // Ce fichier contient l'objet $pdo
include '../header.php';    // Header déjà inclus (doctype, html, head, etc.)

// Requête pour récupérer les actualités
$sql = "SELECT * FROM actualites ORDER BY date_publication DESC";
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Actualités</h1>

<section class="actualites">
  <?php foreach ($actualites as $actu): ?>
    <article>
      <h3><?= htmlspecialchars($actu['titre']) ?></h3>
      <p class="date">
        Publié le <?= date("d/m/Y", strtotime($actu['date_publication'])) ?>
      </p>

      <?php if (!empty($actu['image_url'])): ?>
        <img src="<?= htmlspecialchars($actu['image_url']) ?>" alt="Image actu">
      <?php endif; ?>

      <p><?= nl2br(htmlspecialchars($actu['contenu'])) ?></p>

      <?php if (!empty($actu['pdf_url'])): ?>
        <a href="<?= htmlspecialchars($actu['pdf_url']) ?>" target="_blank">📄 Télécharger le PDF</a>
      <?php endif; ?>
    </article>
  <?php endforeach; ?>
</section>

<?php include '../footer.php'; ?>
