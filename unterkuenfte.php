<?php 
session_start(); 
include 'db_connect.php';

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

</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
			<button onclick="window.open('buchungen.php', '_blank');">Meine Buchungen</button>
			<button onclick="window.open('profil.php', '_blank');">Profil</button>
			<button onclick="window.open('logout.php', '_self');">Logout</button>
        </nav>
    </header>
<!-- Linke Sidebar -->
		<div class="sidebar-left">
			<h2><span>Unterkünfte</span></h2> <BR>
				<button type="submit">Bearbeiten</button>
				<button type="submit">Löschen</button>
				<button type="submit">Buchungen einsehen</button>
		</div>
<!-- Hauptinhalt -->
<main>
	<div class="add_haus">
		<form action="add_haus.php" method="post" enctype="multipart/form-data">
			<input type="text" name="name" placeholder="Name des Ferienhauses" required>
			<input type="text" name="adresse" placeholder="Adresse" required>
			<input type="text" name="land" placeholder="Land" required>
			<input type="text" name="beschreibung" placeholder="Beschreibung" required>
			<input type="number" name="personen" placeholder="Maximale Personenanzahl" required>
			<input type="number" name="preis" placeholder="Preis pro Nacht" step="1" required>
			<input type="file" name="bilder[]" multiple="multiple" accept="image/*">
			<button type="submit">Haus hinzufügen</button>
		</form>
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