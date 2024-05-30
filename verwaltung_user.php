<?php
session_start(); 
include 'db_connect.php'; 
include 'auth_nav.php'; 
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php'); 

// Funktion zum Abrufen aller Benutzer
function get_all_users($conn) {
    $sql = "SELECT USER_ID, forname, surname, email, role FROM users ORDER BY USER_ID"; // SQL-Abfrage zur Auswahl aller Benutzer
    $result = $conn->query($sql); // Ausführen der Abfrage
    return $result; // Rückgabe des Abfrageergebnisses
}

// Funktion zum Aktualisieren eines Benutzers
function update_user($conn, $user_id, $forname, $surname, $email, $role) {
    $sql = "UPDATE users SET forname = ?, surname = ?, email = ?, role = ? WHERE USER_ID = ?"; // SQL-Abfrage zur Aktualisierung eines Benutzers
    $stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
    $stmt->bind_param("ssssi", $forname, $surname, $email, $role, $user_id); // Parameter an die Abfrage binden
    return $stmt->execute(); // Ausführen der Abfrage
}

// Funktion zum Löschen eines Benutzers
function delete_user($conn, $user_id) {
	try {
		$sql = "DELETE FROM users WHERE USER_ID = ?"; // SQL-Abfrage zum Löschen eines Benutzers
		$stmt = $conn->prepare($sql); // Vorbereiten der Abfrage
		$stmt->bind_param("i", $user_id); // Parameter an die Abfrage binden
		return $stmt->execute(); // Ausführen der Abfrage
	} catch (mysqli_sql_exception $e) {
        echo "<script>alert('Fehler: Der Nutzer hat noch Buchungen oder Ferienhäuser, bitte löschen Sie diese zunächst.'); window.location.href='verwaltung_user.php';</script>";
        return false; // Fehler beim Löschen
    }
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                update_user($conn, $_POST['user_id'], $_POST['forname'], $_POST['surname'], $_POST['email'], $_POST['role']); // Benutzer aktualisieren
                break;
            case 'delete':
                delete_user($conn, $_POST['user_id']); // Benutzer löschen
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
    <title>Ihr Ferien Domizil - Benutzerverwaltung</title>
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
        <!-- Tabelle mit bestehenden Benutzern -->
        <table>
            <caption><h1>Benutzerverwaltung</h1></caption>
            <thead>
                <tr>
                    <th style="width: 25px;">ID</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th style="width: 200px;">Email</th>
                    <th style="width: 140px;">Rolle</th>
                    <th style="width: 200px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = get_all_users($conn); // Abrufen aller Benutzer aus der Datenbank
                if ($users->num_rows > 0) { // Überprüfen, ob Benutzer vorhanden sind
                    while($row = $users->fetch_assoc()) { // Durchlaufen aller Benutzer
                        echo "<tr>";
                        echo "<td>" . $row['USER_ID'] . "</td>";
                        // Formular für die Aktualisierung des Benutzers
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='action' value='update'>"; // Verstecktes Feld zur Angabe der Aktion (aktualisieren)
                        echo "<input type='hidden' name='user_id' value='" . $row['USER_ID'] . "'>"; // Verstecktes Feld zur Angabe der Benutzer-ID
                        echo "<td><input type='text' name='forname' value='" . $row['forname'] . "' required></td>"; // Vorname des Benutzers
                        echo "<td><input type='text' name='surname' value='" . $row['surname'] . "' required></td>"; // Nachname des Benutzers
                        echo "<td><input type='email' name='email' value='" . $row['email'] . "' required></td>"; // E-Mail des Benutzers
                        echo "<td>";
                        echo "<select name='role' required>"; // Dropdown zur Auswahl der Rolle des Benutzers
                        echo "<option value='Mieter'" . ($row['role'] == 'Mieter' ? ' selected' : '') . ">Mieter</option>";
                        echo "<option value='Vermieter'" . ($row['role'] == 'Vermieter' ? ' selected' : '') . ">Vermieter</option>";
                        echo "<option value='Admin'" . ($row['role'] == 'Admin' ? ' selected' : '') . ">Admin</option>";
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";
                        // Aktualisieren-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie die Daten aktualisieren möchten?\");'>Aktualisieren</button>";
                        echo "</form>";
                        // Formular für das Löschen des Benutzers
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>"; // Verstecktes Feld zur Angabe der Aktion (löschen)
                        echo "<input type='hidden' name='user_id' value='" . $row['USER_ID'] . "'>"; // Verstecktes Feld zur Angabe der Benutzer-ID
                        // Löschen-Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?\");'>Löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Keine Benutzer gefunden</td></tr>"; // Nachricht, wenn keine Benutzer gefunden wurden
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
