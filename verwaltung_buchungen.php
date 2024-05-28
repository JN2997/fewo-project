<?php
session_start(); 
include 'db_connect.php'; 
include 'auth_nav.php'; 
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php'); 

// Funktion zum Abrufen aller Buchungen
function get_all_buchungen($conn) {
    $sql = "SELECT BUCHUNG_ID, USER_ID, HAUS_ID, gesamtpreis, arrival, departure FROM buchungen ORDER BY BUCHUNG_ID"; // SQL-Abfrage zur Auswahl aller Buchungen
    $result = $conn->query($sql); // Ausführen der Abfrage
    return $result; // Rückgabe des Abfrageergebnisses
}

// Funktion zum Löschen einer Buchung
function delete_buchung($conn, $buchung_id) {
    $sql = "DELETE FROM buchungen WHERE BUCHUNG_ID = ?"; // SQL-Abfrage zum Löschen einer Buchung
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("i", $buchung_id); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Überprüfen, ob das Formular mit der POST-Methode gesendet wurde
    if (isset($_POST['action']) && $_POST['action'] == 'delete') { // Überprüfen, ob die Aktion 'delete' ist
        delete_buchung($conn, $_POST['buchung_id']); // Buchung löschen
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil - Buchungen Verwaltung</title>
    <!-- CSS-Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript-Aufrufe -->
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
        <!-- Tabelle mit bestehenden Buchungen -->
        <table>
            <caption><h1>Buchungs-Verwaltung</h1></caption>
            <thead>
                <tr>
                    <th>Buchungs ID</th>
                    <th>User ID</th>
                    <th>Haus ID</th>
                    <th>Gesamtpreis</th>
                    <th>Ankunft</th>
                    <th>Abreise</th>
                    <th style="width: 180px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $buchungen = get_all_buchungen($conn); // Abrufen aller Buchungen aus der Datenbank
                if ($buchungen->num_rows > 0) { // Überprüfen, ob Buchungen vorhanden sind
                    while($row = $buchungen->fetch_assoc()) { // Durchlaufen aller Buchungen
                        echo "<tr>";
                        echo "<td>" . $row['BUCHUNG_ID'] . "</td>"; // Anzeige der Buchungs-ID
                        echo "<td>" . $row['USER_ID'] . "</td>"; // Anzeige der Benutzer-ID
                        echo "<td>" . $row['HAUS_ID'] . "</td>"; // Anzeige der Haus-ID
                        echo "<td>" . $row['gesamtpreis'] . "</td>"; // Anzeige des Gesamtpreises
                        echo "<td>" . $row['arrival'] . "</td>"; // Anzeige des Ankunftsdatums
                        echo "<td>" . $row['departure'] . "</td>"; // Anzeige des Abreisedatums
                        echo "<td>";
                        // Formular für das Löschen der Buchung
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>"; // Verstecktes Feld zur Angabe der Aktion (löschen)
                        echo "<input type='hidden' name='buchung_id' value='" . $row['BUCHUNG_ID'] . "'>"; // Verstecktes Feld zur Angabe der Buchungs-ID
                        // Löschen-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diese Buchung löschen möchten?\");'>Buchung löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Keine Buchungen gefunden</td></tr>"; // Nachricht, wenn keine Buchungen gefunden wurden
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
