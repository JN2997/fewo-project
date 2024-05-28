<?php
session_start(); 
include 'db_connect.php'; 
include 'auth_nav.php'; 
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php'); 

// Funktion zum Abrufen aller Tags
function get_all_tags($conn) {
    $sql = "SELECT TAG_ID, tag_wert FROM tags ORDER BY TAG_ID"; // SQL-Abfrage zur Auswahl aller Tags
    $result = $conn->query($sql); // Ausführen der Abfrage
    return $result; // Rückgabe des Abfrageergebnisses
}

// Funktion zum Hinzufügen eines neuen Tags
function add_tag($conn, $tag_wert) {
    $sql = "INSERT INTO tags (tag_wert) VALUES (?)"; // SQL-Abfrage zum Hinzufügen eines neuen Tags
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("s", $tag_wert); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Funktion zum Aktualisieren eines Tags
function update_tag($conn, $tag_id, $tag_wert) {
    $sql = "UPDATE tags SET tag_wert = ? WHERE TAG_ID = ?"; // SQL-Abfrage zur Aktualisierung eines Tags
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("si", $tag_wert, $tag_id); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Funktion zum Löschen eines Tags
function delete_tag($conn, $tag_id) {
    $sql = "DELETE FROM tags WHERE TAG_ID = ?"; // SQL-Abfrage zum Löschen eines Tags
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("i", $tag_id); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Überprüfen, ob das Formular mit der POST-Methode gesendet wurde
    if (isset($_POST['action'])) { // Überprüfen, ob das 'action'-Feld im Formular gesetzt ist
        switch ($_POST['action']) {
            case 'add': // Falls die Aktion 'add' ist
                add_tag($conn, $_POST['tag_wert']); // Neuen Tag hinzufügen
                break;
            case 'update': // Falls die Aktion 'update' ist
                update_tag($conn, $_POST['tag_id'], $_POST['tag_wert']); // Tag aktualisieren
                break;
            case 'delete': // Falls die Aktion 'delete' ist
                delete_tag($conn, $_POST['tag_id']); // Tag löschen
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
    <title>Ihr Ferien Domizil</title>
    <!-- CSS Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Aufrufe -->
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
        <!-- Tabelle mit bestehenden Tags und Formular zum Hinzufügen eines neuen Tags -->
        <table>
            <caption><h1>Tag-Verwaltung</h1></caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tag</th>
                    <th style="width: 200px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <!-- Formular zum Hinzufügen eines neuen Tags -->
                <tr>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add"> <!-- Verstecktes Feld zur Angabe der Aktion (hinzufügen) -->
                        <td></td> <!-- Leeres Feld für die ID (wird automatisch generiert) -->
                        <td>
                            <input type="text" name="tag_wert" required> <!-- Eingabefeld für den neuen Tag -->
                        </td>
                        <td>
                            <button type="submit">Hinzufügen</button> <!-- Hinzufügen-Button -->
                        </td>
                    </form>
                </tr>

                <?php
                $tags = get_all_tags($conn); // Abrufen aller Tags aus der Datenbank
                if ($tags->num_rows > 0) { // Überprüfen, ob Tags vorhanden sind
                    while($row = $tags->fetch_assoc()) { // Durchlaufen aller Tags
                        echo "<tr>";
                        echo "<td>" . $row['TAG_ID'] . "</td>";
                        // Formular für die Aktualisierung des Tags
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='action' value='update'>"; // Verstecktes Feld zur Angabe der Aktion (aktualisieren)
                        echo "<input type='hidden' name='tag_id' value='" . $row['TAG_ID'] . "'>"; // Verstecktes Feld zur Angabe der Tag-ID
                        echo "<td><input type='text' name='tag_wert' value='" . $row['tag_wert'] . "' required></td>"; // Eingabefeld für den Tag-Wert
                        echo "<td>";
                        // Aktualisieren-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Tag aktualisieren möchten?\");'>Aktualisieren</button>";
                        echo "</form>";
                        // Formular für das Löschen des Tags
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>"; // Verstecktes Feld zur Angabe der Aktion (löschen)
                        echo "<input type='hidden' name='tag_id' value='" . $row['TAG_ID'] . "'>"; // Verstecktes Feld zur Angabe der Tag-ID
                        // Löschen-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Tag löschen möchten?\");'>Löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Keine Tags gefunden</td></tr>"; // Nachricht, wenn keine Tags gefunden wurden
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
