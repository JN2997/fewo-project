<?php
session_start();
include 'db_connect.php'; 
include 'auth_nav.php'; 
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php'); 

// Funktion zum Abrufen aller Häuser
function get_all_houses($conn) {
    $sql = "SELECT HAUS_ID, name, personen, land, adresse, preis, beschreibung, USER_ID FROM haus ORDER BY HAUS_ID"; // SQL-Abfrage zur Auswahl aller Häuser
    $result = $conn->query($sql); // Ausführen der Abfrage
    return $result; // Rückgabe des Abfrageergebnisses
}

// Funktion zum Aktualisieren eines Hauses
function update_house($conn, $haus_id, $name, $personen, $land, $adresse, $preis, $beschreibung) {
    $sql = "UPDATE haus SET name = ?, personen = ?, land = ?, adresse = ?, preis = ?, beschreibung = ? WHERE HAUS_ID = ?"; // SQL-Abfrage zur Aktualisierung eines Hauses
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("sissssi", $name, $personen, $land, $adresse, $preis, $beschreibung, $haus_id); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Funktion zum Löschen eines Hauses, inklusive zugehöriger Bilder und Tag-Zuordnungen
function delete_house($conn, $haus_id) {
    try {
        // Bilder löschen
        $sql = "DELETE FROM img WHERE HAUS_ID = ?";
        $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
        $stmt->bind_param("i", $haus_id); // Parameter an die Abfrage binden
        $stmt->execute(); // Ausführen der Abfrage
        $stmt->close(); // Schließen der Abfrage

        // Tag-Zuordnungen löschen
        $sql = "DELETE FROM tag_haus_relation WHERE HAUS_ID = ?";
        $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
        $stmt->bind_param("i", $haus_id); // Parameter an die Abfrage binden
        $stmt->execute(); // Ausführen der Abfrage
        $stmt->close(); // Schließen der Abfrage

        // Haus löschen
        $sql = "DELETE FROM haus WHERE HAUS_ID = ?";
        $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
        $stmt->bind_param("i", $haus_id); // Parameter an die Abfrage binden
        $stmt->execute(); // Ausführen der Abfrage
        $stmt->close(); // Schließen der Abfrage

        return true; // Erfolgreiches Löschen
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Fehler: Es existieren noch Buchungen zu diesem Haus, bitte löschen Sie diese zuerst.'); window.location.href='verwaltung_haeuser.php';</script>";
    }
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Überprüfen, ob das Formular mit der POST-Methode gesendet wurde
    if (isset($_POST['action'])) { // Überprüfen, ob das 'action'-Feld im Formular gesetzt ist
        switch ($_POST['action']) {
            case 'update': // Falls die Aktion 'update' ist
                update_house($conn, $_POST['haus_id'], $_POST['name'], $_POST['personen'], $_POST['land'], $_POST['adresse'], $_POST['preis'], $_POST['beschreibung']); // Haus aktualisieren
                break;
            case 'delete': // Falls die Aktion 'delete' ist
					delete_house($conn, $_POST['haus_id']); // Haus löschen
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil - Hausverwaltung</title>
    <!-- CSS Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Aufrufe -->
    <script src="js/popup.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        textarea {
            width: 100%;
            height: 80px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a> <!-- Logo und Link zur Startseite -->
        </div>
        <nav class="menu">
            <?php display_menu(); ?> <!-- Menü basierend auf der Benutzerrolle anzeigen -->
        </nav>
    </header>
    <!-- Hauptinhalt -->
    <main>
        <!-- Tabelle mit bestehenden Häusern -->
        <table>
            <caption><h1>Objekt-Verwaltung</h1></caption>
            <thead>
                <tr>
                    <th>Haus-ID</th>
                    <th style="width: 150px;">Name</th>
                    <th style="width: 70px;">Personen</th>
                    <th>Land</th>
                    <th>Adresse</th>
                    <th style="width: 100px;">Preis</th>
                    <th>Beschreibung</th>
                    <th>User</th>
                    <th style="width: 150px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $houses = get_all_houses($conn); // Abrufen aller Häuser aus der Datenbank
                if ($houses->num_rows > 0) { // Überprüfen, ob Häuser vorhanden sind
                    while($row = $houses->fetch_assoc()) { // Durchlaufen aller Häuser
                        echo "<tr>";
                        echo "<td>" . $row['HAUS_ID'] . "</td>";
                        // Formular für die Aktualisierung des Hauses
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='action' value='update'>"; // Verstecktes Feld zur Angabe der Aktion (aktualisieren)
                        echo "<input type='hidden' name='haus_id' value='" . $row['HAUS_ID'] . "'>"; // Verstecktes Feld zur Angabe der Haus-ID
                        echo "<td><input type='text' name='name' value='" . $row['name'] . "' required></td>"; // Eingabefeld für den Namen
                        echo "<td><input type='number' name='personen' value='" . $row['personen'] . "' required></td>"; // Eingabefeld für die Anzahl der Personen
                        echo "<td><input type='text' name='land' value='" . $row['land'] . "' required></td>"; // Eingabefeld für das Land
                        echo "<td><input type='text' name='adresse' value='" . $row['adresse'] . "' required></td>"; // Eingabefeld für die Adresse
                        echo "<td><input type='number' name='preis' value='" . $row['preis'] . "' required></td>"; // Eingabefeld für den Preis
                        echo "<td><textarea name='beschreibung' required>" . $row['beschreibung'] . "</textarea></td>"; // Textarea für die Beschreibung
                        echo "<td>" . $row['USER_ID'] . "</td>"; // Anzeige der Benutzer-ID
                        echo "<td>";
                        // Aktualisieren-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie die Daten aktualisieren möchten?\");'>Aktualisieren</button>";
                        echo "</form>";
                        // Formular für das Löschen des Hauses
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>"; // Verstecktes Feld zur Angabe der Aktion (löschen)
                        echo "<input type='hidden' name='haus_id' value='" . $row['HAUS_ID'] . "'>"; // Verstecktes Feld zur Angabe der Haus-ID
                        // Löschen-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie dieses Haus löschen möchten?\");'>Löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Keine Häuser gefunden</td></tr>"; // Nachricht, wenn keine Häuser gefunden wurden
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
