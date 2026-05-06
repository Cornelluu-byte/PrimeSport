<?php
// db.php – Conexiunea la baza de date MySQL
// Laborator 4 – Modifica valorile de mai jos dupa configuratia ta XAMPP/WAMP

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // utilizatorul implicit in XAMPP/WAMP
define('DB_PASS', '');           // parola implicita in XAMPP/WAMP e goala
define('DB_NAME', 'arena_x');

// Cream conexiunea
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificam conexiunea
if ($conn->connect_error) {
    die("<p style='color:red; font-family:Arial; padding:20px;'>
        ❌ Conexiune eșuată la baza de date: " . $conn->connect_error . "
        <br><br>Asigură-te că ai creat baza de date <strong>arena_x</strong> în phpMyAdmin.
    </p>");
}

// Setam codificarea UTF-8 pentru caractere speciale (ș, ț, ă etc.)
$conn->set_charset("utf8");
?>
