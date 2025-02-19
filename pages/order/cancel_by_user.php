
 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../database/connection.php"; // Adjust path if necessary
session_start();

// Check if user is logged in
if (!isset($_SESSION['roll'])) {
    header("Location: login.php");
    exit();
}

// Check if order_id is set
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['roll'];

    // SQL to delete the order
    $cancel_query = "DELETE FROM order_food WHERE Cart_Id = ? AND Student_Id = ?";
    
    if ($stmt = $conn->prepare($cancel_query)) {
        $stmt->bind_param("is", $order_id, $user_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Order canceled successfully!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Error canceling order: " . $stmt->error;
            $_SESSION['message_type'] = 'error';
        }
        $stmt->close();
    } else {
        die("Database query failed: " . $conn->error);
    }
} else {
    $_SESSION['message'] = "Order ID not provided!";
    $_SESSION['message_type'] = 'error';
}

// Redirect back to the order page
header("Location: ../cancel.php");
exit();
