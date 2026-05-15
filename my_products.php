<?php
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];


if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ? AND user_id = ? AND status = 'dostepny'");
    $stmt->bind_param("ii", $del_id, $uid);
    $stmt->execute();
    header("Location: my_products.php?msg=deleted");
    exit;
}


$sql = "SELECT p.*, k.nazwa as kat_nazwa 
        FROM produkty p 
        LEFT JOIN kategorie k ON p.kategoria_id = k.id 
        WHERE p.user_id = ? 
        ORDER BY p.data_dodania DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje produkty - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container-wide">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Twoje ogłoszenia</h2>
        <a href="add_product.php" class="btn-submit" style="width: auto; text-decoration: none;">+ Dodaj nowy</a>
    </div>

    <?php if ($res->num_rows > 0): ?>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Kategoria</th>
                    <th>Cena</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = $res->fetch_assoc()): ?>
                <tr>
                    <td><b><?php echo htmlspecialchars($p['nazwa']); ?></b></td>
                    <td><?php echo htmlspecialchars($p['kat_nazwa'] ?? 'Inne'); ?></td>
                    <td class="price"><?php echo number_format($p['cena'], 2, ',', ' '); ?> zł</td>
                    <td>
                        <span class="status-badge <?php echo $p['status']; ?>">
                            <?php echo ucfirst($p['status']); ?>
                        </span>
                    </td>
                    <td class="action-links">
                        <?php if($p['status'] == 'dostepny'): ?>
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="edit-link">Edytuj</a>
                            <a href="?delete=<?php echo $p['id']; ?>" class="delete-link" onclick="return confirm('Czy na pewno chcesz usunąć to ogłoszenie?')">Usuń</a>
                        <?php else: ?>
                            <span style="color: #888; font-size: 0.8em;">Brak akcji</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <p>Nie wystawiłeś jeszcze żadnego produktu.</p>
            <a href="add_product.php">Kliknij tutaj, aby dodać pierwsze ogłoszenie!</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>