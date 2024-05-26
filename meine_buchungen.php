<?php
session_start();
include 'auth_nav.php';
require_once 'db_connect.php'; // Diese Datei stellt die Verbindung zur Datenbank her
exclude_user_Roles (['guest', 'Admin'], 'index.php');

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
			<?php display_menu();?>
        </nav>
    </header>
<body>
<main>
    <!-- Überprüfen, ob es Buchungen gibt -->
    <?php if ($result->num_rows > 0): ?>
        <!-- Tabelle für Buchungen anzeigen -->
        <table>
			<caption><h1>Meine Buchungen</h1></caption> 
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
                <!-- Schleife durch alle Buchungen und anzeigen -->
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
        <!-- Nachricht anzeigen, wenn keine Buchungen vorhanden sind -->
        <p style="text-align: center;">Sie haben Ihren Traumurlaub wohl noch nicht gebucht...</p>
    <?php endif; ?>

    <?php
    // Schließe die Datenbankverbindung
    $stmt->close();
    $conn->close();
    ?>
</main>
</body>
</html>