<?php include 'includes/header.php'; ?>
<?php 
// Connexion à la base de données
require_once '../Configuration/Database/config.php'; 

// Requête pour récupérer les actualités
$sql = "SELECT * FROM actualites ORDER BY date_publication DESC";
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Actualités</h1>

<!-- Actualité en vedette -->
<section class="actualite-en-vedette">
<img src="../Contenus/Image/Img_de_galerie (5).jpg" alt="Image à la une du Rallye">
</section>

<!-- Compte à rebours -->
<div id="compte-a-rebours">
    <p>Prochain événement dans <span id="temps"></span></p>
</div>

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

<?php include 'includes/footer.php'; ?>

<!-- Script JS du compte à rebours + nav -->
<script src="javascript/script.js"></script>
</body>
</html>
