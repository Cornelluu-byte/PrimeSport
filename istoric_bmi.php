<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Istoric BMI - ARENA X</title>
    <link rel="stylesheet" href="/CSS/bmi.css">
    <style>
        /* Stiluri suplimentare pentru tabelul de istoric */
        .tabel-istoric {
            width: 100%;
            max-width: 900px;
            margin: 30px auto;
            border-collapse: collapse;
            font-size: 14px;
        }
        .tabel-istoric th {
            background: #ff3c3c;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }
        .tabel-istoric td {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .tabel-istoric tr:hover td {
            background: rgba(255,255,255,0.05);
        }
        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge.subponderal  { background: rgba(0,150,255,0.3);   color: #69c8ff; }
        .badge.normal       { background: rgba(0,200,100,0.3);   color: #00e676; }
        .badge.supraponderal{ background: rgba(255,200,0,0.3);   color: #ffd740; }
        .badge.obez         { background: rgba(255,60,60,0.3);   color: #ff5252; }
        .mesaj-succes {
            background: rgba(0,200,100,0.2);
            border: 1px solid #00c864;
            padding: 15px 20px;
            border-radius: 12px;
            max-width: 900px;
            margin: 20px auto;
            text-align: center;
        }
        .gol {
            text-align: center;
            padding: 40px;
            opacity: 0.6;
            font-size: 18px;
        }
        .sterg-btn {
            padding: 5px 12px;
            background: rgba(255,60,60,0.2);
            border: 1px solid #ff3c3c;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }
        .sterg-btn:hover { background: #ff3c3c; }
    </style>
</head>
<body>

<h1 align="center">⚖️ CALCULATOR BMI</h1>

<p align="right">
    <a href="index.html"> Acasă</a> |
    <a href="fotbal.html"> Fotbal</a> |
    <a href="fitness.html"> Fitness</a> |
    <a href="bmi.html"> Calculator BMI</a> |
    <a href="istoric_bmi.php"> 📋 Istoric</a>
</p>
<hr>

<?php
require 'db.php';

// ---- Cream tabelul daca nu exista inca ----
$conn->query("CREATE TABLE IF NOT EXISTS calcule_bmi (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nume        VARCHAR(100)   NOT NULL,
    greutate    FLOAT          NOT NULL,
    inaltime    FLOAT          NOT NULL,
    varsta      INT            NOT NULL,
    sex         VARCHAR(20)    NOT NULL,
    bmi         FLOAT          NOT NULL,
    categorie   VARCHAR(50)    NOT NULL,
    data_calcul DATETIME       DEFAULT CURRENT_TIMESTAMP
)");

// ---- Stergere intrare (daca s-a apasat butonul Sterge) ----
if (isset($_GET['sterge']) && is_numeric($_GET['sterge'])) {
    $id_sterge = intval($_GET['sterge']);
    $stmt = $conn->prepare("DELETE FROM calcule_bmi WHERE id = ?");
    $stmt->bind_param("i", $id_sterge);
    $stmt->execute();
    $stmt->close();
    header("Location: istoric_bmi.php");
    exit;
}

// ---- Mesaj de succes dupa salvare ----
if (isset($_GET['succes']) && $_GET['succes'] == 1) {
    $numeAfisat = htmlspecialchars($_GET['nume'] ?? '');
    $bmiAfisat  = htmlspecialchars($_GET['bmi']  ?? '');
    $catAfisat  = htmlspecialchars($_GET['categorie'] ?? '');
    echo "<div class='mesaj-succes'>
        ✅ Calculul a fost salvat! &nbsp;|&nbsp;
        <strong>{$numeAfisat}</strong> &nbsp;|&nbsp;
        BMI: <strong>{$bmiAfisat}</strong> &nbsp;|&nbsp;
        Categorie: <strong>{$catAfisat}</strong>
    </div>";
}
?>

<h2 align="center">📋 Istoric Calcule BMI</h2>

<?php
// ---- Citim toate inregistrarile din baza de date ----
$rezultate = $conn->query("SELECT * FROM calcule_bmi ORDER BY data_calcul DESC");

if ($rezultate && $rezultate->num_rows > 0):
?>

<p align="center" style="opacity:0.7;">
    Total calcule salvate: <strong><?php echo $rezultate->num_rows; ?></strong>
</p>

<table class="tabel-istoric">
    <tr>
        <th>#</th>
        <th>Nume</th>
        <th>Greutate</th>
        <th>Înălțime</th>
        <th>Vârstă</th>
        <th>Sex</th>
        <th>BMI</th>
        <th>Categorie</th>
        <th>Data</th>
        <th>Acțiuni</th>
    </tr>

    <?php
    $nr = 1;
    while ($rand = $rezultate->fetch_assoc()):
        // Determinam clasa badge dupa categorie
        $clasa_badge = 'normal';
        if ($rand['categorie'] === 'Subponderal')   $clasa_badge = 'subponderal';
        if ($rand['categorie'] === 'Supraponderal') $clasa_badge = 'supraponderal';
        if ($rand['categorie'] === 'Obezitate')     $clasa_badge = 'obez';

        // Formatam data
        $data = date('d.m.Y H:i', strtotime($rand['data_calcul']));
    ?>
    <tr>
        <td><?php echo $nr++; ?></td>
        <td><strong><?php echo htmlspecialchars($rand['nume']); ?></strong></td>
        <td><?php echo $rand['greutate']; ?> kg</td>
        <td><?php echo $rand['inaltime']; ?> cm</td>
        <td><?php echo $rand['varsta']; ?> ani</td>
        <td><?php echo $rand['sex']; ?></td>
        <td><strong><?php echo $rand['bmi']; ?></strong></td>
        <td>
            <span class="badge <?php echo $clasa_badge; ?>">
                <?php echo htmlspecialchars($rand['categorie']); ?>
            </span>
        </td>
        <td><?php echo $data; ?></td>
        <td>
            <a href="istoric_bmi.php?sterge=<?php echo $rand['id']; ?>"
               class="sterg-btn"
               onclick="return confirm('Ștergi această înregistrare?')">
               🗑️ Șterge
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php else: ?>
    <div class="gol">
        📭 Nu există calcule salvate încă.<br>
        <a href="bmi.html" style="color:#ff3c3c;">Mergi la Calculator BMI</a>
    </div>
<?php endif; ?>

<?php $conn->close(); ?>

<br>
<a href="bmi.html">⬅ Înapoi la Calculator BMI</a>

</body>
</html>
