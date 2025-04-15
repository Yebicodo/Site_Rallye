<?php include 'includes/header.php'; ?>
<?php 
// Connexion à la base de données
require_once '../Configuration/Database/config.php'; 

// Requête pour récupérer les actualités
$sql = "SELECT * FROM actualites ORDER BY date_publication DESC";
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="actualite-en-vedette">
    <img src="../Contenus/Image/Img_de_galerie (8).jpg" alt="Image à la une du Rallye">
</section>

<h1 class="titre-actualites">Actualités</h1>

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
        <img src="../Contenus/Image/<?= htmlspecialchars($actu['image_url']) ?>" alt="Image actu">
      <?php endif; ?>
      
      <p><?= nl2br(htmlspecialchars($actu['contenu'])) ?></p>

      <?php if (!empty($actu['pdf_url'])): ?>
        <a href="../Contenus/PDF/<?= htmlspecialchars($actu['pdf_url']) ?>" target="_blank">📄 Télécharger le PDF</a>
      <?php endif; ?>
    </article>
  <?php endforeach; ?>
</section>

<?php include 'includes/footer.php'; ?>

<script src="javascript/script.js"></script>
</body>
</html>
