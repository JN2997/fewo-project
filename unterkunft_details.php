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
    <!-- CSS Stylesheet einbinden -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Datei einbinden -->
    <script src="js/popup.js"></script>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
            <?php display_menu();?>
        </nav>
    </header>
	
    <main>
 <?php
        // Überprüfen, ob HAUS_ID in der URL vorhanden ist
			if (isset($_GET['HAUS_ID'])) {
            $haus_id = $conn->real_escape_string($_GET['HAUS_ID']);

            // SQL-Abfrage zum Abrufen der Haus-Daten
            $sql = "SELECT * FROM haus WHERE HAUS_ID = '$haus_id'";
            $result = $conn->query($sql);

            // Überprüfen, ob die Abfrage Ergebnisse zurückgibt
            if ($result && $result->num_rows > 0) {
                // Haus-Daten abrufen und anzeigen
                while ($row = $result->fetch_assoc()) {
                    echo '<h1>' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</h1>';
                
                    // SQL-Abfrage, um Bild-URL für das Haus abzurufen
                    $sql_img = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_typ IN ('Außenansicht', 'Innenansicht')";
                    $stmt_img = $conn->prepare($sql_img);
                    $stmt_img->bind_param("i", $haus_id);
                    $stmt_img->execute();
                    $result_img = $stmt_img->get_result();
                    $stmt_img->close();

                    // Ausgabe der Bilder in einer Slideshow
                    if ($result_img->num_rows > 0) {
                        echo '<div class="slideshow-container">';
                        while ($row_img = $result_img->fetch_assoc()) {
                            echo '<div class="slides">';
                            echo '<img src="' . htmlspecialchars($row_img['img_url'], ENT_QUOTES, 'UTF-8') . '" style="width:100%; height:100%;">';
                            echo '</div>';
                        }
                        echo '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
                        echo '<a class="next" onclick="plusSlides(1)">&#10095;</a>';
                        echo '</div>';
                    } else {
                        echo "Keine Bilder gefunden!";
                    }

                    // Haus-Beschreibung und Adresse anzeigen
                    echo '<p>' . htmlspecialchars($row['beschreibung'], ENT_QUOTES, 'UTF-8') . '</p>';
                    
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
                    
					echo '<p><b>Preis: </b>' . htmlspecialchars($row['preis'], ENT_QUOTES, 'UTF-8') . ' EUR</p>';
                    echo '<p><b>Max. Personen: </b>' . htmlspecialchars($row['personen'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<p><b>Adresse: </b>' . htmlspecialchars($row['adresse'], ENT_QUOTES, 'UTF-8') . ', ' . htmlspecialchars($row['land'], ENT_QUOTES, 'UTF-8') . '</p>';

                    // SQL-Abfrage, um den Lageplan abzurufen
                    $sql_lageplan = "SELECT img_url FROM img WHERE HAUS_ID = ? AND img_typ = 'Lageplan'";
                    $stmt_lageplan = $conn->prepare($sql_lageplan);
                    $stmt_lageplan->bind_param("i", $haus_id);
                    $stmt_lageplan->execute();
                    $result_lageplan = $stmt_lageplan->get_result();
                    $stmt_lageplan->close();

                    // Ausgabe des Lageplans
                    if ($result_lageplan->num_rows > 0) {
                        $row_lageplan = $result_lageplan->fetch_assoc();
                        echo '<div class="lageplan-container">';
                        echo '<img src="' . htmlspecialchars($row_lageplan['img_url'], ENT_QUOTES, 'UTF-8') . '" style="width:100%;">';
                        echo '</div>';
                    } else {
                        echo "Kein Lageplan gefunden!";
                    }
                }
            } else {
                echo "Keine Daten gefunden!";
            }
            $conn->close();
        } else {
            echo "Keine HAUS_ID übergeben!";
        }
        ?>
        <script src="js/slideshow.js"></script>
		
		
        <!-- Button zum Buchen der Unterkunft -->
		<form class="booking_button" action="unterkunft_buchen.php" method="get">
			<input type="hidden" name="HAUS_ID" value="<?php echo htmlspecialchars($haus_id); ?>">
			<button type="submit">Unterkunft Buchen</button>
		</form>
    </main>

    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
