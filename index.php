<?php 
require_once 'config.php';


if (isset($_GET['buy']) && isset($_SESSION['user_id'])) {
    $pid = (int)$_GET['buy'];
    $uid = $_SESSION['user_id'];
    

    $check = $conn->query("SELECT user_id FROM produkty WHERE id = $pid AND status = 'dostepny'")->fetch_assoc();
    if ($check && $check['user_id'] != $uid) {
        $conn->query("UPDATE produkty SET status = 'sprzedany' WHERE id = $pid");
        $conn->query("INSERT INTO zakupy (produkt_id, kupujacy_id) VALUES ($pid, $uid)");
        header("Location: index.php?success=1");
        exit;
    }
}


$search = isset($_GET['search']) ? $_GET['search'] : '';
$cat_filter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

$sql = "SELECT p.*, u.login, k.nazwa as kat_nazwa 
        FROM produkty p 
        JOIN uzytkownicy u ON p.user_id = u.id 
        LEFT JOIN kategorie k ON p.kategoria_id = k.id 
        WHERE p.nazwa LIKE ?";

if ($cat_filter > 0) $sql .= " AND p.kategoria_id = $cat_filter";
$sql .= " ORDER BY p.data_dodania DESC";

$stmt = $conn->prepare($sql);
$term = "%$search%";
$stmt->bind_param("s", $term);
$stmt->execute();
$produkty = $stmt->get_result();


$total = $conn->query("SELECT COUNT(*) FROM produkty")->fetch_row()[0];
$avail = $conn->query("SELECT COUNT(*) FROM produkty WHERE status='dostepny'")->fetch_row()[0];
$sold = $conn->query("SELECT COUNT(*) FROM produkty WHERE status='sprzedany'")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazarPL - Strona Główna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="stats-container">
    <div class="stat-item"><h2><?php echo $total; ?></h2><span>Wszystkich produktów</span></div>
    <div class="stat-item"><h2><?php echo $avail; ?></h2><span>Dostępnych</span></div>
    <div class="stat-item"><h2><?php echo $sold; ?></h2><span>Sprzedanych</span></div>
</div>

<div style="padding: 0 10%;">

    <form method="GET" class="filter-bar">
        <input type="text" name="search" placeholder="Szukaj produktu..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="cat">
            <option value="0">Wszystkie kategorie</option>
            <?php
            $kats = $conn->query("SELECT * FROM kategorie");
            while($k = $kats->fetch_assoc()) {
                $sel = ($cat_filter == $k['id']) ? 'selected' : '';
                echo "<option value='{$k['id']}' $sel>{$k['nazwa']}</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn-submit" style="width: auto;">Filtruj</button>
    </form>

    <h2>Lista produktów</h2>
    
    <div class="product-grid">
        <?php while($p = $produkty->fetch_assoc()): ?>
            <div class="product-card">
                <span class="status-badge <?php echo $p['status']; ?>">
                    <?php echo htmlspecialchars($p['kat_nazwa'] ?? 'Inne'); ?> | <?php echo ucfirst($p['status']); ?>
                </span>
                
                <h3><?php echo htmlspecialchars($p['nazwa']); ?></h3>
                <p><?php echo htmlspecialchars($p['opis']); ?></p>
                <p class="price"><?php echo number_format($p['cena'], 0, '', ' '); ?> zł</p>
                <p><small>Sprzedający: <b><?php echo $p['login']; ?></b></small></p>

                <?php if($p['status'] == 'dostepny'): ?>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $p['user_id']): ?>
                        <a href="?buy=<?php echo $p['id']; ?>" class="btn-buy" onclick="return confirm('Kupić?')">Kup teraz</a>
                    <?php elseif(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $p['user_id']): ?>
                        <button class="btn-buy btn-disabled">Twój produkt</button>
                    <?php else: ?>
                        <a href="login.php" class="btn-buy">Zaloguj by kupić</a>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn-buy btn-disabled">Sprzedany</button>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>

    const btn = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
        btn.textContent = 'Tryb Jasny';
    }

    btn.addEventListener('click', () => {
        let theme = document.body.getAttribute('data-theme');
        if (theme === 'dark') {
            document.body.removeAttribute('data-theme');
            btn.textContent = 'Tryb Ciemny';
            localStorage.setItem('theme', 'light');
        } else {
            document.body.setAttribute('data-theme', 'dark');
            btn.textContent = 'Tryb Jasny';
            localStorage.setItem('theme', 'dark');
        }
    });
</script>

</body>
</html>