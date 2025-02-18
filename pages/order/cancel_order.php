<?php
session_start();
include "../database/connection.php";

// Checking if the user is an admin or cook
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['cook_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Check if 'id' is passed and it's a valid order
if (isset($_GET['id'])) {
    $cartId = mysqli_real_escape_string($conn, $_GET['id']);

    // Query to fetch order details
    $query = "SELECT order_food.*, student_info.Name, student_info.Email 
    FROM order_food 
    INNER JOIN student_info ON order_food.Student_Id = student_info.Rollnum
    WHERE order_food.Cart_Id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cartId);  // Bind parameter for Cart_Id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Fetch the dish name of the food being canceled
        $foodName = $order['Dish_name'];  // The name of the food item being canceled

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get cancellation message
            $cancelMessage = mysqli_real_escape_string($conn, $_POST['cancel_message']);

            // Mark order as cancelled
            $updateQuery = "UPDATE order_food SET is_cancelled = 1, cancel_message = ? WHERE Cart_Id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $cancelMessage, $cartId);  // Bind parameters for update
            $updateStmt->execute();

            // Notify the user
            $to = $order['Student_Id'];
            $name = $order['Name'];
            $email = $order['Email'];
            $subject = "Your order has been canceled";
            $message = "Dear " . $name . ",\n\nYour order for '" . $foodName . "' has been canceled. Reason: " . $cancelMessage;
            $headers = "From: ishamagar308@gmail.com";

            mail($email, $subject, $message, $headers);

            // Set a success message
            $_SESSION['message'] = "Order has been cancelled, and the user has been notified.";
            header("Location: admin_dash.php"); // Redirect to dashboard after cancellation
            exit();
        }
    } else {
        $_SESSION['message'] = "Order not found.";
        header("Location: admin_dash.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid order ID.";
    header("Location: admin_dash.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Order</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <div class="cancel-order-wrapper">
        <h1>Cancel Order</h1>

        <!-- Displaying the student's details -->
        <p><strong>Student Name:</strong> <?php echo htmlspecialchars($order['Name']); ?></p>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($order['Student_Id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['Email']); ?></p>

        <!-- Displaying the ordered food item that is going to be cancelled -->
        <p><strong>Order to be Canceled:</strong> <?php echo htmlspecialchars($foodName); ?></p>

        <!-- Cancellation Form -->
        <form method="POST">
            <label for="cancel_message">Reason for cancellation:</label>
            <textarea id="cancel_message" name="cancel_message" required></textarea>
            <button type="submit" class="btn">Submit Cancellation</button>
        </form>

        <button class="btn"><a href="../admin/status_view.php" style="text-decoration: none;">Back to Dashboard</a></button>
    </div>

    <script src="../js/dropdown-dash.js"></script>
</body>

</html>
