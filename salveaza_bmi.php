<?php
// salveaza_bmi.php – Primeste datele din bmi.html si le salveaza in MySQL
// Laborator 4 – PHP + MySQL

// Acceptam doar cereri POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: bmi.html');
    exit;
}

require 'db.php';

// ---- Cream tabelul daca nu exista inca ----
$sql_creare = "CREATE TABLE IF NOT EXISTS calcule_bmi (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nume        VARCHAR(100)   NOT NULL,
    greutate    FLOAT          NOT NULL,
    inaltime    FLOAT          NOT NULL,
    varsta      INT            NOT NULL,
    sex         VARCHAR(20)    NOT NULL,
    bmi         FLOAT          NOT NULL,
    categorie   VARCHAR(50)    NOT NULL,
    data_calcul DATETIME       DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql_creare)) {
    die("Eroare la creare tabel: " . $conn->error);
}

// ---- Preluam si validam datele din formular ----
$nume     = trim($_POST['nume']     ?? '');
$greutate = floatval($_POST['greutate'] ?? 0);
$inaltime = floatval($_POST['inaltime'] ?? 0);
$varsta   = intval($_POST['varsta']     ?? 0);
$sex      = $_POST['sex'] ?? '';

$erori = [];

if (empty($nume))                          $erori[] = "Numele este obligatoriu.";
if ($greutate < 20 || $greutate > 300)     $erori[] = "Greutatea trebuie să fie între 20–300 kg.";
if ($inaltime < 100 || $inaltime > 250)    $erori[] = "Înălțimea trebuie să fie între 100–250 cm.";
if ($varsta < 10 || $varsta > 100)         $erori[] = "Vârsta trebuie să fie între 10–100 ani.";
if (!in_array($sex, ['masculin','feminin'])) $erori[] = "Sexul este obligatoriu.";

if (!empty($erori)) {
    // Redirectam inapoi cu erorile ca parametri GET
    $mesaj = urlencode(implode(' | ', $erori));
    header("Location: bmi.html?eroare=" . $mesaj);
    exit;
}

// ---- Calculam BMI ----
$inaltimeMetri = $inaltime / 100;
$bmi           = round($greutate / ($inaltimeMetri * $inaltimeMetri), 1);

if ($bmi < 18.5)      $categorie = "Subponderal";
elseif ($bmi < 25)    $categorie = "Greutate normală";
elseif ($bmi < 30)    $categorie = "Supraponderal";
else                  $categorie = "Obezitate";

// ---- Salvam in baza de date cu prepared statement (protectie SQL injection) ----
$stmt = $conn->prepare(
    "INSERT INTO calcule_bmi (nume, greutate, inaltime, varsta, sex, bmi, categorie)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
// Tipuri: s=string, d=double(float), i=int
$stmt->bind_param("sddisds", $nume, $greutate, $inaltime, $varsta, $sex, $bmi, $categorie);

if ($stmt->execute()) {
    // Salvare reusita – redirectam la pagina de istoric cu mesaj de succes
    header("Location: bmi.html?succes=1&nume=" . urlencode($nume) . "&bmi=" . $bmi . "&categorie=" . urlencode($categorie));
} else {
    header("Location: bmi.html?eroare=" . urlencode("Eroare la salvare: " . $stmt->error));
}

$stmt->close();
$conn->close();
?>
