<?php
// 🧩 Chargement des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/PHPMailer-master/src/Exception.php';

// 🔌 Chargement des infos de config (BDD + identifiants Gmail)
require_once '../Configuration/Database/config.php';

// 🔄 Initialisation des variables
$success = false;
$errors = [];
$name = '';
$email = '';
$message = '';

// 📩 Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // ✨ Récupération des champs
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $message = trim($_POST["message"]);
  $honeypot = trim($_POST["website"]); // champ invisible (anti-bot)

  // 🛡️ Si le champ caché est rempli → robot → on bloque
  if (!empty($honeypot)) exit;

  // ✅ Validation des champs
  if (empty($name)) $errors[] = "Le nom est requis.";
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
  if (empty($message)) $errors[] = "Le message est requis.";

  // 📬 Si aucun problème, on envoie l'e-mail
  if (empty($errors)) {
    $mail = new PHPMailer(true);
    try {
      // ✉️ Configuration SMTP (envoi via Gmail)
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = SMTP_USER;
      $mail->Password = SMTP_PASS;
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // 📤 Infos du message
      $mail->setFrom($email, $name);
      $mail->addAddress('tonemail@gmail.com'); // ton adresse de réception

      $mail->isHTML(true);
      $mail->Subject = '📩 Nouveau message depuis le site RallyePéÏ';
      $mail->Body = "<b>Nom :</b> " . htmlspecialchars($name) .
                    "<br><b>Email :</b> " . htmlspecialchars($email) .
                    "<br><b>Message :</b><br>" . nl2br(htmlspecialchars($message));

      // 📨 Envoi du mail
      $mail->send();
      $success = true;

      // 💾 Enregistrement dans la base de données
      $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
      $stmt->execute([$name, $email, $message]);
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
      <!-- champ caché anti-spam -->
      <input type="text" name="website" style="display:none">

      <div class="groupe-formulaire">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name) ?>" autocomplete="name">
      </div>

      <div class="groupe-formulaire">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>" autocomplete="email">
      </div>

      <div class="groupe-formulaire">
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
      </div>

      <div class="button-container">
        <button class="btn-envoyer" type="submit">Envoyer</button>
      </div>
    </form>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
