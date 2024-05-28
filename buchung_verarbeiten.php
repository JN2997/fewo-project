<?php
session_start();
include 'db_connect.php'; 

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); 
    exit; 
}

// Überprüfen, ob das Formular mit der POST-Methode gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daten aus dem Formular abrufen
    $haus_id = isset($_POST['haus_id']) ? $_POST['haus_id'] : null; // Abrufen der Haus-ID
    $anreise = isset($_POST['anreise']) ? $_POST['anreise'] : null; // Abrufen des Anreisedatums
    $abreise = isset($_POST['abreise']) ? $_POST['abreise'] : null; // Abrufen des Abreisedatums
    $user_id = $_SESSION['USER_ID']; // Abrufen der Benutzer-ID aus der Sitzung

    // Überprüfen, ob alle erforderlichen Angaben vorhanden sind
    if (!$haus_id || !$anreise || !$abreise) {
        echo "Fehlende Angaben."; 
        exit; 
    }

    // Validierung der HAUS_ID
    $stmt = $conn->prepare("SELECT preis FROM haus WHERE HAUS_ID = ?"); // SQL-Abfrage zum Abrufen des Preises pro Nacht
    $stmt->bind_param("i", $haus_id); // Binden der Haus-ID als Parameter
    $stmt->execute(); // Ausführen der Abfrage
    $stmt->bind_result($preis_pro_nacht); // Binden des Ergebnisses an die Variable $preis_pro_nacht
    $stmt->fetch(); // Abrufen des Ergebnisses
    $stmt->close(); 

    // Überprüfen, ob die Haus-ID gültig ist
    if (!$preis_pro_nacht) {
        echo "Ungültige HAUS_ID."; // Fehlermeldung, wenn die Haus-ID ungültig ist
        exit; // Beenden des Skripts
    }

    // Daten validieren und berechnen
    $anreise_date = new DateTime($anreise); // Erstellen eines DateTime-Objekts für das Anreisedatum
    $abreise_date = new DateTime($abreise); // Erstellen eines DateTime-Objekts für das Abreisedatum
    $interval = $anreise_date->diff($abreise_date); // Berechnen des Zeitintervalls zwischen Anreise und Abreise und speichern als Int
    $tage = $interval->days; // Abrufen der Anzahl der Tage und befüllen der Variable tage

    $gesamtpreis = $tage * $preis_pro_nacht; // Berechnen des Gesamtpreises

    // Buchung in die Datenbank einfügen
    $stmt = $conn->prepare("INSERT INTO buchungen (USER_ID, HAUS_ID, arrival, departure, gesamtpreis) VALUES (?, ?, ?, ?, ?)"); // SQL-Abfrage zum Einfügen der Buchung
    $stmt->bind_param("iissi", $user_id, $haus_id, $anreise, $abreise, $gesamtpreis); // Binden der Parameter

    // Überprüfen, ob die Buchung erfolgreich war
    if ($stmt->execute()) {
        // Speichern der Buchungsdetails in der Sitzung
        $_SESSION['buchung_details'] = [
            'haus_id' => $haus_id,
            'anreise' => $anreise,
            'abreise' => $abreise,
            'gesamtpreis' => $gesamtpreis
        ];
        header('Location: buchung_erfolgreich.php'); // Umleitung zur Erfolgsseite
        exit; 
    } else {
        echo "Fehler bei der Buchung: " . $stmt->error; // Fehlermeldung bei einem Fehler
    }

    $stmt->close(); // Schließen des Statements
    $conn->close(); // Schließen der Datenbankverbindung
}
?>
