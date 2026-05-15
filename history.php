<?php
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];


$sql = "SELECT p.nazwa, p.cena, z.data_zakupu, u.login as sprzedawca 
        FROM zakupy z 
        JOIN produkty p ON z.produkt_id = p.id 
        JOIN uzytkownicy u ON p.user_id = u.id 
        WHERE z.kupujacy_id = ? 
        ORDER BY z.data_zakupu DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$zakupy = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia zakupów - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container-wide">
    <h2>Twoja historia zakupów</h2>

    <?php if ($zakupy->num_rows > 0): ?>
        <div style="margin-top: 20px;">
            <?php while($z = $zakupy->fetch_assoc()): ?>
                <div class="history-item">
                    <div class="history-info">
                        <h4><?php echo htmlspecialchars($z['nazwa']); ?></h4>
                        <p>Sprzedawca: <b><?php echo htmlspecialchars($z['sprzedawca']); ?></b></p>
                        <p><small>Data zakupu: <?php echo date('d.m.Y H:i', strtotime($z['data_zakupu'])); ?></small></p>
                    </div>
                    <div class="history-price">
                        <?php echo number_format($z['cena'], 2, ',', ' '); ?> zł
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>Twoja historia zakupów jest pusta.</p>
            <a href="index.php">Przejdź do strony głównej i znajdź coś dla siebie!</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>