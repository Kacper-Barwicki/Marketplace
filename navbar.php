<?php

?>
<nav>
    <a href="index.php" class="logo">Bazar<span>PL</span></a>
    
    <div class="nav-links">
        <a href="index.php">Produkty</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
           
            <a href="add_product.php">Dodaj produkt</a>
            <a href="my_products.php">Moje produkty</a>
            <a href="history.php">Historia zakupów</a>
        <?php endif; ?>
        
        <a href="users.php">Użytkownicy</a>
    </div>

    <div class="user-info">

        <button class="theme-switch" id="themeToggle">Tryb Ciemny</button>
        
        <?php if(isset($_SESSION['login'])): ?>

            <div class="avatar"><?php echo strtoupper(substr($_SESSION['login'], 0, 2)); ?></div>
            <span><?php echo htmlspecialchars($_SESSION['login']); ?></span>
            <a href="logout.php" style="margin-left:10px; text-decoration:none; color:#d9534f;">Wyloguj</a>
        <?php else: ?>
   
            <a href="login.php">Zaloguj się</a>
            <a href="register.php" style="margin-left:10px;">Zarejestruj się</a>
        <?php endif; ?>
    </div>
</nav>


<script>
    const btn = document.getElementById('themeToggle');
    const theme = localStorage.getItem('theme');

    if (theme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
        btn.textContent = 'Tryb Jasny';
    }

    btn.addEventListener('click', () => {
        if (document.body.getAttribute('data-theme') === 'dark') {
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