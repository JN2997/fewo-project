<?php
include 'db_connect.php';

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
<!-- Überprüft, ob die URL-Parameter haus_id nicht gesetzt sind. Wenn sie nicht gesetzt sind, wird der folgende Block ausgeführt. -->
<div class="haus_list">
    <h3>Wählen Sie ein Haus, um die Buchungen zu sehen:</h3>
    <ul>
        <?php if (!empty($haeuser)): ?>
            <!-- Überprüft, ob die Variable $haeuser nicht leer ist. Wenn true, dann wird die Schleife ausgeführt -->
            <?php foreach ($haeuser as $hausItem): ?>
                <!-- Iteriert über jedes Element im Array $haeuser und weist es der Variable $hausItem zu. -->
                <li>
					<a href="?page=bookings&haus_id=<?php echo $hausItem['HAUS_ID']; ?>"><!-- Erstellt Link, der Benutzer zur Seite führt, wo die Buchungen für das ausgewählte Haus angezeigt werden. HAUS_ID ist URL Parameter. -->
					<?php echo htmlspecialchars($hausItem['name']); ?> (<?php echo $hausItem['buchung_count']; ?> Buchungen)<!-- Gibt den Namen des Hauses und die Anzahl der Buchungen mit "htmlspecialchars" aus. -->
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Wenn $haeuser leer ist, wird diese Nachricht angezeigt. -->
            <li>Keine Häuser vorhanden.</li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>

<?php if ($haus): ?>
    <!-- Überprüft, ob die Variable $haus gesetzt ist. Wenn sie gesetzt ist, wird der folgende Block ausgeführt. -->
    <div class="haus_details">
        <?php if (!empty($buchungen)): ?>
            <!-- Überprüft, ob die Variable $buchungen nicht leer ist. Wenn sie nicht leer ist, wird die folgende Tabelle erstellt. -->
            <table>
                <caption><h3>Buchungen für <?php echo htmlspecialchars($haus['name']); ?>:</h3></caption>
                <!-- Gibt eine Beschriftung der Tabelle aus, die den Namen des Hauses anzeigt. -->
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
                    <!-- Iteriert über jedes Element im Array $buchungen und weist es der Variable $buchung zu. -->
                    <tr> <!-- Gibt Elemente der Buchung in einer Tabelle aus -->
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
            <!-- Wenn $buchungen leer ist, wird diese Nachricht angezeigt. -->
            <p>Keine Buchungen vorhanden.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
