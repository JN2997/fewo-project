<?php 
session_start(); 
include 'db_connect.php';

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') 
{
header('Location: admin_start.php');
exit;
}

// Annahme: HAUS_ID wird als Parameter übergeben
$haus_id = isset($_GET['HAUS_ID']) ? intval($_GET['HAUS_ID']) : 0;

// Preis pro Nacht aus der Datenbank abrufen
$stmt = $conn->prepare("SELECT preis FROM haus WHERE HAUS_ID = ?");
$stmt->bind_param("i", $haus_id);
$stmt->execute();
$stmt->bind_result($preis_pro_nacht);
$stmt->fetch();
$stmt->close();

if (!$preis_pro_nacht) {
    echo "Ungültige HAUS_ID.";
    exit;
}
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
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
            <?php if (!isset($_SESSION['loggedin'])): ?>
                <!-- Menü für nicht eingeloggte Benutzer -->
                <button onclick="window.open('vermieterseite.php', '_blank');">Unterkunft vermieten</button>
                <button onclick="openPopupanmelden()">Anmelden</button>
                <button onclick="openPopupregistrieren()">Registrieren</button>
            <?php else: ?>
                <?php switch ($_SESSION['role']): 
                    case "Mieter": ?>
                        <!-- Menü für Mieter -->
                        <button onclick="window.open('meine_buchungen.php', '_blank');">Meine Buchungen</button>
                        <button onclick="window.open('profil.php', '_blank');">Profil</button>
                        <button onclick="window.open('logout.php', '_self');">Logout</button>
                        <?php break;
                    case "Vermieter": ?>
                        <!-- Menü für Vermieter -->
                        <button onclick="window.open('meine_buchungen.php', '_blank');">Meine Buchungen</button>
                        <button onclick="window.open('unterkuenfte.php', '_blank');">Unterkünfte verwalten</button>
                        <button onclick="window.open('profil.php', '_blank');">Profil</button>
                        <button onclick="window.open('logout.php', '_self');">Logout</button>
                        <?php break;
                endswitch; ?>
            <?php endif; ?>
        </nav>
    </header>
<main>
		<?php if (!$isLoggedIn): ?>
            <p><h1>Bitte melden Sie sich an um fortzufahren.</h1></p>
        <?php else: ?>
            <h1>Buchung für Ferienhaus</h1>
			<form action="buchung_verarbeiten.php" method="post">
                <input type="hidden" name="haus_id" value="<?php echo htmlspecialchars($haus_id); ?>">
                <label for="anreise">Anreise:</label>
                <input type="date" id="anreise" name="anreise" required>
                <label for="abreise">Abreise:</label>
                <input type="date" id="abreise" name="abreise" required>
                <button type="submit">Buchen</button>
            </form>
        <?php endif; ?>
</main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: info@IhrFerienDomizil.com</p>
    </footer>

    <!-- Das Anmelde-Popup -->
    <div id="popupanmelden" class="popupanmelden" style="<?php echo isset($_SESSION['registration_success']) ? 'display:block;' : 'display:none;'; ?>">
        <form action="signin.php" class="form-container" method="post">
            <h1>Anmeldung</h1>
            <h3><?php if (isset($_SESSION['registration_success'])): ?>
                <p style="color:green;"><?php echo $_SESSION['registration_success']; unset($_SESSION['registration_success']); ?></p>
            <?php endif; ?></h3>
            <label for="email"></label>
            <input type="text" placeholder="Email" name="email" required>
            <label for="psw"></label>
            <input type="password" placeholder="Passwort" name="psw" required>
            <button type="submit" class="btn">Login</button>
            <button type="button" class="btn cancel" onclick="closePopup()">Abbrechen</button>
        </form>
    </div>
    
    <!-- Das Registrieren-Popup -->
    <div id="popupregistrieren" class="popupregistrieren">
        <form action="signup.php" class="form-container" method="post">
            <h1>Registrierung</h1>
            <label for="forname"></label>
            <input type="text" placeholder="Vorname angeben" name="forname" required>
            <label for="surname"></label>
            <input type="text" placeholder="Nachname angeben" name="surname" required>
            <label for="email"></label>
            <input type="text" placeholder="E-Mail eingeben" name="email" required>
            <label for="psw"></label>
            <input type="password" placeholder="Passwort eingeben" name="psw" required>
            <label for="confirm_psw"></label>
            <input type="password" placeholder="Passwort wiederholen" name="confirm_psw" required>
            <label for="is_vermieter">Ich möchte mich auch als Vermieter registrieren:</label>
            <input type="checkbox" id="is_vermieter" name="is_vermieter">
            <button type="submit" class="btn">Registrierung abschicken</button>
            <button type="button" class="btn cancel" onclick="closePopup()">Abbrechen</button>
        </form>
    </div>
</body>
</html>