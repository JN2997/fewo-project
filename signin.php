<?php
	session_start();
	include 'db_connect.php';  // Bindet das Verbindungsskript ein
	
	 // Benutzereingaben bereinigen
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['psw'];
	$redirect_url = $_POST['redirect_url'];

    // SQL-Query zum Finden des Benutzers
    $sql = "SELECT USER_ID, email, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
	{
        // Benutzerdaten überprüfen
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) 
		{
            // Passwort ist korrekt, Login erfolgreich
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['USER_ID'] = $row['USER_ID'];
			$_SESSION['role'] = $row['role'];
			header("Location: " . $redirect_url);
        } else 
		{
            echo "Passwort oder Benutzername falsch";
        }
    } else 
	{
        echo "Benutzer nicht gefunden!";
    }

    $conn->close();


?>
