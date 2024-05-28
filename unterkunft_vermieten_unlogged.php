<?php 
session_start(); 
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles (['Vermieter', 'Mieter', 'Admin'], 'index.php');
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
<!-- Hauptinhalt -->
<main>
	
	
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

		<!-- Popups einbinden -->
		<?php
		include 'popupanmelden.php';
		include 'popupregistrieren.php';
		?>
		
        <button onclick="openPopupregistrieren()">Jetzt Registrieren und vermieten</button><br><br>

		Wir freuen uns darauf, Sie als Gastgeber willkommen zu heißen!<br><br>

		Ihr FerienDomizil-Team<br>
</div>
</main>

<footer>
  <p>Kontaktieren Sie uns für weitere Informationen:</p>
  <p>Telefon: 123-456-789</p>
  <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
</footer>



</body>
</html>