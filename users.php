<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div style="padding: 0 10%; margin-top: 30px;">
        <h2>Lista użytkowników</h2>
    <table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $res = $conn->query("SELECT id, login, email FROM uzytkownicy");
        while($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td style="text-align: center;"><?php echo $row['id']; ?></td>
            <td><?php echo $row['login']; ?></td>
            <td><?php echo $row['email']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </div>
</body>
</html>