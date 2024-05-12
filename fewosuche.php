<?php 
session_start(); 
	$personenanzahl = isset($_GET['personenanzahl']) ? $_GET['personenanzahl'] : '';
	$land = isset($_GET['land']) ? $_GET['land'] : '';

	if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
    header('Location: admin_start.php');
    exit;
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
                        <button onclick="window.open('meineBuchungen.php', '_blank');">Meine Buchungen</button>
                        <button onclick="window.open('profil.php', '_blank');">Profil</button>
                        <button onclick="window.open('logout.php', '_self');">Logout</button>
                        <?php break;
                    case "Vermieter": ?>
                        <!-- Menü für Vermieter -->
                        <button onclick="window.open('buchungen.php', '_blank');">Meine Buchungen</button>
                        <button onclick="window.open('unterkuenfte.php', '_blank');">Unterkünfte verwalten</button>
                        <button onclick="window.open('profil.php', '_blank');">Profil</button>
                        <button onclick="window.open('logout.php', '_self');">Logout</button>
                        <?php break;
                endswitch; ?>
            <?php endif; ?>
        </nav>
    </header>
<!-- Hauptinhalt -->
<main>
		<div class="tag-suche">
		<!-- Suchleiste -->
		<form class="search-form" action="fewosuche.php" method="GET" target="_blank">
				<label for="tagsuche"></label>
				<input type="text" placeholder="Nach Tags suchen" name="email" required>
			<button type="submit">Ferienhaus Suchen</button>
		</form>
	</div>
	<!-- Linke Sidebar -->
		<div class="sidebar-left">
			<h2><span>Filter</span></h2> <BR>
		 <form action="fewosuche.php" method="GET">
				<select id="personenanzahl" name="personenanzahl">
					<?php
					$personenOptionen = ["1", "2", "3", "4", "5", "6", "7+"];
					foreach ($personenOptionen as $option) {
						echo '<option value="' . $option . '"' . ($option === $personenanzahl ? ' selected' : '') . '>' . $option . '</option>';
					}
					?>
				</select>
				<br>

				<select id="land" name="land">
					<?php
					$laenderOptionen = [
						"deutschland" => "Deutschland", "oesterreich" => "Österreich",
						"schweiz" => "Schweiz", "italien" => "Italien", "spanien" => "Spanien",
						"frankreich" => "Frankreich", "schweden" => "Schweden", "niederlande" => "Niederlande",
						"kroatien" => "Kroatien", "tschechien" => "Tschechien", "finnland" => "Finnland",
						"england" => "England", "portugal" => "Portugal", "polen" => "Polen",
						"daenemark" => "Dänemark", "norwegen" => "Norwegen", "ungarn" => "Ungarn"
					];
					foreach ($laenderOptionen as $key => $value) {
						echo '<option value="' . $key . '"' . ($key === $land ? ' selected' : '') . '>' . $value . '</option>';
					}
					?>
				</select>
				<br><br>
						<!-- Weitere Filteroptionen ohne initiale Werte -->
						<label><input type="checkbox" name="filter" value="pool">Pool</label><br>
						<label><input type="checkbox" name="filter" value="wlan">WLAN</label><br>
						<label><input type="checkbox" name="filter" value="strandnaehe">Strandnähe</label><br>
						<label><input type="checkbox" name="filter" value="Parkplatz">Parkplatz</label><br>
						<label><input type="checkbox" name="filter" value="Küche">Küche</label><br>
				<br>
				<button type="submit">Suche aktualisieren</button>
				</form>
		</div>
	 <!-- Popup-Anzeige bei erfolgreicher Registrierung -->
    <?php if (isset($_SESSION['registration_success'])): ?>
        <script>
            alert('<?php echo $_SESSION['registration_success']; ?>');
            <?php unset($_SESSION['registration_success']); ?>
        </script>
    <?php endif; ?>
	

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