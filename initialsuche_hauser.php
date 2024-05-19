<?php
if (!isset($conn)) {
    include 'db_connect.php';
}

// Benutzereingaben erfassen
$personen = isset($_GET['personenanzahl']) ? $_GET['personenanzahl'] : '';
$land = isset($_GET['land']) ? $_GET['land'] : '';

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

        // HTML-Code, um die Details des Hauses anzuzeigen
        echo "<div class='haus-container'>";
        echo "<h2>" . htmlspecialchars($haus['name'], ENT_QUOTES, 'UTF-8') . "</h2>";
        echo "<p>Adresse: " . htmlspecialchars($haus['adresse'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p>Preis: " . htmlspecialchars($haus['preis'], ENT_QUOTES, 'UTF-8') . " EUR</p>";
        echo "<p>Max. Personen: " . htmlspecialchars($haus['personen'], ENT_QUOTES, 'UTF-8') . "</p>";

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
            echo "<p>Tags: " . implode(', ', $tags) . "</p>";
        }

        // Bilder des Hauses abrufen
        $sql_img = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_typ = 'Vorschaubild'";
        $stmt_img = $conn->prepare($sql_img);
        $stmt_img->bind_param("i", $haus_id);
        $stmt_img->execute();
        $result_img = $stmt_img->get_result();
        while ($img = $result_img->fetch_assoc()) {
            echo "<img src='" . htmlspecialchars($img['img_url'], ENT_QUOTES, 'UTF-8') . "' alt='Vorschaubild'>";
        }
        $stmt_img->close();

        // "Informieren und Buchen"-Button hinzufügen
        echo "<div class='button-container'>";
        echo "<form action='detailseite_unterkuenfte.php' method='POST'>";
        echo "<input type='hidden' name='HAUS_ID' value='" . htmlspecialchars($haus['HAUS_ID'], ENT_QUOTES, 'UTF-8') . "'>";
        echo "<button type='submit'>Informieren und Buchen</button>";
        echo "</form>";
        echo "</div>";

        // Schließen des HTML-Containers für dieses Haus
        echo "</div>";
    }
} else {
    echo "<p>Bitte geben Sie die Personenanzahl und das Land an.</p>";
}

// Schließen der Datenbankverbindung
$conn->close();
?>
