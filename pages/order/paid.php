<?php
include '../database/connection.php';

// Ensure the admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get Cart_Id (Order ID) from URL parameter
$cart_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if the Cart_Id exists in order_food
$check_order = $conn->prepare("SELECT * FROM order_food WHERE Cart_Id = ?");
$check_order->bind_param("i", $cart_id);
$check_order->execute();
$result = $check_order->get_result();

if ($result->num_rows == 0) {
    $_SESSION['message'] = " Error: Order not found.";
    header("Location: ../admin/status_view.php");
    exit();
}

// Fetch order details
$order = $result->fetch_assoc();

// Fetch student name from student_info table
$studentQuery = "SELECT Name FROM student_info WHERE Rollnum = ?";
$studentStmt = $conn->prepare($studentQuery);
$studentStmt->bind_param("s", $order['Student_Id']);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();
$studentName = ($studentResult->num_rows > 0) ? $studentResult->fetch_assoc()['Name'] : 'Unknown';

// Insert into completed_orders
$insert_query = "INSERT INTO completed_orders (Id, Student_Id, Name, Dish_Name, Quantity, Price, Date) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("isssids", 
    $order['Cart_Id'], 
    $order['Student_Id'], 
    $studentName, 
    $order['Dish_name'], 
    $order['Quantity'], 
    $order['Price'], 
    $order['Order_Date']
);

// Execute insert query
if ($insert_stmt->execute()) {
    // Delete the order from order_food after successful insertion
    $delete_query = "DELETE FROM order_food WHERE Cart_Id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $cart_id);
    $delete_stmt->execute();

    $_SESSION['message'] = "✅ Order marked as paid!";
} else {
    $_SESSION['message'] = "❌ Error moving order to completed orders!";
}

// Redirect back to status_view.php
header("Location: ../admin/status_view.php");
exit();
?>
