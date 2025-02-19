<?php
date_default_timezone_set('Asia/Kathmandu');
session_start();
include "../pages/database/connection.php"; // Adjust the path if necessary

// Check if there are any session messages to display
// if (isset($_SESSION['message'])): ?>
//     <script>
//         showAlert("<?php echo htmlspecialchars($_SESSION['message']); ?>", 
//                   "<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; ?>");
//     </script>
// <?php 
//     unset($_SESSION['message']); 
//     unset($_SESSION['message_type']); 
// endif; 


if (!isset($_SESSION['roll'])) {
    header("Location: login.php");
    exit();
}

// Ensure user_id is stored in session
$user_id = $_SESSION['roll']; 

// Get today's orders
$today = date("Y-m-d");
$query = "SELECT * FROM order_food WHERE Student_Id = ? AND DATE(Order_Date) = ?";

// Check if the connection is successful before preparing the query
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ss", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
} else {
    die("Database query failed: " . $conn->error);
}

// Calculate the total price
$total = 0; 
foreach ($orders as $order) {
    $price = is_numeric($order['Price']) ? floatval($order['Price']) : 0;
    $quantity = is_numeric($order['Quantity']) ? intval($order['Quantity']) : 0;
    $total += $price * $quantity;
}

// Cancel Order Logic (if required)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $cancel_query = "DELETE FROM order_food WHERE Cart_Id = ? AND Student_Id = ?";
    
    if ($cancel_stmt = $conn->prepare($cancel_query)) {
        $cancel_stmt->bind_param("is", $order_id, $user_id);
        if ($cancel_stmt->execute()) {
            $_SESSION['message'] = "Order canceled successfully!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Error canceling order: " . $cancel_stmt->error;
            $_SESSION['message_type'] = 'error';
        }
        $cancel_stmt->close();
    } else {
        die("Database query failed: " . $conn->error);
    }
    header("Location: ../pages/cancel.php"); // Redirect after canceling
    exit();
}
?>

 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Cart Details</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/order_table.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
    
    <style>
        /* Empty Cart Styling */
        .empty-cart-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin: 20px;
        }

        .empty-cart-message p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        .empty-cart-message button {
            background-color: #543787;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .empty-cart-message button:hover {
            background-color: #6a4ba3;
        }

        /* Cart Content Styling Enhancements */
        .order-wrapper {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .my_order {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .my_order thead {
            background-color: #f4f4f4;
        }

        .my_order th,
        .my_order td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .my_order th {
            font-weight: 600;
            color: #333;
        }

        .my_order td input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .my_order td button {
            background-color: #543787;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .my_order td button:hover {
            background-color: #6a4ba3;
        }

        .order-total-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #FFEBAE;
            border-radius: 8px;
        }

        .my_order thead#order_table_head {
            background-color: #FFEBAE !important;
        }

        .total-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .total-container h3 {
            margin: 0;
            color: #333;
        }

        .total-container h5 {
            margin: 0;
            color: #543787;
            font-size: 18px;
        }

        .total-container button,
        .back-btn button {
            background-color: #543787;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .total-container button:hover,
        .back-btn button:hover {
            background-color: #6a4ba3;
        }
    </style>
</head>

<body id="view_cart">
    <?php 
        // Check if cart is empty
        $isCartEmpty = !isset($_SESSION['cart']) || empty($_SESSION['cart']);
        
        if ($isCartEmpty) {
            $_SESSION['message'] = "Your cart is empty. Please add items to your cart.";
            $_SESSION['message_type'] = 'warning';
        }
    ?>
    <!-- Show Popup Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <script>
            // showAlert called from notify.js file
            showAlert("<?php echo htmlspecialchars($_SESSION['message']); ?>", 
                      "<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; ?>");
        </script>
        <?php 
        unset($_SESSION['message']); 
        unset($_SESSION['message_type']); 
        ?>
    <?php endif; ?>

    <section id="food-order-wrapper">
        <div class="menu-headline">
            <div class="navBar-banner-headings menu">
                <div class="navbar-img menu">
                    <img src="../assets/image/icon/final_dark logo.png" alt="Logo">
                </div>
                <div class="navbar-txt menu">
                    <ul>
                        <li><a href="../pages/index.php">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="./menu_page.php#menu-food-info-wrapper">View Menu</a></li>
                        <li><a href="logout.php">Log out</a></li>
                        <li><a href="../menu_page.php"><button>Back</button></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="reg-wrapper order cart">
            <div class="reg-headline order">
                <div class="order_title">
                    <h2>My Orders</h2>
                    <div class="order_count">
                        Total orders: <?php echo !empty($orders) ? count($orders) : 0; ?>
                    </div>
                </div>
            </div>

            <!-- Display Message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php 
                unset($_SESSION['message']); 
                unset($_SESSION['message_type']); 
                ?>
            <?php endif; ?>

            <?php if (!empty($orders)): ?>
            <div class="order-wrapper">
                <table class="my_order">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Order Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($orders as $order):
                            $order_id = $order['Cart_Id'];
                            $order_time = strtotime($order['Order_Time']); // Convert Order_Time to timestamp
                            $current_time = time(); // Current timestamp
                            $time_difference = ($current_time - $order_time) / 60; // Difference in minutes
                            $cancelable = $time_difference < 16; // Check if the order can be canceled
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($order['Dish_name']); ?></td>
                            <td><?php echo $order['Quantity']; ?></td>
                            <td>Rs. <?php echo number_format($order['Price'], 2); ?></td>
                            <td><?php echo !empty($order['Order_Time']) ? date("h:i A", strtotime($order['Order_Time'])) : "N/A"; ?></td>
                            <td>
                                <?php if ($cancelable): ?>
                                    <form method="POST" action="../pages/order/cancel_by_user.php">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        <button type="submit" style="color: red;">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: grey;">Cannot Cancel</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>No orders found for today.</p>
            <?php endif; ?>

            <!-- Order Total Section -->
            <div class="order-total-wrapper">
                <div class="total-container">
                    <h3>Total:</h3>
                    <h5>Rs. <?php echo number_format($total, 2); ?></h5>
                </div>
                <div class="back-btn">
                    <button onclick="window.location.href='../pages/menu_page.php';">Back</button>
                </div>
            </div>
        </div>
    </section>
</body>
</html>