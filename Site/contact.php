<?php
// 🛡️ Initialisation de la session pour le token CSRF
session_start();

// 📦 Chargement des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/PHPMailer-master/src/Exception.php';

// 🔌 Chargement des infos de config (BDD + identifiants Gmail) + mp dans .env
require_once __DIR__ . '/../config.php';


// 🔄 Initialisation des variables
$success = false;
$errors = [];
$name = '';
$email = '';
$message = '';

// 🔐 Génération du token CSRF (protection contre les attaques intersites)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 📩 Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 🛡️ Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit("Erreur de sécurité : token invalide");
    }

    // ✨ Récupération et nettoyage des champs
    $name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8');

    // ✅ Validation des champs
    if (empty($name)) $errors[] = "Le nom est requis.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (empty($message)) $errors[] = "Le message est requis.";

    // 📏 Validation de la longueur des champs
    if (strlen($name) > 100) $errors[] = "Le nom est trop long (max 100 caractères)";
    if (strlen($email) > 255) $errors[] = "Email trop long (max 255 caractères)";
    if (strlen($message) > 2000) $errors[] = "Message trop long (max 2000 caractères)";

    // 📬 Si aucun problème, on envoie l'e-mail
    if (empty($errors)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(SMTP_USER, 'Formulaire RallyePéÏ');
            $mail->addReplyTo($email, $name);
            $mail->addAddress(CONTACT_RECEIVER);

            $mail->isHTML(true);
            $mail->Subject = '📩 Nouveau message depuis le site RallyePéÏ';
            $mail->Body = "<b>Nom :</b> " . htmlspecialchars($name) .
                          "<br><b>Email :</b> " . htmlspecialchars($email) .
                          "<br><b>Message :</b><br>" . nl2br(htmlspecialchars($message));

            $mail->send();
            $success = true;

            try {
                $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $message]);
            } catch (PDOException $e) {
                error_log("Erreur BDD: " . $e->getMessage());
            }

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        } catch (Exception $e) {
            $errors[] = "Erreur d'envoi : " . $mail->ErrorInfo;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<!-- 🌄 Fond et formulaire -->
<section class="background-image">
  <div class="formulaire-contact">
    <h2>Contactez-nous</h2>

    <!-- ✅ Message de succès -->
    <?php if ($success): ?>
      <p class="success">✅ Votre message a été envoyé avec succès !</p>

    <!-- ❌ Liste des erreurs -->
    <?php elseif (!empty($errors)): ?>
      <ul class="errors">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- 📝 Formulaire de contact -->
    <form method="post" action="contact.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="groupe-formulaire">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required 
               value="<?= htmlspecialchars($name) ?>" 
               autocomplete="name"
               maxlength="100">
      </div>

      <div class="groupe-formulaire">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required 
               value="<?= htmlspecialchars($email) ?>" 
               autocomplete="email"
               maxlength="255">
      </div>

      <div class="groupe-formulaire">
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="5" required
                  maxlength="2000"><?= htmlspecialchars($message) ?></textarea>
      </div>

      <div class="button-container">
        <button class="btn-envoyer" type="submit">Envoyer</button>
      </div>
    </form>

    <!-- 🔙 Bouton retour -->
    <div class="button-container">
      <a href="index.php" class="btn-retour">← Retour à l'accueil</a>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="javascript/script.js"></script>
</body>
</html>

