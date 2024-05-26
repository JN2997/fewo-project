<?php
session_start();
include 'auth_nav.php';

if (!isset($_SESSION['buchung_details'])) {
    header('Location: index.php');
    exit;
}

$details = $_SESSION['buchung_details'];
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
            <?php display_menu();?>
        </nav>
    </header>
    <main>
        <h1>Buchung Erfolgreich</h1>
        <p>Vielen Dank für Ihre Buchung!</p>
        <ul>
            <li>Haus ID: <?php echo htmlspecialchars($details['haus_id']); ?></li>
            <li>Anreise am: <?php echo htmlspecialchars($details['anreise']); ?></li>
            <li>Abreise am: <?php echo htmlspecialchars($details['abreise']); ?></li>
            <li>Gesamtpreis: <?php echo htmlspecialchars($details['gesamtpreis']); ?> EUR</li>
			<li>Die Rechnung ist vor Ort zu begleichen.</li>
        </ul>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: info@IhrFerienDomizil.com</p>
    </footer>
</body>
</html>
<?php
unset($_SESSION['buchung_details']);
?>
