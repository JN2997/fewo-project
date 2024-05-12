<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title>
		<!-- CSS Aufrufe -->
	<link rel="stylesheet" href="css/main.css">
		<!-- JavaScript Aufrufe -->

</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
			<button class="button" onclick="window.open('tabelle1.html')">User-Verwaltung</button>
			<button class="button" onclick="window.open('tabelle2.html')">Unterkünfte verwalten</button>
			<button class="button" onclick="window.open('tabelle3.html')">Buchungen verwalten</button>
			<button class="button" onclick="window.open('tabelle3.html')">Tag-Verwalten</button>
			<button onclick="window.open('logout.php', '_self');">Logout</button>
        </nav>
    </header>

<!-- Hauptinhalt -->
<main>

	
	
    <!-- Neuer Container für Highlights und darunterliegende Inhalte -->
    <div class="container-highlights">
        <h2>Herzlich Willkommen im Admin-Bereich!</h2>
      
    </div>
</main>
<footer>
  <p>Kontaktieren Sie uns für weitere Informationen:</p>
  <p>Telefon: 123-456-789</p>
  <p>Email: info@IhrFerienDomizil.com</p>
</footer>
</div>


</body>
</html>