<?php
session_start();
include "../database/connection.php";


/**
 * TODO: Implementation
 * once the items are purchased,
 * destroy the session cart only not all sessions.
 * 
 * Store in the order history table with order information filtered by users id or roll number 
 * create a separate page for every users who can see the order history
 */


// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ensure the cart and student session are set
    if (!isset($_SESSION['cart']) || !isset($_SESSION['roll'])) {
        $_SESSION['message'] = "Your cart is empty or you are not logged in.";
        header("Location: ../menu_page.php");
        exit();
    }

    // Retrieve session data
    $cart_items = $_SESSION['cart']; // Cart items
    $student_id = $_SESSION['roll']; // Student ID from session
    $order_date = date("Y-m-d H:i:s"); // Current date and time

    // Process each item in the cart
    foreach ($cart_items as $key => $item) {
        $dish_name = $item['orderName'];
        $quantity = intval($item['orderQuantity']);
        $price = floatval($item['orderPrice']);
       // Adjust the logic here if Menu_ID is dynamically fetched

        // Debug the values (optional)
        echo "Dish Name: $dish_name<br>";
        echo "Quantity: $quantity<br>";
        echo "Price: $price<br>";
       
        echo "Student ID: $student_id<br>";
        echo "Order Date: $order_date<br>";

        // SQL query to insert order details into the order_food table
       
                $sql = "INSERT INTO order_food (Dish_name, Quantity, Price, Student_Id, Order_Date) 
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the query
        $stmt->bind_param("sidss", $dish_name, $quantity, $price, $student_id, $order_date);


        // Execute the query and check for success
        if ($stmt->execute()) {
            echo "Insertion successful for $dish_name.<br>";
        } else {
            echo "Error inserting $dish_name: " . $stmt->error . "<br>";
        }
    }

    

    // Clear the cart after successful order
    unset($_SESSION['cart']);
    $_SESSION['message'] = "Your order has been placed successfully!";

    // Redirect to the menu page or a success page
    header("Location: ../menu_page.php");
    exit();
} else {
    // If not a POST request, redirect to the menu page
    $_SESSION['message'] = "Invalid request method.";
    header("Location: ../menu_page.php");
    exit();
}
?>
