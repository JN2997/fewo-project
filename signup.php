<?php
include 'db_connect.php'; // Bindet dein Datenbankverbindungsskript ein

session_start(); // Startet die Session, um Session-Variablen zu verwenden

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Daten aus dem Formular extrahieren mit Sicherheitsüberprüfungen
    $forname = $conn->real_escape_string($_POST['forname']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['psw'];
    $confirm_password = $_POST['confirm_psw'];
	$redirect_url = $_POST['redirect_url'];

    // Überprüfen, ob die beiden Passwörter übereinstimmen
    if ($password !== $confirm_password) {
        echo "Passwörter stimmen nich überein.";
        exit;
    }

    // Hash das Passwort, bevor es in die Datenbank gespeichert wird
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bestimme die Rolle basierend auf der Checkbox
    $role = isset($_POST['is_vermieter']) && $_POST['is_vermieter'] ? "Vermieter" : "Mieter";

    // Füge den neuen Benutzer in die Datenbank ein
    $sql = "INSERT INTO users (forname, surname, email, password, role) VALUES ('$forname', '$surname', '$email', '$hashed_password', '$role')";

	if ($conn->query($sql) === TRUE) {
			// Setzt eine Session-Variable, um auf der Indexseite eine Nachricht anzuzeigen
			$_SESSION['registration_success'] = 'Sie haben sich erfolgreich registriert und können sich jetzt einloggen.';
			header("Location: " . $redirect_url);
			
		} else {
			echo "Fehler: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
	} else {
		echo "Ungültige Anfrage.";
	}
?>
