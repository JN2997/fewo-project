<?php 
session_start(); 
include 'db_connect.php';
$sql = "SELECT tag_wert FROM tags"; // Adjust this query to your table structure
$result = $conn->query($sql);

$tags = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tags[] = $row["tag_wert"];
    }
}

$conn->close();

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
	<script>
		const dbTags = <?php echo json_encode($tags); ?>; // Konvertiert das PHP-Array $tags in ein JSON-Format und speichert es in einer JavaScript-Variablen dbTags.
		console.log(dbTags); // Überprüfen, ob die Tags korrekt übergeben werden
	</script>
	
	<script src="js/tags_add_haus.js" defer></script>
    
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
			<form action="add_haus.php" method="post" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<input type="text" name="name" placeholder="Name des Ferienhauses" required>
					<input type="text" name="adresse" placeholder="Adresse" required>
				</div>
				<div class="form-group">
					<input type="text" name="land" placeholder="Land" required>
					<input type="text" name="beschreibung" placeholder="Beschreibung" required>
				</div>
				<div class="form-group">
					<input type="number" name="personen" placeholder="Maximale Personenanzahl" required>
					<input type="number" name="preis" placeholder="Preis pro Nacht" step="1" required>
				</div>
				<div class="form-group">
					<div class="upload_section">
						<label for="vorschaubild">Vorschaubild</label>
						<input type="file" id="vorschaubild" name="vorschaubild" accept="image/*" required>
					</div>
					<div class="upload_section">
						<label for="lageplan">Lageplan</label>
						<input type="file" id="lageplan" name="lageplan" accept="image/*" required>
					</div>
					<div class="upload_section">
						<label for="aussenansicht">Außenansicht</label>
						<input type="file" id="aussenansicht" name="aussenansicht[]" accept="image/*" multiple required>
					</div>
					<div class="upload_section">
						<label for="innenansicht">Innenansicht</label>
						<input type="file" id="innenansicht" name="innenansicht[]" accept="image/*" multiple required>
					</div>
				</div>
					<div class="form-group tags-input-container">
						<div class="tags-input">
							<label for="tags">Tags</label>
								<div id="tags-container" class="tags-container"></div>
								</div>
							<select id="tags-select" multiple size="10">
							<!-- Optionen werden hier von JavaScript hinzugefügt -->
							</select>
							<input type="hidden" name="tags" id="hidden-tags">
						</div>
				<button type="submit">Haus hinzufügen</button>
			</form>
		</div>
	</main>

	<footer>
	  <p>Kontaktieren Sie uns für weitere Informationen:</p>
	  <p>Telefon: 123-456-789</p>
	  <p>Email: info@IhrFerienDomizil.com</p>
	</footer>
</body>
</html>