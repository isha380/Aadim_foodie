<?php
include "../database/connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $order_id = $_POST['order_id'];

    // Verify the order belongs to the logged-in user
    $query = "DELETE FROM order_food WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order canceled successfully.";
    } else {
        $_SESSION['message'] = "Failed to cancel order.";
    }
    
    header("Location: view_cart.php");
    exit();
}
?>
