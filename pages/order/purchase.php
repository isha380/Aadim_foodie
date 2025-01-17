<?php 
session_start();
include "../database/connection.php";


// Check if the user is logged in and the cart is not empty
if (!isset($_SESSION['roll']) || !isset($_SESSION['name']) || empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Your cart is empty or you are not logged in.";
    header("Location: ../menu_page.php");
    exit();
}

// Get student details
$student_id = $_SESSION['roll'];
$cart_items = $_SESSION['cart']; // Cart items
$order_date = date("Y-m-d H:i:s"); // Current date and time

foreach ($cart_items as $key => $item) {
    $dish_name = $item['orderName'];
    $quantity = intval($item['orderQuantity']);
    $price = floatval($item['orderPrice']);
    $menu_id = $key + 1; // Adjust this logic if `Menu_Id` is stored in your database

    // Insert into order_food table
    $sql = "INSERT INTO order_food (Cart_Id, Dish_name, Quantity, Price, Menu_Id, Student_Id, Order_Date) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidiss", $dish_name, $quantity, $price, $menu_id, $student_id, $order_date);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order placed successfully!";
    } else {
        $_SESSION['message'] = "Error placing order: " . $conn->error;
    }
}

// Clear the cart after purchase
unset($_SESSION['cart']);

// Redirect to a confirmation or menu page
header("Location: ../menu_page.php");
exit();


?>