<?php 
session_start(); 
include 'db_connect.php';
include 'auth_nav.php';

// Benutzerrolle abrufen
$role = get_user_role();
exclude_user_Roles(['Admin'], 'index.php');

// Annahme: HAUS_ID wird als Parameter übergeben und in eine Ganzzahl umgewandelt
$haus_id = isset($_GET['HAUS_ID']) ? intval($_GET['HAUS_ID']) : 0;

// Preis pro Nacht aus der Datenbank abrufen
$stmt = $conn->prepare("SELECT preis FROM haus WHERE HAUS_ID = ?");
$stmt->bind_param("i", $haus_id);
$stmt->execute();
$stmt->bind_result($preis_pro_nacht);
$stmt->fetch();
$stmt->close();

// Überprüfen, ob ein gültiger Preis abgerufen wurde
if (!$preis_pro_nacht) {
    echo "Ungültige HAUS_ID.";
    exit;
}

// Heutiges Datum berechnen
$heute = date('Y-m-d');

// Datum für morgen berechnen
$morgen = date('Y-m-d', strtotime('+1 day'));
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Ihr Ferien Domizil</title>
    <!-- CSS Stylesheet einbinden -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Datei einbinden -->
    <script src="js/popup.js"></script>
    <!-- Inline-Skript zur Preisübergabe an das JavaScript -->
    <script>
        const preisProNacht = <?php echo $preis_pro_nacht; ?>; // Preis pro Nacht aus PHP in JavaScript übergeben
        const heute = "<?php echo $heute; ?>"; // Heutiges Datum aus PHP in JavaScript übergeben
        const morgen = "<?php echo $morgen; ?>"; // Datum für morgen aus PHP in JavaScript übergeben
    </script>
    <!-- JavaScript Datei für die Preisberechnung einbinden -->
    <script src="js/price_calculate_realtime.js" defer></script>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a> 
        </div>
        <nav class="menu">
            <?php display_menu(); ?> 
        </nav>
    </header>
    <main>
        <?php if ($role === 'guest'): ?> 
            <p><h1>Bitte melden Sie sich an um fortzufahren.</h1></p> 
        <?php else: ?>
            <h1>Buchung für Ferienhaus</h1> 
            <form action="buchung_verarbeiten.php" method="post"> <!-- Formular zur Buchung -->
                <input type="hidden" name="haus_id" value="<?php echo htmlspecialchars($haus_id); ?>"> <!-- Verstecktes Feld für HAUS_ID -->
                <label for="anreise"><b>Anreise:</b></label>
                <input type="date" id="anreise" name="anreise" value="<?php echo $heute; ?>" required> <!-- Anreisedatum -->
                <label for="abreise"><b>Abreise:</b></label>
                <input type="date" id="abreise" name="abreise" value="<?php echo $morgen; ?>" required><br><br> <!-- Abreisedatum -->
                <div id="gesamtpreis"><b>Gesamtpreis: 0.00 EUR</b></div><br> <!-- Bereich für die Anzeige des Gesamtpreises -->
                <div id="errorOutput" style="color: red;"></div> <!-- Bereich für die Anzeige von Fehlermeldungen -->
                <button type="submit">Buchen</button> <!-- Absende-Button -->
            </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p> 
        <p>Telefon: 123-456-789</p>
         <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
