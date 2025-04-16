<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/PHPMailer-master/src/Exception.php';

require_once '../Configuration/Database/config.php';



$success = false;
$errors = [];

$name = '';
$email = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $message = trim($_POST["message"]);
  $honeypot = trim($_POST["website"]);

  if (!empty($honeypot)) exit;

  if (empty($name)) $errors[] = "Le nom est requis.";
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
  if (empty($message)) $errors[] = "Le message est requis.";

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

      $mail->setFrom($email, $name);
      $mail->addAddress('tonemail@gmail.com');

      $mail->isHTML(true);
      $mail->Subject = '📩 Nouveau message depuis le site RallyePéÏ';
      $mail->Body = "<b>Nom :</b> " . htmlspecialchars($name) .
                    "<br><b>Email :</b> " . htmlspecialchars($email) .
                    "<br><b>Message :</b><br>" . nl2br(htmlspecialchars($message));

      $mail->send();
      $success = true;

      $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
      $stmt->execute([$name, $email, $message]);
    } catch (Exception $e) {
      $errors[] = "Erreur d'envoi : " . $mail->ErrorInfo;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<body>
  <?php include 'includes/header.php'; ?>

  <section class="background-image">
    <div class="formulaire-contact">
      <h2>Contactez-nous</h2>

      <?php if ($success): ?>
        <p class="success">✅ Votre message a été envoyé avec succès !</p>
      <?php elseif (!empty($errors)): ?>
        <ul class="errors">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <form method="post" action="contact.php" novalidate>
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
</body>
</html>

