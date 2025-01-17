<?php
session_start();

include "../pages/database/connection.php";
include "./cart/managecart.php";

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
    <title>PRACTICEMENU</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/order_table.css">
</head>

<body class="menu-body">

    <!-- -------------------- Menu Info Section -------------------- -->
    <section id="menu-info-wrapper">
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
                        <li><a href="#menu-food-info-wrapper">View Menu</a></li>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="menu-banner">
            <div class="menu-profile-name">
                <h1>Welcome, <?php echo htmlspecialchars($userName); ?></h1>
            </div>
            <div class="menu-info">
                <span class="menu-info-txt">Hungry for something amazing? <br>Let us bring the feast to you.<br>" Order your favorites now! "</span>
                <button class="btn reg-btn menu">
                    <a href="#menu-food-info-wrapper">ORDER FOOD</a>
                </button>
            </div>
        </div>
    </section>

    <!-- -------------------- Menu Food Info Section -------------------- -->
    <section id="menu-food-info-wrapper">
        <div class="menu-container-headline">
            <span class="menu-container-txt">OUR CULINARY DELIGHTS</span>
            
        </div>

        <div class="menu-container">
            <button class="arrow menu pre">
                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 1024 1024">
                    <path fill="#543787" d="M685.248 104.704a64 64 0 0 1 0 90.496L368.448 512l316.8 316.8a64 64 0 0 1-90.496 90.496L232.704 557.248a64 64 0 0 1 0-90.496l362.048-362.048a64 64 0 0 1 90.496 0" />
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

                                <!-- Dish Status Section -->
                                <div class="dish-status-wrapper">
                                    <span class="dish-status-text">Status:
                                        <button class="dish-status-label" id="dish-status"
                                            <?php if ($row['Status'] == '1') {
                                                echo 'style="background-color: #6EC531; color: #FEB737;"';
                                            } ?>>
                                            <?php echo ($row['Status'] == '1') ? 'Available' : 'Unavailable'; ?>
                                        </button>
                                    </span>
                                </div>

                                <!-- Quantity Section -->
                                <div class="dish-quantity-wrapper">
                                    <button class="btn-quantity decrement" type="button"
                                        onclick="decrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">-</button>
                                    <div class="dish-number" id="dish-quantity-<?php echo $row['Id']; ?>">1</div>
                                    <button class="btn-quantity increment" type="button"
                                        onclick="incrementQuantity('dish-quantity-<?php echo $row['Id']; ?>')">+</button>

                                    <!-- Hidden input for quantity -->
                                    <input type="hidden" id="order-quantity-<?php echo $row['Id']; ?>" name="quantity" value="1">
                                </div>

                                <!-- Add to Cart Button -->
                                <div class="add-to-cart-button">
                                    <?php if ($row['Status'] == '0') { ?>
                                        <button class="btn cart-btn" type="button" onclick="alert('This dish is not available for now')">
                                            <span class="cart-btn-text">Add to cart</span>
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn cart-btn" type="submit" name="add_to_cart">
                                            <span class="cart-btn-text">Add to cart</span>
                                        </button>
                                    <?php } ?>

                                    <!-- Hidden Inputs for Order Details -->
                                    <input type="hidden" name="order_name" value="<?php echo htmlspecialchars($row['Name']); ?>">
                                    <input type="hidden" name="order_price" value="<?php echo htmlspecialchars($row['Price']); ?>">
                                   

                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </section>

    
    <script src="../pages/js/slide.js"></script>
    <script>
       // Increment Quantity
function incrementQuantity(displayId, inputId) {
    const quantityElement = document.getElementById(displayId);
    const quantityInput = document.getElementById(inputId);
    let quantity = parseInt(quantityElement.textContent);
    quantity++;
    quantityElement.textContent = quantity;
    quantityInput.value = quantity; // Update the hidden input
}

// Decrement Quantity
function decrementQuantity(displayId, inputId) {
    const quantityElement = document.getElementById(displayId);
    const quantityInput = document.getElementById(inputId);
    let quantity = parseInt(quantityElement.textContent);
    if (quantity > 1) {
        quantity--;
        quantityElement.textContent = quantity;
        quantityInput.value = quantity; // Update the hidden input
    }
}

    </script>
</body>

</html>
