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
			<button class="button" onclick="window.open('tabelle1.html')">User-Verwaltung</button>
			<button class="button" onclick="window.open('tabelle2.html')">Unterkünfte verwalten</button>
			<button class="button" onclick="window.open('tabelle3.html')">Buchungen verwalten</button>
			<button class="button" onclick="window.open('tabelle3.html')">Tags verwalten</button>
			<button onclick="window.open('logout.php', '_self');">Logout</button>
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