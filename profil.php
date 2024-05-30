<?php 
session_start(); // Startet die Session, um auf Session-Variablen zugreifen zu können
include 'db_connect.php'; // Stellt die Verbindung zur Datenbank her
include 'auth_nav.php'; // Beinhaltet Authentifizierungs- und Navigationslogik
exclude_user_Roles(['guest', 'Admin'], 'index.php'); // Schließt bestimmte Benutzerrollen von dieser Seite aus

// Benutzerinformationen aus der Datenbank abrufen
$user_id = $_SESSION['USER_ID']; // Holt die Benutzer-ID aus der Session
$query = $conn->prepare("SELECT forname, surname, email FROM users WHERE USER_ID = ?"); // Bereitet die SQL-Abfrage vor
$query->bind_param('i', $user_id); // Bindet die Benutzer-ID als Parameter an die Abfrage
$query->execute(); // Führt die Abfrage aus
$query->bind_result($forname, $surname, $email); // Bindet die Ergebnisvariablen an
$query->fetch(); // Holt das Ergebnis aus der Abfrage
$query->close(); // Schließt die Abfrage

$error = ''; // Initialisiert die Fehlermeldung
$success = ''; // Initialisiert die Erfolgsmeldung

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) { // Überprüft, ob das Update-Formular gesendet wurde
        $new_forname = $_POST['forname']; // Holt den neuen Vornamen aus dem Formular
        $new_surname = $_POST['surname']; // Holt den neuen Nachnamen aus dem Formular
        $new_email = $_POST['email']; // Holt die neue E-Mail aus dem Formular
        $current_password = $_POST['current_password']; // Holt das aktuelle Passwort aus dem Formular
        $new_password = $_POST['new_password']; // Holt das neue Passwort aus dem Formular
        $confirm_password = $_POST['confirm_password']; // Holt die Passwortbestätigung aus dem Formular

        // E-Mail-Überprüfung
        $email_query = $conn->prepare("SELECT USER_ID FROM users WHERE email = ? AND USER_ID != ?"); // Bereitet die SQL-Abfrage vor
        $email_query->bind_param('si', $new_email, $user_id); // Bindet die Parameter an die Abfrage
        $email_query->execute(); // Führt die Abfrage aus
        $email_query->store_result(); // Speichert das Ergebnis der Abfrage
        if ($email_query->num_rows > 0) { // Überprüft, ob die E-Mail bereits existiert
            $error = 'Die E-Mail-Adresse ist bereits vergeben.'; // Setzt die Fehlermeldung
        } else {
            $email_query->close(); // Schließt die E-Mail-Abfrage

            // Wenn das Passwort geändert werden soll
            if (!empty($new_password)) {
                // Passwortüberprüfung
                $password_query = $conn->prepare("SELECT password FROM users WHERE USER_ID = ?"); // Bereitet die SQL-Abfrage vor
                $password_query->bind_param('i', $user_id); // Bindet die Benutzer-ID als Parameter an die Abfrage
                $password_query->execute(); // Führt die Abfrage aus
                $password_query->bind_result($hashed_password); // Bindet das Ergebnis an die Variable
                $password_query->fetch(); // Holt das Ergebnis aus der Abfrage
                $password_query->close(); // Schließt die Passwort-Abfrage

                if (!password_verify($current_password, $hashed_password)) { // Überprüft, ob das aktuelle Passwort korrekt ist
                    $error = 'Das aktuelle Passwort ist falsch.'; // Setzt die Fehlermeldung
                } elseif ($new_password !== $confirm_password) { // Überprüft, ob das neue Passwort und die Bestätigung übereinstimmen
                    $error = 'Die Passwörter stimmen nicht überein.'; // Setzt die Fehlermeldung
                } elseif (password_verify($new_password, $hashed_password)) { // Überprüft, ob das neue Passwort nicht mit dem alten übereinstimmt
                    $error = 'Das neue Passwort darf nicht mit dem alten Passwort übereinstimmen.'; // Setzt die Fehlermeldung
                } else {
                    // Neues Passwort hashen und Benutzerinformationen aktualisieren
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hasht das neue Passwort
                    $update_query = $conn->prepare("UPDATE users SET forname = ?, surname = ?, email = ?, password = ? WHERE USER_ID = ?"); // Bereitet die SQL-Abfrage vor
                    $update_query->bind_param('ssssi', $new_forname, $new_surname, $new_email, $new_hashed_password, $user_id); // Bindet die Parameter an die Abfrage
                    if ($update_query->execute()) { // Führt die Abfrage aus
                        $success = 'Profilinformationen erfolgreich aktualisiert.'; // Setzt die Erfolgsmeldung
                    } else {
                        $error = 'Es gab ein Problem beim Aktualisieren der Profilinformationen.'; // Setzt die Fehlermeldung
                    }
                    $update_query->close(); // Schließt die Update-Abfrage
                }
            } else {
                // Benutzerinformationen ohne Passwortänderung aktualisieren
                $update_query = $conn->prepare("UPDATE users SET forname = ?, surname = ?, email = ? WHERE USER_ID = ?"); // Bereitet die SQL-Abfrage vor
                $update_query->bind_param('sssi', $new_forname, $new_surname, $new_email, $user_id); // Bindet die Parameter an die Abfrage
                if ($update_query->execute()) { // Führt die Abfrage aus
                    $success = 'Profilinformationen erfolgreich aktualisiert.'; // Setzt die Erfolgsmeldung
                } else {
                    $error = 'Es gab ein Problem beim Aktualisieren der Profilinformationen.'; // Setzt die Fehlermeldung
                }
                $update_query->close(); // Schließt die Update-Abfrage
            }
        }
    } elseif (isset($_POST['delete'])) { // Überprüft, ob das Löschformular gesendet wurde
        try {
            // Löschen des Benutzers
            $delete_query = $conn->prepare("DELETE FROM users WHERE USER_ID = ?");
            $delete_query->bind_param('i', $user_id);
            $delete_query->execute();
            $delete_query->close();
            
            // Sitzung beenden und zur Startseite weiterleiten
            session_destroy();
            header('Location: index.php');
            exit();
        } catch (mysqli_sql_exception $e) {
             echo "<script>alert('Fehler: Sie haben noch offene Buchungen oder bestehende Ferienhäuser, bitte wenden Sie sich an einen Administrator.'); window.location.href='profil.php';</script>";
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
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a> 
        </div>
        <nav class="menu">
            <?php display_menu(); ?> <!-- Menü anzeigen -->
        </nav>
    </header>
    <!-- Hauptinhalt -->
    <main>
        <h1>Profil bearbeiten</h1> <!-- Hauptüberschrift -->
        <?php if ($error): ?> <!-- Wenn es einen Fehler gibt, anzeigen -->
            <p style="color: red;"><?php echo $error; ?></p> <!-- Fehlernachricht in roter Schrift -->
        <?php endif; ?>
        <?php if ($success): ?> <!-- Wenn es eine Erfolgsmeldung gibt, anzeigen -->
            <p style="color: green;"><?php echo $success; ?></p> <!-- Erfolgsmeldung in grüner Schrift -->
        <?php endif; ?>
        <form action="profil.php" method="post"> 
            <label for="forname">Vorname:</label>
            <input type="text" name="forname" value="<?php echo htmlspecialchars($forname); ?>" required> <!-- Eingabefeld für den Vornamen -->
            <label for="surname">Nachname:</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required> <!-- Eingabefeld für den Nachnamen -->
            <label for="email">E-Mail:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required> <!-- Eingabefeld für die E-Mail-Adresse -->
            <label for="current_password">Aktuelles Passwort (nur bei Passwortänderung erforderlich):</label>
            <input type="password" name="current_password"> <!-- Eingabefeld für das aktuelle Passwort -->
            <label for="new_password">Neues Passwort:</label>
            <input type="password" name="new_password"> <!-- Eingabefeld für das neue Passwort -->
            <label for="confirm_password">Neues Passwort bestätigen:</label>
            <input type="password" name="confirm_password"> <!-- Eingabefeld zur Bestätigung des neuen Passworts -->
            <button type="submit" name="update">Profil aktualisieren</button><button type="submit" name="delete">Profil löschen</button>
        </form>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p> 
    </footer>
</body>
</html>