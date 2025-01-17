<?php
session_start();
include "../pages/database/connection.php";
include "./cart/managecart.php";

if (!isset($_SESSION['roll']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $itemName = $_POST['item_name'];
        $itemPrice = $_POST['item_price'];
        $itemQuantity = 1; // Default quantity for new items

        if (isset($_SESSION['cart'])) {
            $myitems = array_column($_SESSION['cart'], 'order_name');

            // Check if the item is already in the cart
            if (in_array($itemName, $myitems)) {
                echo "<script>
                    alert('Dish already added');
                    window.location.href='menu.php';
                </script>";
            } else {
                $count = count($_SESSION['cart']);
                $_SESSION['cart'][$count] = array('order_name' => $itemName, 'Price' => $itemPrice, 'Quantity' => $itemQuantity);
                echo "<script>
                    alert('Dish added');
                    window.location.href='menu.php';
                </script>";
            }
        } else {
            // First item in the cart
            $_SESSION['cart'][0] = array('order_name' => $itemName, 'Price' => $itemPrice, 'Quantity' => $itemQuantity);
            echo "<script>
                alert('Dish added');
                window.location.href='menu.php';
            </script>";
        }
    }

    if (isset($_POST['remove_button'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['order_name'] == $_POST['item_name']) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
                echo "<script>
                    alert('Order removed');
                    window.location.href='menu.php';
                </script>";
                break;
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/menu_view.css">
    <link rel="stylesheet" href="../assets/css/order_table.css">
</head>

<body id="menu-view">

    <section id="menu-food-info-wrapper view">
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
        <div class="menu-container-headline view">
            <span class="menu-container-txt">OUR CULINARY DELIGHTS</span>
        </div>

        <div class="menu-container-view">


            <div class="dish-slide-container menu view">
                <?php


                $res = mysqli_query($conn, "SELECT * FROM menu_items");

                // Loop through each item and display it
                while ($row = mysqli_fetch_assoc($res)) {

                ?>
                    <form method="POST" action="./cart/managecart.php">
                        <div class="dish-content-wrapper menu">
                            <div class="dish-image">
                                <img src="../assets/image/menu/<?php echo htmlspecialchars($row['Image']); ?>" alt="Dish Image">
                            </div>
                            <div class="dish-info">
                                <div class="dish-name">
                                    <button class="btn dish"><?php echo htmlspecialchars($row['Name']); ?></button>
                                </div>
                                <div class="dish-price">
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
                                    <button class="btn-quantity decrement" type="button" onclick="decrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">-</button>
                                    <div class="dish-number" id="dish-quantity-<?php echo $row['Id']; ?>">1</div>
                                    <button class="btn-quantity increment" type="button" onclick="incrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">+</button>
                                </div>

                                <div class="add-to-cart-button">

                                    <?php
                                    if ($row['Status'] == '0') {
                                        echo  '<button class="btn cart-btn" onclick="alert(\'This dish is not available for now\')">
                                     <span class="cart-btn-text">Add to cart</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path fill="#feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                                    </svg></button>';
                                    } else {
                                        echo  '<button class="btn cart-btn" type="submit" name="add_to_cart">
                                     <span class="cart-btn-text">Add to cart</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path fill="#feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                                    </svg></button>';
                                    }

                                    ?>
                                    <input type="hidden" name="order_name" value="<?php $row['Name'] ?>">
                                    <input type="hidden" name="order_price" value="<?php $row['Price'] ?>">
                                    <input type="hidden" id="order-quantity-<?php echo $row['Id']; ?>" name="quantity" value="1">
                                </div>
                    </form>
            </div>
        </div>
    <?php
                }
    ?>
    </div>

    </div>
    </section>


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