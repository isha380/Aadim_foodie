<?php
include "./managecart.php";
// session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="../../assets/css/component.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/order_table.css">
    <link rel="stylesheet" href="../../assets/css/notification.css">
    <script src="../js/notify.js"></script>
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
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

        .my_order th, .my_order td {
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
            background-color: #f9f9f9;
            border-radius: 8px;
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

        .total-container button, .back-btn button {
            background-color: #543787;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .total-container button:hover, .back-btn button:hover {
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
                    <img src="../../assets/image/icon/final_dark logo.png" alt="Logo">
                </div>
                <div class="navbar-txt menu">
                    <ul>
                        <li><a href="../pages/index.php">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#menu-food-info-wrapper">View Menu</a></li>
                        <li><a href="logout.php">Log out</a></li>
                        <li><a href="../menu_page.php"><button>Back</button></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="reg-wrapper order">
            <div class="reg-headline order">
                <div class="order_title">
                    <h2>My Orders</h2>
                    <div class="order_count">
                        Total orders: <?php echo $isCartEmpty ? 0 : count($_SESSION['cart']); ?>
                    </div>
                </div>
            </div>

            <?php if (!$isCartEmpty): ?>
            <div class="order-wrapper">
                <table class="my_order">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Update</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $key => $item) {
                            $orderName = htmlspecialchars($item['order_name'] ?? "N/A");
                            $orderPrice = floatval($item['order_price'] ?? 0);
                            $orderQuantity = intval($item['order_quantity'] ?? 1);
                            $itemTotalPrice = $orderPrice * $orderQuantity;
                            $total += $itemTotalPrice;
                        ?>
                        <tr>
                            <form method="POST" action="managecart.php">
                                <td><?php echo $key + 1; ?></td>
                                <td>
                                    <input type="hidden" name="order_name" value="<?php echo $orderName; ?>">
                                    <?php echo $orderName; ?>
                                </td>
                                <td>
                                    <input type="number" name="order_quantity" value="<?php echo $orderQuantity; ?>" min="1">
                                </td>
                                <td>
                                    <input type="hidden" name="order_price" value="<?php echo number_format($itemTotalPrice, 2); ?>" readonly>
                                    <h5><?php echo number_format($itemTotalPrice, 2); ?></h5>
                                </td>
                                <td>
                                    <button type="submit" name="update_item">Update</button>
                                </td>
                                <td>
                                    <button type="submit" name="remove_item">Remove</button>
                                </td>
                            </form>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <!-- Order Total Section -->
                <div class="order-total-wrapper">
                    <div class="total-container">
                        <h3>Total:</h3>
                        <h5>Rs.<?php echo number_format($total, 2); ?></h5>
                        <form method="POST" action="../order/purchase.php">
                            <button type="submit">Purchase</button>
                        </form>
                    </div>
                    <div class="back-btn">
                        <button onclick="window.location.href='../../pages/menu_page.php';">Back</button>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-cart-message">
                <p>Your cart is currently empty. Start adding items from the menu!</p>
                <button onclick="window.location.href='../../pages/menu_page.php';">Go to Menu</button>
            </div>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>