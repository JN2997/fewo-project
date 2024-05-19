<?php
	if (!isset($conn)) {
		include 'db_connect.php';
	}

// Benutzereingaben erfassen
	$personen = $_POST['personen'];
	$land = $_POST['land'];

// Initiale Suche durchführen nach HAUS_ID's
	$sql = "SELECT HAUS_ID FROM haus WHERE personen = ? AND land = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("is", $personen, $land);
	$stmt->execute();
	$result = $stmt->get_result();
	$haus_ids = [];
	
		while ($row = $result->fetch_assoc()) {
			$haus_ids[] = $row['HAUS_ID'];
		}
		
$stmt->close();

// Details für jedes Haus abrufen und dynamische Container erstellen
	foreach ($haus_ids as $haus_id) {
		// Details des Hauses abrufen
		$sql = "SELECT * FROM haus WHERE HAUS_ID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $haus_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$haus = $result->fetch_assoc();

		// HTML-Code, um die Details des Hauses anzuzeigen
		echo "<div class='haus-container'>";
		echo "<h2>" . htmlspecialchars($haus['name'], ENT_QUOTES, 'UTF-8') . "</h2>";
		echo "<p>Adresse: " . htmlspecialchars($haus['adresse'], ENT_QUOTES, 'UTF-8') . "</p>";
		echo "<p>Preis: " . htmlspecialchars($haus['preis'], ENT_QUOTES, 'UTF-8') . " EUR</p>";
		echo "<p>Beschreibung: " . nl2br(htmlspecialchars($haus['beschreibung'], ENT_QUOTES, 'UTF-8')) . "</p>";

		// Bilder des Hauses abrufen
		$sql_img = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_type = 'Vorschaubild'";
		$stmt_img = $conn->prepare($sql_img);
		$stmt_img->bind_param("i", $haus_id);
		$stmt_img->execute();
		$result_img = $stmt_img->get_result();
		while ($img = $result_img->fetch_assoc()) {
			echo "<img src='" . htmlspecialchars($img['img_url'], ENT_QUOTES, 'UTF-8') . "' alt='Vorschaubild'>";
		}
		$stmt_img->close();

		// Schließen des HTML-Containers für dieses Haus
		echo "</div>";
	}

// Schließen der Datenbankverbindung
$conn->close();
?>
