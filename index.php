<?php 
session_start(); 
include 'db_connect.php';
include 'auth_nav.php';
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
	 <!-- Popup-Anzeige bei erfolgreicher Registrierung -->
    <?php if (isset($_SESSION['registration_success'])): ?>
        <script>
            alert('<?php echo $_SESSION['registration_success']; ?>');
            <?php unset($_SESSION['registration_success']); ?>
        </script>
    <?php endif; ?>
	
	<div class="container-suche">
        <?php if (get_user_role() === 'Admin'): ?>
            <h2>Herzlich Willkommen im Admin-Bereich!</h2>
        <?php else: ?>



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
				<option value="schweden">Schweden</option>
				<option value="finnland">Finnland</option>
				<option value="england">England</option>
				<option value="daenemark">Dänemark</option>
				<option value="norwegen">Norwegen</option>
			</select>

			<button type="submit">Ferienhaus Suchen</button>
		</form>
		<?php endif; ?>
	</div>
		<div class="c-heroImageGrid">
		<div class="container">
				<div class="column"></div>
				<div class="column">
					<div class="row"></div>
					<div class="row">
						<div>
							<div class="text">

								<p><BR>
								Das Leben ist eine Reise. Erkunden und genießen Sie Europa und all seine Facetten. <BR><BR> Auf unserer Webseite finden Sie Ihr perfektes Feriendomizil!
								</p>
							</div>
						</div>
						<div></div>
					</div>
					<div class="row">
					</div>
				</div>
			</div>
			<div class="separator">
		</div>
		

</main>
<footer>
  <p>Kontaktieren Sie uns für weitere Informationen:</p>
  <p>Telefon: 123-456-789</p>
  <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
</footer>


</body>
</html>