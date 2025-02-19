<?php
session_start();
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cartId = $data['cartId'];
    $status = $data['status'];

    // Validate input
    if (empty($cartId) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit();
    }

    // Update the order status in the database
    $stmt = $conn->prepare("UPDATE order_food SET Status = ? WHERE Cart_Id = ?");
    $stmt->bind_param("ss", $status, $cartId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
