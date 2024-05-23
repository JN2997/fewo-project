<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daten aus dem Formular abrufen
    $haus_id = isset($_POST['haus_id']) ? intval($_POST['haus_id']) : null;
    $anreise = isset($_POST['anreise']) ? $_POST['anreise'] : null;
    $abreise = isset($_POST['abreise']) ? $_POST['abreise'] : null;
    $user_id = $_SESSION['USER_ID'];

    if (!$haus_id || !$anreise || !$abreise) {
        echo "Fehlende Angaben.";
        exit;
    }

    // Validierung der HAUS_ID
    $stmt = $conn->prepare("SELECT preis FROM haus WHERE HAUS_ID = ?");
    $stmt->bind_param("i", $haus_id);
    $stmt->execute();
    $stmt->bind_result($preis_pro_nacht);
    $stmt->fetch();
    $stmt->close();

    if (!$preis_pro_nacht) {
        echo "Ungültige HAUS_ID.";
        exit;
    }

    // Daten validieren und berechnen
    $anreise_date = new DateTime($anreise);
    $abreise_date = new DateTime($abreise);
    $interval = $anreise_date->diff($abreise_date);
    $tage = $interval->days;

    $gesamtpreis = $tage * $preis_pro_nacht;

    // Buchung in die Datenbank einfügen
    $stmt = $conn->prepare("INSERT INTO buchungen (USER_ID, HAUS_ID, arrival, departure, gesamtpreis) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $user_id, $haus_id, $anreise, $abreise, $gesamtpreis);

    if ($stmt->execute()) {
        $_SESSION['buchung_details'] = [
            'haus_id' => $haus_id,
            'anreise' => $anreise,
            'abreise' => $abreise,
            'gesamtpreis' => $gesamtpreis
        ];
        header('Location: buchung_erfolgreich.php');
        exit;
    } else {
        echo "Fehler bei der Buchung: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
