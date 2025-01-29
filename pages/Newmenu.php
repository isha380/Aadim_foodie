<?php
// session_start();
include "../pages/database/connection.php";
include "./cart/cartManage.php";

if (!isset($_SESSION['roll']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['name'];

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/order_table.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
    <!-- we use async tag in script in order to load asynchronously (without blocking page rendering) -->
    <!-- <script src="./js/notification.js"></script> -->
</head>

<body class="menu-body">

    <!-- --------------------menu info section ------------------------------------------------------ -->
    <section id="menu-info-wrapper">

        <div class="menu-headline">

            <div class="navBar-banner-headings menu">
                <div class="navbar-img menu">
                    <img src="../assets/image/icon/final_dark logo.png" alt="img">
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
        <script src="./js/notification.js"></script> 
        <?php
            if (isset($_SESSION['msg'])) {
                echo "<script>showAlert('" . htmlspecialchars($_SESSION['msg']) . "');</script>";
                unset($_SESSION['msg']);
            }
        ?>
        <div class="menu-banner">
            <div class="menu-profile-name">
                <h1>Welcome, <?php echo htmlspecialchars($userName); ?></h1>
            </div>
            <div class="menu-info">
                <span class="menu-info-txt">Hungry for something amazing? <br>Let us bring the feast to you.<br>" Order your favorites now! "
                </span>
                <button class="btn reg-btn menu "><a href="#menu-food-info-wrapper">
                        ORDER FOOD
                    </a>
                </button>

            </div>
        </div>
    </section>

    <!----------------------menu food info section-------------------------------------------------------->

    <section id="menu-food-info-wrapper">
        <div class="menu-container-headline">
            <span class="menu-container-txt">OUR CULINARY DELIGHTS</span>
        </div>

        <div class="menu-container">
            <button class=" arrow menu pre">
                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 1024 1024">
                    <path fill=" #543787" d="M685.248 104.704a64 64 0 0 1 0 90.496L368.448 512l316.8 316.8a64 64 0 0 1-90.496 90.496L232.704 557.248a64 64 0 0 1 0-90.496l362.048-362.048a64 64 0 0 1 90.496 0" />
                </svg>
            </button>

            <button class="arrow menu next">
                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 1024 1024">
                    <path fill="#543787" d="M338.752 104.704a64 64 0 0 0 0 90.496l316.8 316.8l-316.8 316.8a64 64 0 0 0 90.496 90.496l362.048-362.048a64 64 0 0 0 0-90.496L429.248 104.704a64 64 0 0 0-90.496 0" />
                </svg>
            </button>


            <div class="dish-slide-container menu">
                <?php
                    $res = mysqli_query($conn, "SELECT * FROM menu_items");
                    // Loop through each item and display it
                    while ($row = mysqli_fetch_assoc($res)) {

                ?>
                    <form method="POST" action="./cart/cartManage.php">
                        <div class="dish-content-wrapper menu">
                            <div class="dish-image">
                                <img src="../assets/image/menu/<?php echo htmlspecialchars($row['Image']); ?>" alt="Dish Image">
                            </div>
                            <div class="dish-info">
                                <div class="dish-name">
                                    <button class="btn dish"><?php echo htmlspecialchars($row['Name']); ?></button>
                                </div>
                                <div class="dish-price">
                                <!-- <--! <?php var_dump($row);?> -->
                                    <span class="slider-dish-price">Per Plate:
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                                        </svg>
                                        <?php echo htmlspecialchars($row['Price']); ?>
                                    </span>
                                </div>
                                <div class="dish-status-wrapper">
                                    <span class="dish-status-text">Status:
                                        <button class="dish-status-label" id="dish-status" <?php if ($row['Status'] == '1') {
                                                                                                echo 'style="background-color:  #6EC531; color:"  #FEB737" "';
                                                                                            } ?>>
                                            <?php

                                            echo ($row['Status'] == '1') ? 'Available' : 'Unavailable';

                                            ?>
                                        </button>
                                    </span>
                                </div>
                                <div class="dish-quantity-wrapper">
                                    <button class="btn-quantity decrement"  type="button" onclick="decrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">-</button>
                                    <div class="dish-number" id="dish-quantity-<?php echo $row['Id']; ?>">1</div>
                                    <button class="btn-quantity increment" type="button"  onclick="incrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">+</button>
                                </div>

                                <div class="add-to-cart-button">
                                    <?php if ($row['Status'] == '0'): ?>
                                        <!-- Dish Unavailable -->
                                        <button class="btn cart-btn" type="button" onclick="showAlert('This dish is not available for now','error')">
                                            <span class="cart-btn-text">Add to cart</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path fill="#feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                                            </svg>
                                        </button>
                                            <?php else: ?>
                                                <!-- Dish Available -->
                                                <button class="btn cart-btn" type="submit" name="add_to_cart">
                                                    <span class="cart-btn-text">Add to cart</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                        <path fill="#feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <input type="hidden" name="Item_Name" value="<?php echo $row['Name']; ?>" />
                                            <input type="hidden" name="image" value="<?php echo $row['Image']; ?>" />
                                            <input type="hidden" name="Price" value="<?php echo $row['Price']; ?>" />
                                            <input type="hidden" id="order-quantity-<?php echo $row['Id']; ?>" name="quantity" value="1">
                                </div>
                            </div>
                        </div>
                    </form>
                <?php
                    }
                ?>
            </div>
        </div>
    </section>

    <!--------------------------------------menu food order info section------------------------->

    <?php
        // Pagination setup
        $items_per_page = 5; // Items per page 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page from URL
        $offset = ($page - 1) * $items_per_page;

        // Fetch cart items from session
        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $total_items = count($cart_items);
        $cart_items = array_slice($cart_items, $offset, $items_per_page); // Slice the array to paginate

        $total_pages = ceil($total_items / $items_per_page);
    ?>

    <section id="food-order-wrapper">
        <div class="reg-wrapper order">
            <div class="reg-headline">
                <div class="order_title">
                    <h2 style="font-size: 24px; margin: 0;">My Orders</h2>
                    <div class="order_count" style="font-size: 20px; color: #333;"><?php echo $total_items; ?></div>
                </div>
            </div>

            <div class="order-wrapper">
                <table class="my_order" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">S.No</th>
                            <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Dish Name</th>
                            <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Quantity</th>
                            <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Price</th>
                            <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                        $total = 0; 
                        $counter = $offset + 1; // To keep S.No accurate for pagination
                        if (!empty($cart_items)) {
                            foreach ($cart_items as $key => $value) {
                                $value = array_merge([
                                    'order_name' => '',
                                    'order_price' => 0,
                                    'quantity' => 0,
                                    'image' => '',
                                    'Item_Name' => '',
                                    'Price' => 0,
                                    'Quantity' => 0
                                ], $value);

                                $price = floatval($value['Price'] ?? $value['order_price']);
                                $quantity = intval($value['Quantity'] ?? $value['quantity']);
                                $itemTotal = $price * $quantity;
                                $total += $itemTotal;

                                $itemName = !empty($value['Item_Name']) ? $value['Item_Name'] : $value['order_name'];
                                $itemName = htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $counter; ?></td>
                                    <td style="text-align: left; padding: 10px;">
                                        <?php if (!empty($value['image'])): ?>
                                            <img src="../assets/image/menu/<?php echo htmlspecialchars($value['image']); ?>" alt="<?php echo $itemName; ?>" class="cart-item-image" style="width: 30px; height: 30px; margin-right: 10px;">
                                        <?php endif; ?>
                                        <?php echo $itemName; ?>
                                    </td>
                                    <td style="text-align: center;"><?php echo $quantity; ?></td>
                                    <td style="text-align: right;">Rs. <?php echo number_format($price, 2); ?></td>
                                    <td style="text-align: center;">
                                        <form method="post" action="./cart/cartManage.php">
                                            <input type="hidden" name="item_name" value="<?php echo $value['Item_Name']; ?>">
                                            <input type="hidden" name="item_index" value="<?php echo $key; ?>">
                                            <button type="submit" class="remove-btn" name="remove_item" style="background-color: #ff4c4c; color: white; border: none; padding: 5px 10px; cursor: pointer; font-size: 14px; border-radius: 5px;">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            $counter++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; font-size: 18px; color: #999;">Your cart is empty</td>
                            </tr>
                        <?php } ?>
                        <tr class="cart-total">
                            <td colspan="3" rowspan="2" style="padding: 15px; text-align: right;">Total</td>
                            <td colspan="2" class="total-amount" style="font-size: 24px; font-weight: bold; color: #e74c3c; text-align: right; padding: 15px;">
                                Rs. <?php echo number_format($total, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination" style="text-align: center; margin-top: 20px;">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" style="padding: 10px 15px; background-color: #3498db; color: white; text-decoration: none; border-radius: 5px;">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" style="padding: 10px 15px; background-color: <?php echo $i == $page ? '#3498db' : '#ddd'; ?>; color: <?php echo $i == $page ? 'white' : 'black'; ?>; text-decoration: none; border-radius: 5px; margin: 0 5px;"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" style="padding: 10px 15px; background-color: #3498db; color: white; text-decoration: none; border-radius: 5px;">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!-- <script src="./js/notification.js">
        console.log("Notification script loaded");
        console.log("showAlert function:", typeof showAlert);
    </script> -->
    <script src="./js/slide.js"> </script>
    
    <script>
        // Update button state for decrement button
        function updateButtonState(id, minLimit = 1) {
            const quantityElem = document.getElementById(id);
            const decrementBtn = document.querySelector(`button.decrement[data-id="${id}"]`);
            if (parseInt(quantityElem.textContent) <= minLimit) {
                decrementBtn.disabled = true;
            } else {
                decrementBtn.disabled = false;
            }
        }

        // Increment quantity function
        function incrementQuantity(id, maxLimit = 10) {
            const quantityElem = document.getElementById(id);
            let quantity = parseInt(quantityElem.textContent);
            if (quantity < maxLimit) {
                quantityElem.textContent = quantity + 1;
                updateButtonState(id);
            } else {
                alert(`Maximum quantity limit of ${maxLimit} reached`);
            }
        }

        // Decrement quantity function
        function decrementQuantity(id, minLimit = 1) {
            const quantityElem = document.getElementById(id);
            let quantity = parseInt(quantityElem.textContent);
            if (quantity > minLimit) {
                quantityElem.textContent = quantity - 1;
                updateButtonState(id);
            }
        }
    </script>
    
</body>

</html>