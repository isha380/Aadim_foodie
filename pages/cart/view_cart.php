<?php
include "./managecart.php";
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
</head>
<style>

</style>

<body id="view_cart">
    <!-- Show Popup Message -->
    <?php if (isset($_SESSION['message'])) : ?>
        <script>
            alert("<?php echo $_SESSION['message']; ?>");
        </script>
        <?php unset($_SESSION['message']); // Clear the message after displaying 
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
                    </ul>
                </div>
            </div>
        </div>

        <div class="reg-wrapper order">
            <div class="reg-headline order">
                <div class="order_title">
                    <h2>My Orders</h2>
                    <div class="order_count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></div>
                </div>
            </div>

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
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            // Ensure numeric values
            $orderPrice = floatval($item['orderPrice']);
            $orderQuantity = intval($item['orderQuantity']);
            $item_total_price = $orderPrice * $orderQuantity;
            $total += $item_total_price;
    ?>
            <tr>
                <form method="POST" action="managecart.php">
                    <td><?php echo $key + 1; ?></td>
                    <td>
                        <input type="hidden" name="order_name" value="<?php echo $item['orderName']; ?>">
                        <?php echo htmlspecialchars($item['orderName']); ?>
                    </td>
                    <td>
                        <input type="number" name="order_quantity" value="<?php echo $orderQuantity; ?>" min="1">
                    </td>
                    <td>
                        <input type="hidden" name="order_price" value="<?php echo number_format($item_total_price, 2); ?>" readonly>
                        <h5><?php echo number_format($item_total_price, 2); ?></h5>

                    </td>
                    <td>
                        <button type="submit" name="update_item">Update</button>
                    </td>
                    <td>
                        <button type="submit" name="remove_item">Remove</button>
                    </td>
                </form>
            </tr>
    <?php
        }
    }
    ?>
</tbody>

                </table>


                <!-- Order Total Section -->
                <div class="order-total-wrapper">
                    <div class="total-container">
                        <h3>Total:</h3>
                        <h5>Rs.<?php echo $total; ?></h5>
                        <form method="POST" action="purchase.php">
                            <button type="submit">Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>