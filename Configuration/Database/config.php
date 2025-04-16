<?php
// Lire le fichier .env
$env = parse_ini_file(__DIR__ . '/../.env');

// Récupérer les variables
$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];

// Connexion PDO
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
