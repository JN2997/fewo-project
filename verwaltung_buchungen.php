<?php
session_start();
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php');

// Funktion zum Abrufen aller Buchungen
function get_all_buchungen($conn) {
    $sql = "SELECT BUCHUNG_ID, USER_ID, HAUS_ID, gesamtpreis, arrival, departure FROM buchungen ORDER BY BUCHUNG_ID";
    $result = $conn->query($sql);
    return $result;
}

// Funktion zum Löschen einer Buchung
function delete_buchung($conn, $buchung_id) {
    $sql = "DELETE FROM buchungen WHERE BUCHUNG_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $buchung_id);
    return $stmt->execute();
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        delete_buchung($conn, $_POST['buchung_id']);
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil - Buchungen Verwaltung</title>
    <!-- CSS Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Aufrufe -->
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
                $buchungen = get_all_buchungen($conn);
                if ($buchungen->num_rows > 0) {
                    while($row = $buchungen->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['BUCHUNG_ID'] . "</td>";
                        echo "<td>" . $row['USER_ID'] . "</td>";
                        echo "<td>" . $row['HAUS_ID'] . "</td>";
                        echo "<td>" . $row['gesamtpreis'] . "</td>";
                        echo "<td>" . $row['arrival'] . "</td>";
                        echo "<td>" . $row['departure'] . "</td>";
                        echo "<td>";
                        // Formular für das Löschen der Buchung
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>";
                        echo "<input type='hidden' name='buchung_id' value='" . $row['BUCHUNG_ID'] . "'>";
                        // Löschen Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diese Buchung löschen möchten?\");'>Buchung löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Keine Buchungen gefunden</td></tr>";
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
