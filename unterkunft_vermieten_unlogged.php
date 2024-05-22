<?php 
session_start(); 
include 'db_connect.php';

// Überprüfung, ob der Benutzer bereits eingeloggt ist
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['role'])) 
{
    // Weiterleitung basierend auf der Rolle des Benutzers
    switch ($_SESSION['role']) {
        case 'Mieter':
            header('Location: mieter_start.php');
            exit;
        case 'Vermieter':
            header('Location: vermieter_start.php');
            exit;
        case 'Admin':
            header('Location: admin_start.php');
            exit;
		}
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title>
		<!-- CSS Aufrufe -->
	<link rel="stylesheet" href="css/main.css">
		<!-- JavaScript Aufrufe -->
    <script src="js/popup.js"></script>
    <script src="js/navigation.js"></script>
    <script src="js/carousel.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
			<button onclick="openPopupanmelden()">Anmelden</button>
            <button onclick="openPopupregistrieren()">Registrieren</button>
        </nav>
    </header>
<!-- Hauptinhalt -->
<main>
	 <!-- Popup-Anzeige bei erfolgreicher Registrierung -->
    <?php if (isset($_SESSION['registration_success'])): ?>
        <script>
            alert('<?php echo $_SESSION['registration_success']; ?>');
            <?php unset($_SESSION['registration_success']); ?>
        </script>
    <?php endif; ?>
	
	
    <!-- Neuer Container für Vermieter Ansprache und Registrierung -->
    <div class="container-vermieteransprache">
        <h2>Willkommen bei "Ihr Ferien Domizil"</h2>
        Vermieten Sie Ihre Unterkunft bei uns und profitieren Sie von vielen Vorteilen!
		Wir freuen uns, dass Sie daran interessiert sind, Ihre Unterkunft auf bei uns zu vermieten.<br> 
		Unsere Plattform bietet Ihnen eine Vielzahl von Vorteilen, um Ihre Ferienwohnung oder Ihr Ferienhaus optimal zu präsentieren und zu vermieten.<br>

		<h3>Warum bei uns vermieten?</h3>
		<p><b>Reichweite:</b> Erreichen Sie Tausende von potenziellen Gästen weltweit. Unsere Plattform wird täglich von vielen Reisenden besucht, die auf der Suche nach ihrer perfekten Unterkunft sind.</p>
		<p><b>Einfachheit:</b> Unser benutzerfreundliches System macht es Ihnen leicht, Ihre Unterkunft zu verwalten. Von der Verfügbarkeitskalender bis zur Preisgestaltung – alles ist intuitiv und einfach zu bedienen.</p>
		<p><b>Support:</b> Unser engagiertes Support-Team steht Ihnen jederzeit zur Verfügung, um Ihre Fragen zu beantworten und Ihnen bei der Verwaltung Ihrer Unterkunft zu helfen.
		<h3>So einfach geht's:</h3>
		<p><b>Registrieren:</b> Erstellen Sie ein Konto bei uns. Es dauert nur wenige Minuten!</p>
		<p><b>Anmelden:</b> Loggen Sie sich ein und fügen Sie Ihre Unterkunft hinzu.</p>
		<p><b>Inserieren:</b> Füllen Sie die Details Ihrer Unterkunft aus, laden Sie schöne Fotos hoch und legen Sie Ihre Preise fest.</p>
		<p><b>Vermieten:</b> Begrüßen Sie Ihre ersten Gäste!</p>
		<p><b>Jetzt registrieren oder anmelden:</b> 
		Um Ihre Unterkunft zu vermieten, müssen Sie sich bei uns registrieren oder anmelden. Klicken Sie einfach auf den untenstehenden Button und starten Sie noch heute:</p>

        <button onclick="openPopupregistrieren()">Jetzt Registrieren und vermieten</button><br><br>

		Wir freuen uns darauf, Sie als Gastgeber willkommen zu heißen!<br><br>

		Ihr FerienDomizil-Team<br>
</div>
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
            <H3><?php if (isset($_SESSION['registration_success'])): ?>
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
	    <!-- Das Popup -->
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
			<input type="checkbox" id="is_vermieter" name="is_vermieter" checked>
			
            <button type="submit" class="btn">Registrierung abschicken</button>
            <button type="button" class="btn cancel" onclick="closePopup()">Abbrechen</button>
        </form>
    </div>
</div>

</body>
</html>