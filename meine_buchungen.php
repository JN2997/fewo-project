<?php
session_start();
require_once 'db_connect.php'; // Diese Datei stellt die Verbindung zur Datenbank her

// Angenommen, die User-ID ist in der Session gespeichert
$user_id = $_SESSION['USER_ID'];

$query = "
    SELECT 
        b.BUCHUNG_ID, 
        b.arrival, 
        b.departure, 
        b.gesamtpreis, 
        h.adresse, 
        h.land 
    FROM 
        buchungen b 
    JOIN 
        haus h 
    ON 
        b.HAUS_ID = h.HAUS_ID 
    WHERE 
        b.USER_ID = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title> 
    <!-- CSS Stylesheet einbinden -->
    <link rel="stylesheet" href="css/main.css">

</head>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
			<button onclick="window.open('meine_buchungen.php', '_blank');">Meine Buchungen</button>
			<button onclick="window.open('profil.php', '_blank');">Profil</button>
			<button onclick="window.open('logout.php', '_self');">Logout</button>
        </nav>
    </header>
<body>
    <h1>Meine Buchungen</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Buchungs-ID</th>
                    <th>Anreise</th>
                    <th>Abreise</th>
                    <th>Gesamtpreis</th>
                    <th>Adresse</th>
                    <th>Land</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['BUCHUNG_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['arrival']); ?></td>
                        <td><?php echo htmlspecialchars($row['departure']); ?></td>
                        <td><?php echo htmlspecialchars($row['gesamtpreis']) . ' EUR'; ?></td>
                        <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                        <td><?php echo htmlspecialchars($row['land']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">Sie haben Ihren Traumurlaub wohl noch nicht gebucht...</p>
    <?php endif; ?>

    <?php
    // Schließe die Datenbankverbindung
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>