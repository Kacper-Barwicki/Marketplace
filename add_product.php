<?php 
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $stmt = $conn->prepare("INSERT INTO produkty (nazwa, opis, cena, kategoria_id, user_id) VALUES (?, ?, ?, ?, ?)");
    
    
    $stmt->bind_param("ssdii", 
        $_POST['nazwa'], 
        $_POST['opis'], 
        $_POST['cena'], 
        $_POST['kategoria_id'], 
        $_SESSION['user_id']
    );
    
    if($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Błąd bazy danych: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wystaw przedmiot - BazarPL</title>
    <link rel="stylesheet" href="style.css">
    <style>
    
        .add-product-wrapper {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            box-sizing: border-box; 
            padding: 12px;
            border-radius: 6px;
            border: 1px solid var(--card-border);
            background-color: var(--card-bg);
            color: var(--text-color);
            font-size: 14px;
        }
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="add-product-wrapper">
        <div class="form-container" style="padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="margin-top: 0; text-align: center;">Wystaw produkt</h2>
            <p style="text-align: center; color: #888; font-size: 0.9em; margin-bottom: 25px;">Wypełnij poniższe pola, aby wystawić przedmiot na bazarze.</p>
            
            <?php if(isset($error)): ?>
                <div style="color: #d9534f; background: #f2dede; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form id="productForm" method="POST">
                <div class="form-group">
                    <label>Co sprzedajesz?</label>
                    <input type="text" name="nazwa" id="nazwa" placeholder="Np. Konsola PS5 Digital" required>
                </div>

                <div class="form-group">
                    <label>Kategoria</label>
                    <select name="kategoria_id" id="kategoria" required>
                        <option value="" disabled selected>Wybierz kategorię...</option>
                        <?php
                        $kats = $conn->query("SELECT * FROM kategorie ORDER BY nazwa ASC");
                        while($k = $kats->fetch_assoc()) {
                            echo "<option value='{$k['id']}'>{$k['nazwa']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Opis (opcjonalnie)</label>
                    <textarea name="opis" id="opis" placeholder="Napisz w jakim stanie jest przedmiot, co wchodzi w skład zestawu itp."></textarea>
                </div>

                <div class="form-group">
                    <label>Cena w PLN</label>
                    <input type="number" step="0.01" name="cena" id="cena" placeholder="0.00" required>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit" style="font-size: 1.1em; padding: 15px;">Wystaw na sprzedaż</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('productForm').onsubmit = function(e) {
        let nazwa = document.getElementById('nazwa').value;
        let cena = document.getElementById('cena').value;
        let kat = document.getElementById('kategoria').value;

        if(nazwa.length < 3 || cena <= 0 || kat === "") {
            alert("Uzupełnij poprawnie wszystkie pola formularza!");
            e.preventDefault();
        }
    }
    </script>
</body>
</html>