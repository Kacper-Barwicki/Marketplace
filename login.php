<?php
include 'config.php';
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $stmt = $conn->prepare("SELECT id, login, haslo FROM uzytkownicy WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($user = $result->fetch_assoc()) {
        if(password_verify($_POST['haslo'], $user['haslo'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            header("Location: index.php");
        } else { echo "Błędne hasło!"; }
    } else { echo "Użytkownik nie istnieje!"; }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div style="max-width: 400px; margin: 100px auto; padding: 20px; border: 1px solid #eee;">
        <h2>Zaloguj się</h2>
        <form method="POST">
            <input type="text" name="login" placeholder="Login" required style="width:100%; margin-bottom:10px; padding:8px;">
            <input type="password" name="haslo" placeholder="Hasło" required style="width:100%; margin-bottom:20px; padding:8px;">
            <button type="submit" class="btn-submit">Zaloguj</button>
        </form>
        <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
    </div>
</body>
</html>