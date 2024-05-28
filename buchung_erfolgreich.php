<?php
session_start(); 
include 'auth_nav.php'; 

// Überprüfen, ob die Buchungsdetails in der Sitzung gesetzt sind
if (!isset($_SESSION['buchung_details'])) {
    header('Location: index.php'); // Umleiten zur Startseite, wenn keine Buchungsdetails vorhanden sind
    exit; 
}

$details = $_SESSION['buchung_details']; // Buchungsdetails aus der Sitzung holen
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buchung Erfolgreich</title>
    <link rel="stylesheet" href="css/main.css"> 
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
        <h1>Buchung Erfolgreich</h1>
        <p>Vielen Dank für Ihre Buchung!</p>
        <ul> <!-- Nutzung aller Parameter der buchung_details Variable die in der Session gespeichert wurden.
            <li>Haus ID: <?php echo htmlspecialchars($details['haus_id']); ?></li> <!-- Anzeige der Haus-ID -->
            <li>Anreise am: <?php echo htmlspecialchars($details['anreise']); ?></li> <!-- Anzeige des Anreisedatums -->
            <li>Abreise am: <?php echo htmlspecialchars($details['abreise']); ?></li> <!-- Anzeige des Abreisedatums -->
            <li>Gesamtpreis: <?php echo htmlspecialchars($details['gesamtpreis']); ?> EUR</li> <!-- Anzeige des Gesamtpreises -->
            <li>Die Rechnung ist vor Ort zu begleichen.</li>
        </ul>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
<?php
unset($_SESSION['buchung_details']); // Buchungsdetails aus der Sitzung löschen
?>
