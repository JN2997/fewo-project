<?php
session_start();
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php');

// Funktion zum Abrufen aller Häuser
function get_all_houses($conn) {
    $sql = "SELECT HAUS_ID, name, personen, land, adresse, preis, beschreibung, USER_ID FROM haus ORDER BY HAUS_ID";
    $result = $conn->query($sql);
    return $result;
}

// Funktion zum Aktualisieren eines Hauses
function update_house($conn, $haus_id, $name, $personen, $land, $adresse, $preis, $beschreibung) {
    $sql = "UPDATE haus SET name = ?, personen = ?, land = ?, adresse = ?, preis = ?, beschreibung = ? WHERE HAUS_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $name, $personen, $land, $adresse, $preis, $beschreibung, $haus_id);
    return $stmt->execute();
}

// Funktion zum Löschen eines Hauses
function delete_house($conn, $haus_id) {
    $sql = "DELETE FROM haus WHERE HAUS_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $haus_id);
    return $stmt->execute();
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                update_house($conn, $_POST['haus_id'], $_POST['name'], $_POST['personen'], $_POST['land'], $_POST['adresse'], $_POST['preis'], $_POST['beschreibung']);
                break;
            case 'delete':
                delete_house($conn, $_POST['haus_id']);
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
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
            <?php display_menu(); ?>
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
                $houses = get_all_houses($conn);
                if ($houses->num_rows > 0) {
                    while($row = $houses->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['HAUS_ID'] . "</td>";
                        // Formular für die Aktualisierung des Hauses
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='action' value='update'>";
                        echo "<input type='hidden' name='haus_id' value='" . $row['HAUS_ID'] . "'>";
                        echo "<td><input type='text' name='name' value='" . $row['name'] . "' required></td>";
                        echo "<td><input type='number' name='personen' value='" . $row['personen'] . "' required></td>";
                        echo "<td><input type='text' name='land' value='" . $row['land'] . "' required></td>";
                        echo "<td><input type='text' name='adresse' value='" . $row['adresse'] . "' required></td>";
                        echo "<td><input type='number' name='preis' value='" . $row['preis'] . "' required></td>";
                        echo "<td><textarea name='beschreibung' required>" . $row['beschreibung'] . "</textarea></td>";
                        echo "<td>" . $row['USER_ID'] . "</td>";
                        echo "<td>";
                        // Aktualisieren Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie die Daten aktualisieren möchten?\");'>Aktualisieren</button>";
                        echo "</form>";
                        // Formular für das Löschen des Hauses
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>";
                        echo "<input type='hidden' name='haus_id' value='" . $row['HAUS_ID'] . "'>";
                        // Löschen Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie dieses Haus löschen möchten?\");'>Löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Keine Häuser gefunden</td></tr>";
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
