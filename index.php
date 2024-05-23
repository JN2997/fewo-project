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
			<button onclick="window.open('unterkunft_vermieten_unlogged.php', '_blank');">Unterkunft vermieten</button>
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
	
	<div class="container-suche">
		<!-- Suchleiste -->
		<form class="search-form" action="fewosuche.php" method="GET">
			<select id="personenanzahl" name="personenanzahl" required>
				<option value="" disabled selected>Anzahl Gäste</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7+">7+</option>
			</select>

			<select id="land" name="land" required>
				<option value="" disabled selected>Reiseziel</option>
				<option value="deutschland">Deutschland</option>
				<option value="oesterreich">Österreich</option>
				<option value="schweiz">Schweiz</option>
				<option value="italien">Italien</option>
				<option value="spanien">Spanien</option>
				<option value="frankreich">Frankreich</option>
				<option value="schweden">Schweden</option>
				<option value="niederlande">Niederlande</option>
				<option value="kroatien">Kroatien</option>
				<option value="tschechien">Tschechien</option>
				<option value="finnland">Finnland</option>
				<option value="england">England</option>
				<option value="portugal">Portugal</option>
				<option value="polen">Polen</option>
				<option value="daenemark">Dänemark</option>
				<option value="norwegen">Norwegen</option>
				<option value="ungarn">Ungarn</option>
			</select>

			<button type="submit">Ferienhaus Suchen</button>
		</form>
	</div>

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
			<input type="checkbox" id="is_vermieter" name="is_vermieter">
			
            <button type="submit" class="btn">Registrierung abschicken</button>
            <button type="button" class="btn cancel" onclick="closePopup()">Abbrechen</button>
        </form>
    </div>
</div>

</body>
</html>