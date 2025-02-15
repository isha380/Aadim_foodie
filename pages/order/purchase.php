<?php
session_start();
include "../database/connection.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['message'] = "Invalid request method.";
    header("Location: ../menu_page.php");
    exit();
}

// Check if the cart and student session are set
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || !isset($_SESSION['roll'])) {
    $_SESSION['message'] = "Your cart is empty or you are not logged in.";
    header("Location: ../menu_page.php");
    exit();
}

// Retrieve session data
$cart_items = $_SESSION['cart']; // Cart items
$student_id = $_SESSION['roll']; // Student ID from session
$order_date = date("Y-m-d H:i:s"); // Current date and time

// Prepare SQL statement
$sql = "INSERT INTO order_food (Dish_name, Quantity, Price, Student_Id, Order_Date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";


echo "Student ID: " . $_SESSION['roll'];






// Process each item in the cart
foreach ($cart_items as $item) {
    // Validate and sanitize each item
    $dish_name = isset($item['order_name']) ? $item['order_name'] : "Unknown";
    $quantity = isset($item['order_quantity']) ? intval($item['order_quantity']) : 0;
    $price = isset($item['order_price']) ? floatval($item['order_price']) : 0.0;
    
//     // Skip inserting invalid items
//     if ($dish_name === "Unknown" || $quantity <= 0 || $price <= 0) {
//         continue;
//     }

    // Bind parameters and execute SQL query
    $stmt->bind_param("sidss", $dish_name, $quantity, $price, $student_id, $order_date);

    if (!$stmt->execute()) {
        echo "<p style='color:red;'>Error inserting $dish_name: " . $stmt->error . "</p><hr>";
    }
}

// Close the statement
$stmt->close();

// Clear the cart after successful order
unset($_SESSION['cart']);
$_SESSION['message'] = "Your order has been placed successfully!";

// Redirect to the menu page or a success page
header("Location: ../menu_page.php");
exit();
?>
