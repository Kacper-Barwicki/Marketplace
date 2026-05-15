<?php
include 'config.php';
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['haslo'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO uzytkownicy (login, email, haslo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $login, $email, $pass);
    if($stmt->execute()) header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div style="max-width: 400px; margin: 100px auto; padding: 20px; border: 1px solid #eee;">
        <h2>Zarejestruj się</h2>
        <form method="POST">
            <input type="text" name="login" placeholder="Login" required style="width:100%; margin-bottom:10px; padding:8px;">
            <input type="email" name="email" placeholder="Email" required style="width:100%; margin-bottom:10px; padding:8px;">
            <input type="password" name="haslo" placeholder="Hasło" required style="width:100%; margin-bottom:20px; padding:8px;">
            <button type="submit" class="btn-submit">Stwórz konto</button>
        </form>
    </div>
</body>
</html>