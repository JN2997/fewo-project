<?php
session_start();
include 'db_connect.php';  // Stelle sicher, dass die Verbindung zur Datenbank hergestellt ist

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extrahiere die Daten aus dem POST-Array
    $name = $_POST['name'];
    $adresse = $_POST['adresse'];
    $land = $_POST['land'];
    $beschreibung = $_POST['beschreibung'];
    $personen = $_POST['personen'];
    $preis = $_POST['preis'];
    $USER_ID = $_SESSION['USER_ID']; // User-ID aus der Session

    // Füge die Hausinformationen in die Datenbank ein
    $sql = "INSERT INTO haus (NAME, ADRESSE, LAND, BESCHREIBUNG, PERSONEN, PREIS, USER_ID) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdiid", $name, $adresse, $land, $beschreibung, $personen, $preis, $user_id);
    $stmt->execute();
    $last_id = $stmt->insert_id;

    // Bild-Upload-Verarbeitung
    foreach ($_FILES['bilder']['name'] as $key => $value) {
        $file_name = $_FILES['bilder']['name'][$key];
        $file_tmp = $_FILES['bilder']['tmp_name'][$key];
        $file_type = $_FILES['bilder']['type'][$key];
        $target_directory = "uploads/";
        $target_file = $target_directory . basename($file_name);

        if (move_uploaded_file($file_tmp, $target_file)) {
            $sql_img = "INSERT INTO img (IMG_URL, IMG_TYP, HAUS_ID) VALUES (?, ?, ?)";
            $stmt_img = $conn->prepare($sql_img);
            $stmt_img->bind_param("ssi", $target_file, $file_type, $last_id);
            $stmt_img->execute();
        }
    }

    echo "Das Haus wurde erfolgreich hinzugefügt!";
}
?>