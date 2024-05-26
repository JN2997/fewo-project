<?php
session_start();
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php');

// Funktion zum Abrufen aller Benutzer
function get_all_users($conn) {
    $sql = "SELECT USER_ID, forname, surname, email, role FROM users ORDER BY USER_ID";
    $result = $conn->query($sql);
    return $result;
}

// Funktion zum Aktualisieren eines Benutzers
function update_user($conn, $user_id, $forname, $surname, $email, $role) {
    $sql = "UPDATE users SET forname = ?, surname = ?, email = ?, role = ? WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $forname, $surname, $email, $role, $user_id);
    return $stmt->execute();
}

// Funktion zum Löschen eines Benutzers
function delete_user($conn, $user_id) {
    $sql = "DELETE FROM users WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                update_user($conn, $_POST['user_id'], $_POST['forname'], $_POST['surname'], $_POST['email'], $_POST['role']);
                break;
            case 'delete':
                delete_user($conn, $_POST['user_id']);
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
    <!-- CSS Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript Aufrufe -->
	<script src="js/popup.js"></script>
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
                $users = get_all_users($conn);
                if ($users->num_rows > 0) {
                    while($row = $users->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['USER_ID'] . "</td>";
                        // Formular für die Aktualisierung des Benutzers
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='action' value='update'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['USER_ID'] . "'>";
                        echo "<td><input type='text' name='forname' value='" . $row['forname'] . "' required></td>";
                        echo "<td><input type='text' name='surname' value='" . $row['surname'] . "' required></td>";
                        echo "<td><input type='email' name='email' value='" . $row['email'] . "' required></td>";
                        echo "<td>";
                        echo "<select name='role' required>";
                        echo "<option value='Mieter'" . ($row['role'] == 'Mieter' ? ' selected' : '') . ">Mieter</option>";
                        echo "<option value='Vermieter'" . ($row['role'] == 'Vermieter' ? ' selected' : '') . ">Vermieter</option>";
                        echo "<option value='Admin'" . ($row['role'] == 'Admin' ? ' selected' : '') . ">Admin</option>";
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";
                        // Aktualisieren Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie die Daten aktualisieren möchten?\");'>Aktualisieren</button>";
                        echo "</form>";
                        // Formular für das Löschen des Benutzers
                        echo "<form method='post' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='action' value='delete'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['USER_ID'] . "'>";
                        // Löschen Button
                        echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?\");'>Löschen</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Keine Benutzer gefunden</td></tr>";
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
