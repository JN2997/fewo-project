<?php
include 'db_connect.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['USER_ID'])) {
    echo "Sie müssen eingeloggt sein, um diese Aktion auszuführen.";
    exit;
}

$user_id = $_SESSION['USER_ID'];

$haeuser = [];
$haus = null;
$buchungen = [];

// Haus-Daten abrufen
if (isset($_GET['haus_id'])) {
    $haus_id = $_GET['haus_id'];
    $stmt = $conn->prepare("SELECT name FROM haus WHERE HAUS_ID=? AND USER_ID=?");
    $stmt->bind_param("ii", $haus_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $haus = $result->fetch_assoc();
    $stmt->close();

    if ($haus) {
        // Buchungen für das ausgewählte Haus abrufen
        $stmt = $conn->prepare("SELECT b.arrival, b.departure, b.gesamtpreis, u.surname, u.email 
                                FROM buchungen b 
                                JOIN users u ON b.USER_ID = u.USER_ID 
                                WHERE b.HAUS_ID=?
								ORDER BY b.arrival ASC");
        $stmt->bind_param("i", $haus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $buchungen[] = $row;
        }
        $stmt->close();
    }
} else {
    // Liste der Häuser anzeigen
    $stmt = $conn->prepare("SELECT h.HAUS_ID, h.name, COUNT(b.BUCHUNG_ID) as buchung_count 
                            FROM haus h 
                            LEFT JOIN buchungen b ON h.HAUS_ID = b.HAUS_ID 
                            WHERE h.USER_ID=? 
                            GROUP BY h.HAUS_ID, h.name");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $haeuser[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>
<?php if (!isset($_GET['haus_id'])): ?>
<div class="haus_list">
    <h3>Wählen Sie ein Haus, um die Buchungen zu sehen:</h3>
    <ul>
        <?php if (!empty($haeuser)): ?>
            <?php foreach ($haeuser as $hausItem): ?>
                <li>
                    <a href="?page=bookings&haus_id=<?php echo $hausItem['HAUS_ID']; ?>">
                        <?php echo htmlspecialchars($hausItem['name']); ?> (<?php echo $hausItem['buchung_count']; ?> Buchungen)
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Keine Häuser vorhanden.</li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>

<?php if ($haus): ?>
    <div class="haus_details">
        <?php if (!empty($buchungen)): ?>
            <table>
				<caption><h3>Buchungen für <?php echo htmlspecialchars($haus['name']); ?>:</h3></caption> 
                <thead>
                <tr>
                    <th>Nachname</th>
                    <th>Email</th>
                    <th>Gesamtpreis</th>
                    <th>Anreisedatum</th>
                    <th>Abreisedatum</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($buchungen as $buchung): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($buchung['surname']); ?></td>
                        <td><?php echo htmlspecialchars($buchung['email']); ?></td>
                        <td><?php echo htmlspecialchars($buchung['gesamtpreis']) . ' EUR'; ?></td>
                        <td><?php echo htmlspecialchars($buchung['arrival']); ?></td>
                        <td><?php echo htmlspecialchars($buchung['departure']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Keine Buchungen vorhanden.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
