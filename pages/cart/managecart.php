<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_name = htmlspecialchars(trim($_POST['order_name'] ?? ''));
    $order_price = floatval($_POST['order_price'] ?? 0);
    $order_quantity = intval($_POST['order_quantity'] ?? 1);

    error_log("POST data: " . print_r($_POST, true)); // Debugging

    // Add to Cart
    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $check_product = array_column($_SESSION['cart'], 'orderName');
        if (in_array($order_name, $check_product)) {
            $_SESSION['message'] = "Order already placed!";
            header("Location: ../menu_page.php");
            exit;
        }

        $_SESSION['cart'][] = [
            'orderName' => $order_name,
            'orderPrice' => $order_price,
            'orderQuantity' => $order_quantity
        ];
        $_SESSION['message'] = "Item added to cart!";
        error_log("Cart after addition: " . print_r($_SESSION['cart'], true)); // Debugging
        header("Location: view_cart.php");
        exit;
    }

    // Remove Item
    if (isset($_POST['remove_item'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['orderName'] === $order_name) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                $_SESSION['message'] = "Item removed successfully!";
                break;
            }
        }
        header("Location: view_cart.php");
        exit;
    }

    // Update Item
    if (isset($_POST['update_item'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['orderName'] === $order_name) {
                if ($order_quantity > 0) {
                    $_SESSION['cart'][$key]['orderQuantity'] = $order_quantity;
                    $_SESSION['message'] = "Item updated successfully!";
                } else {
                    $_SESSION['message'] = "Quantity must be greater than 0!";
                }
                break;
            }
        }
        error_log("Cart after update: " . print_r($_SESSION['cart'], true)); // Debugging
        header("Location: view_cart.php");
        exit;
    }
}

/* 


// first second

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_name = htmlspecialchars(trim($_POST['order_name'] ?? ''));
    $order_price = floatval($_POST['order_price'] ?? 0);
    // $order_quantity = intval($_POST['order_quantity'] ?? 1);
    error_log("Received quantity: " . print_r($_POST['order_quantity'], true));


    // Add to Cart
    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $check_product = array_column($_SESSION['cart'], 'orderName');
        if (in_array($order_name, $check_product)) {
            $_SESSION['message'] = "Order already placed!";
            header("Location: ../menu_page.php");
            exit;
        }

        $_SESSION['cart'][] = [
            'orderName' => $order_name,
            'orderPrice' => $order_price,
            'orderQuantity' => $order_quantity
        ];
        $_SESSION['message'] = "Item added to cart!";
        header("Location: view_cart.php");
        exit;
    }

    // Remove Item
    if (isset($_POST['remove_item'])) {
        $order_to_remove = htmlspecialchars(trim($_POST['order_name'] ?? ''));
        $found = false;

        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['orderName'] === $order_to_remove) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                $_SESSION['message'] = "Item removed successfully!";
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['message'] = "Item not found!";
        }
        header("Location: view_cart.php");
        exit;
    }

    // Update Item
    if (isset($_POST['update_item'])) {
        $found = false;

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['orderName'] === $order_name) {
                $_SESSION['cart'][$key]['orderQuantity'] = $order_quantity;
                $_SESSION['message'] = "Item updated successfully!";
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['message'] = "Item not found for update!";
        }
        header("Location: view_cart.php");
        exit;
    }
}

?>
*/