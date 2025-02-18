<?php
session_start();
include "../database/connection.php";

if (isset($_POST['cartId']) && isset($_POST['status'])) {
    $cartId = mysqli_real_escape_string($conn, $_POST['cartId']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE order_food SET Status = ? WHERE Cart_Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status, $cartId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo 'Status updated successfully';
    } else {
        echo 'Failed to update status';
    }
}
?>
