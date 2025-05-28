<?php include 'includes/header.php'; ?>

<main>
<header class="hero">
    <div class="hero-content">
        <h1>Bienvenue sur RallyePéÏ</h1>
        <a href="../Site/galerie.php" class="cta-button">Découvrir</a>
    </div>
</header>

<div class="conteneur">
    <h2 class="entête">Découvrez l'univers palpitant du rallye automobile</h2>
    <div class="grille">
        <!-- Performances -->
        <a href="galerie.php" class="carte carte-large orange">
            <div class="contenu">
                <span class="numéro">01</span>
                <h3>PERFORMANCES</h3>
                <p>Des voitures exceptionnelles pour des courses palpitantes.</p>
            </div>
            <img src="../Contenus/Image/diapo (6).jpg" alt="Voiture de sport">
        </a>

        <!-- Compétitions -->
        <a href="actualite.php" class="carte bleu-foncé">
            <span class="numéro">02</span>
            <h3>COMPÉTITIONS</h3>
            <p>Les plus grands événements de rallye.</p>
        </a>

        <!-- Passion -->
        <a href="https://www.facebook.com/PixelRallye/?locale=fr_FR" target="_blank" class="carte bleu-moyen">
            <span class="numéro">03</span>
            <h3>PASSION</h3>
            <p>Une communauté passionnée par le rallye.</p>
            <img src="../Contenus/Image/img-grille2.jpg" alt="Voiture de course noire" class="image-carte">
        </a>

        <!-- Circuit -->
        <a href="https://lsar.live/" target="_blank" class="carte gris-clair">
            <span class="numéro">04</span>
            <h3>CIRCUIT</h3>
            <p>Des circuits emblématiques à travers l'île de La Réunion.</p>
        </a>

        <!-- Adhésion -->
        <a href="contact.php" class="carte bleu-moyen">
            <span class="numéro">05</span>
            <h3>CONTACTEZ-NOUS</h3>
            <p>Des questions? Besoin d'informations? Contactez-nous</p>
        </a>
    </div>
</div>

<!-- <div class="gallery">
    <img src="../Contenus/Image/diapo (1).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (2).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (3).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (4).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (5).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (6).jpg" alt="Diapo">
    <img src="../Contenus/Image/diapo (7).jpg" alt="Diapo">
</div> -->

<section id="ma-section-video">
    <video controls loop>
        <source src="../Contenus/Vidéo/rallyejeanpetit.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</section>
</main>

<?php include 'includes/footer.php'; ?>

<!-- Script JS du site -->
<script src="javascript/script.js"></script>
</body>
</html>
