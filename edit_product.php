<?php
require_once 'config.php';
$pid = (int)$_GET['id'];
$uid = $_SESSION['user_id'];


$res = $conn->query("SELECT * FROM produkty WHERE id = $pid AND user_id = $uid");
$produkt = $res->fetch_assoc();
if (!$produkt) die("Nie masz uprawnień!");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("UPDATE produkty SET nazwa=?, opis=?, cena=?, kategoria_id=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssdiii", $_POST['nazwa'], $_POST['opis'], $_POST['cena'], $_POST['cat'], $pid, $uid);
    $stmt->execute();
    header("Location: my_products.php");
}
?>
<?php echo $produkt['nazwa']; ?>