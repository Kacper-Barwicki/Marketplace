<?php
include 'config.php';


if(isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $prod_id = intval($_GET['id']); 
    $user_id = $_SESSION['user_id'];

  
    $check_query = $conn->prepare("SELECT user_id, status FROM produkty WHERE id = ?");
    $check_query->bind_param("i", $prod_id);
    $check_query->execute();
    $result = $check_query->get_result();
    $product = $result->fetch_assoc();

    if($product && $product['status'] == 'dostepny' && $product['user_id'] != $user_id) {
        
        
        $update = $conn->prepare("UPDATE produkty SET status = 'sprzedany' WHERE id = ?");
        $update->bind_param("i", $prod_id);
        $update->execute();

       
        $insert = $conn->prepare("INSERT INTO zakupy (produkt_id, kupujacy_id) VALUES (?, ?)");
        $insert->bind_param("ii", $prod_id, $user_id);
        $insert->execute();
    }
}


header("Location: index.php");
exit;
?>