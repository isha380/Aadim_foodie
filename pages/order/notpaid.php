<?php
session_start();
include "../database/connection.php";

// if (!isset($_SESSION['admin_id'])) {
//     header('Location: admin_login.php');
//     exit();
// }

// if (isset($_GET['id'])) {
//     $cartId = intval($_GET['id']);

//     // Fetch order details
//     $query = "SELECT * FROM order_food WHERE Cart_Id = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $cartId);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $order = $result->fetch_assoc();

//     if ($order) {
//         // Move order details to pending_payments table
//         $insertQuery = "INSERT INTO pending_payments (Student_Id, Name, Dish_name, Quantity, Price, Due_Amount, Date) 
//                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
//         $stmt = $conn->prepare($insertQuery);
//         $stmt->bind_param("issidi", $order['Student_Id'], $order['Name'], $order['Dish_name'], 
//                           $order['Quantity'], $order['Price'], $order['Price']);
//         $stmt->execute();

//         // Remove from order_food
//         $deleteQuery = "DELETE FROM order_food WHERE Cart_Id = ?";
//         $stmt = $conn->prepare($deleteQuery);
//         $stmt->bind_param("i", $cartId);
//         $stmt->execute();

//         $_SESSION['message'] = "Order moved to pending payments.";
//     } else {
//         $_SESSION['message'] = "Order not found.";
//     }
// } else {
//     $_SESSION['message'] = "Invalid request.";
// }

// header("Location: ../admin/status_view.php");
// exit();


error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (isset($_GET['id'])) {
    $cartId = intval($_GET['id']);

    // Fetch order details

    $query = "SELECT o.*, s.Name 
          FROM order_food o 
          JOIN student_info s ON o.Student_Id = s.Rollnum 
          WHERE o.Cart_Id = ?";


    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        // Move order details to pending_payments table
        $insertQuery = "INSERT INTO pending_payments (Student_Id, Name, Dish_name, Quantity, Price, Due_Amount, Date) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param(
            "sssidd",  // "s" for VARCHAR, "i/d" for numbers
            $order['Student_Id'],  // This still refers to order_food.Student_Id
            $order['Name'],
            $order['Dish_name'],
            $order['Quantity'],
            $order['Price'],
            $order['Price']
        );
        
        $stmt->execute();


        if ($stmt->affected_rows > 0) {
            // Remove from order_food
            $deleteQuery = "DELETE FROM order_food WHERE Cart_Id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $cartId);
            $stmt->execute();

            $_SESSION['message'] = "✅ Order moved to pending payments.";
        } else {
            $_SESSION['message'] = "⚠️ Order not added to pending payments.";
        }
    } else {
        $_SESSION['message'] = "❌ Order not found.";
    }
} else {
    $_SESSION['message'] = "⚠️ Invalid request.";
}

header("Location: ../admin/status_view.php");
exit();
