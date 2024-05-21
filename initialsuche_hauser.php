<?php
if (!isset($conn)) {
    include 'db_connect.php';
}

// Benutzereingaben erfassen
$personen = isset($_GET['personenanzahl']) ? $_GET['personenanzahl'] : '';
$land = isset($_GET['land']) ? $_GET['land'] : '';

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
    <script src="js/scale_vorschaubild.js"></script>
</head>
<body>
    <h2>Suchergebnisse</h2>

<?php
// Überprüfen, ob die Variablen gesetzt sind
if (!empty($personen) && !empty($land)) {
    // Initiale Suche durchführen nach HAUS_ID's
    $sql = "SELECT HAUS_ID FROM haus WHERE personen >= ? AND land = ?";
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

        // Bild-URL abrufen
        $sql_img = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_typ = 'Vorschaubild'";
        $stmt_img = $conn->prepare($sql_img);
        $stmt_img->bind_param("i", $haus_id);
        $stmt_img->execute();
        $result_img = $stmt_img->get_result();
        $img_url = $result_img->fetch_assoc()['img_url'] ?? 'default_image.jpg'; // Default-Bild verwenden, falls kein Bild vorhanden
        $stmt_img->close();

        echo '<div class="container2">';
        echo '<img src="' . htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') . '" alt="Bild">';
        echo '<div class="text">';
        echo '<h2><a href="detailseite_unterkuenfte.php?HAUS_ID=' . htmlspecialchars($haus['HAUS_ID'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($haus['name'], ENT_QUOTES, 'UTF-8') . '</a></h2>';
        echo '<p><b>Adresse: </b>' . htmlspecialchars($haus['adresse'], ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><b>Preis: </b>' . htmlspecialchars($haus['preis'], ENT_QUOTES, 'UTF-8') . ' EUR</p>';
        echo '<p><b>Max. Personen: </b>' . htmlspecialchars($haus['personen'], ENT_QUOTES, 'UTF-8') . '</p>';

        // Tags des Hauses abrufen
        $sql_tags = "SELECT t.tag_wert FROM tags t JOIN tag_haus_relation thr ON t.TAG_ID = thr.TAG_ID WHERE thr.HAUS_ID = ?";
        $stmt_tags = $conn->prepare($sql_tags);
        $stmt_tags->bind_param("i", $haus_id);
        $stmt_tags->execute();
        $result_tags = $stmt_tags->get_result();
        $tags = [];
        while ($tag = $result_tags->fetch_assoc()) {
            $tags[] = htmlspecialchars($tag['tag_wert'], ENT_QUOTES, 'UTF-8');
        }
        $stmt_tags->close();

        if (!empty($tags)) {
            echo '<p><b>Highlights:</b> ' . implode(', ', $tags) . '</p>';
        }

		echo '</div>';
        // Hinzufügen des "Mehr Infos und Buchen" Buttons mit der richtigen CSS-Klasse
        echo '<div style="text-align:right;">';
        echo '<a href="detailseite_unterkuenfte.php?HAUS_ID=' . htmlspecialchars($haus['HAUS_ID'], ENT_QUOTES, 'UTF-8') . '" class="button">Mehr Infos und Buchen</a>';
        echo '</div>';
        echo '</div>';;
    }
} else {
    echo '<p>Bitte geben Sie die Personenanzahl und das Land an.</p>';
}

// Schließen der Datenbankverbindung
$conn->close();
?>
</body>
</html>
