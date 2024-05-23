<?php

if (!isset($conn)) {
    include 'db_connect.php';
}

// Benutzereingaben erfassen und in Variablen speichern
$personen = isset($_GET['personenanzahl']) ? $_GET['personenanzahl'] : ''; // Anzahl der Personen
$land = isset($_GET['land']) ? $_GET['land'] : ''; // Land
$filters = isset($_GET['filter']) ? $_GET['filter'] : []; // Filter aus dem Formular

// Initialisieren eines Arrays zur Speicherung der Preisbedingungen
$price_conditions = [];

// Überprüfen, welche Preisfilter ausgewählt wurden und entsprechende Bedingungen hinzufügen
if (isset($filters['price1'])) {
    $price_conditions[] = "preis < 100.00"; // Bedingung für weniger als 25€
}
if (isset($filters['price2'])) {
    $price_conditions[] = "preis < 200.00"; // Bedingung für weniger als 100€
}
if (isset($filters['price3'])) {
    $price_conditions[] = "preis < 300.00"; // Bedingung für weniger als 200€
}

// Erstellen der Preis-SQL-Bedingung, wenn Preisfilter ausgewählt wurden
$price_sql = !empty($price_conditions) ? ' AND (' . implode(' OR ', $price_conditions) . ')' : '';

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title>
    <!-- CSS Stylesheet einbinden -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Datei einbinden -->
    <script src="js/scale_vorschaubild.js"></script>
	
</head>
<body>
    <h2>Suchergebnisse</h2>

<?php
// Überprüfen, ob die Variablen für Personenanzahl und Land gesetzt sind
if (!empty($personen) && !empty($land)) {
    // Initiale SQL-Abfrage, um Haus-IDs basierend auf Personenanzahl und Land abzurufen
    $sql = "SELECT HAUS_ID FROM haus WHERE personen >= ? AND land = ? $price_sql";
    // Vorbereitung der SQL-Abfrage
    $stmt = $conn->prepare($sql);
    // Bindung der Parameter an die Abfrage
    $stmt->bind_param("is", $personen, $land);
    // Ausführen der Abfrage
    $stmt->execute();
    // Ergebnis der Abfrage abrufen
    $result = $stmt->get_result();
    // Initialisieren eines Arrays zur Speicherung der Haus-IDs
    $haus_ids = [];

    // Iteration durch die Ergebnisse und Speichern der Haus-IDs
    while ($row = $result->fetch_assoc()) {
        $haus_ids[] = $row['HAUS_ID'];
    }

    // Schließen der Abfrage
    $stmt->close();
    
    // Wenn Filter gesetzt sind, weitere Filterung basierend auf Tags durchführen
    if (!empty($filters)) {
        // Entfernen der Preisfilter aus den allgemeinen Filtern
        $tag_filters = array_diff($filters, ['price1', 'price2', 'price3']);
        if (!empty($tag_filters)) {
            // Initialisieren eines Arrays zur Speicherung der gefilterten Haus-IDs
            $filtered_haus_ids = [];
            foreach ($haus_ids as $haus_id) {
                // SQL-Abfrage, um Tags für jede Haus-ID abzurufen
                $sql_tags = "SELECT t.tag_wert FROM tags t JOIN tag_haus_relation thr ON t.TAG_ID = thr.TAG_ID WHERE thr.HAUS_ID = ?";
                $stmt_tags = $conn->prepare($sql_tags);
                $stmt_tags->bind_param("i", $haus_id);
                $stmt_tags->execute();
                $result_tags = $stmt_tags->get_result();
                $tags = [];
                // Iteration durch die Ergebnisse und Speichern der Tags
                while ($tag = $result_tags->fetch_assoc()) {
                    $tags[] = $tag['tag_wert'];
                }
                $stmt_tags->close();

                // Überprüfen, ob alle ausgewählten Tag-Filter übereinstimmen
                $match = true;
                foreach ($tag_filters as $filter) {
                    if (!in_array($filter, $tags)) {
                        $match = false;
                        break;
                    }
                }

                // Wenn alle Filter übereinstimmen, Haus-ID zur Liste hinzufügen
                if ($match) {
                    $filtered_haus_ids[] = $haus_id;
                }
            }
            // Aktualisieren der Haus-IDs mit den gefilterten IDs
            $haus_ids = $filtered_haus_ids;
        }
    }
    
    // Iteration durch die gefilterten Haus-IDs und Abrufen der Details für jedes Haus
    foreach ($haus_ids as $haus_id) {
        // SQL-Abfrage, um Details des Hauses abzurufen
        $sql = "SELECT * FROM haus WHERE HAUS_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $haus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $haus = $result->fetch_assoc();

        // SQL-Abfrage, um Bild-URL für das Haus abzurufen
        $sql_img = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_typ = 'Vorschaubild'";
        $stmt_img = $conn->prepare($sql_img);
        $stmt_img->bind_param("i", $haus_id);
        $stmt_img->execute();
        $result_img = $stmt_img->get_result();
        // Verwenden einer Default-Bild-URL, falls kein Bild vorhanden ist
        $img_url = $result_img->fetch_assoc()['img_url'] ?? 'default_image.jpg';
        $stmt_img->close();

        // HTML-Ausgabe für jedes gefundene Haus
        echo '<div class="container2">';
        echo '<img src="' . htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') . '" alt="Bild">';
        echo '<div class="text">';
        echo '<h2><a href="detailseite_unterkuenfte.php?HAUS_ID=' . htmlspecialchars($haus['HAUS_ID'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($haus['name'], ENT_QUOTES, 'UTF-8') . '</a></h2>';
        echo '<p><b>Adresse: </b>' . htmlspecialchars($haus['adresse'], ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><b>Preis: </b>' . htmlspecialchars($haus['preis'], ENT_QUOTES, 'UTF-8') . ' EUR</p>';
        echo '<p><b>Max. Personen: </b>' . htmlspecialchars($haus['personen'], ENT_QUOTES, 'UTF-8') . '</p>';

        // SQL-Abfrage, um die Tags des Hauses abzurufen
        $sql_tags = "SELECT t.tag_wert FROM tags t JOIN tag_haus_relation thr ON t.TAG_ID = thr.TAG_ID WHERE thr.HAUS_ID = ?";
        $stmt_tags = $conn->prepare($sql_tags);
        $stmt_tags->bind_param("i", $haus_id);
        $stmt_tags->execute();
        $result_tags = $stmt_tags->get_result();
        $tags = [];
        // Iteration durch die Ergebnisse und Speichern der Tags
        while ($tag = $result_tags->fetch_assoc()) {
            $tags[] = htmlspecialchars($tag['tag_wert'], ENT_QUOTES, 'UTF-8');
        }
        $stmt_tags->close();

        // Ausgabe der Tags, wenn vorhanden
        if (!empty($tags)) {
            echo '<p><b>Highlights:</b> ' . implode(', ', $tags) . '</p>';
        }

        echo '</div>';
        // Hinzufügen des "Mehr Infos und Buchen" Buttons mit der richtigen CSS-Klasse
        echo '<div style="text-align:right;">';
        echo '<a href="detailseite_unterkuenfte.php?HAUS_ID=' . htmlspecialchars($haus['HAUS_ID'], ENT_QUOTES, 'UTF-8') . '" class="button">Mehr Infos und Buchen</a>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // Ausgabe einer Meldung, wenn keine Personenanzahl und Land angegeben sind
    echo '<p>Bitte geben Sie die Personenanzahl und das Land an.</p>';
}

// Schließen der Datenbankverbindung
$conn->close();
?>
</body>
</html>
