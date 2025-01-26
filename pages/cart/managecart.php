<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add to cart
    if (isset($_POST['add_to_cart'])) {
        // Ensure order_quantity is set and is a positive integer
        $order_quantity = isset($_POST['order_quantity']) ? max(1, intval($_POST['order_quantity'])) : 1;

        if (isset($_SESSION['cart'])) {
            $myitems = array_column($_SESSION['cart'], 'order_name');

            if (in_array($_POST['order_name'], $myitems)) {
                // Update quantity if item already exists
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['order_name'] === $_POST['order_name']) {
                        $_SESSION['cart'][$key]['order_quantity'] += $order_quantity;
                        break;
                    }
                }
                $_SESSION['message'] = "Quantity updated for {$_POST['order_name']}.";
                $_SESSION['message_type'] = 'error';
            } else {
                // Add new item to cart
                $_SESSION['cart'][] = array(
                    'image' => $_POST['image'] ?? '', // Use null coalescing operator
                    'order_name' => $_POST['order_name'],
                    'order_price' => $_POST['order_price'],
                    'order_quantity' => $order_quantity
                );
                $_SESSION['message'] = "Item added: {$_POST['order_name']}";
                $_SESSION['message_type'] = 'success';
            }
        } else {
            // First item in cart
            $_SESSION['cart'] = [
                [
                    'image' => $_POST['image'] ?? '',
                    'order_name' => $_POST['order_name'],
                    'order_price' => $_POST['order_price'],
                    'order_quantity' => $order_quantity
                ]
            ];
            $_SESSION['message'] = "Item added: {$_POST['order_name']}";
            $_SESSION['message_type'] = 'success';
        }
    } 
    // Remove item from cart
    elseif (isset($_POST['remove_item']) && isset($_POST['order_name'])) {
        $order_name = $_POST['order_name'];
    
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($order_name) {
                return $item['order_name'] !== $order_name;
            });
    
            $_SESSION['message'] = "Item removed: $order_name";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Cart is empty.";
            $_SESSION['message_type'] = 'warning';
        }
    }
    //update item qty  from view cart page
    elseif (isset($_POST['update_item']) && isset($_POST['order_name'])) {
        $order_name = $_POST['order_name'];
        $new_quantity = max(1, intval($_POST['order_quantity']));
    
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['order_name'] === $order_name) {
                    $_SESSION['cart'][$key]['order_quantity'] = $new_quantity;
                    $_SESSION['message'] = "Quantity updated for $order_name.";
                    $_SESSION['message_type'] = 'success';
                    break;
                }
            }
        }
    
        // Redirect back to cart page
        header("Location: ../cart/view_cart.php");
        exit();
    }
    // Error handling
    else {
        $_SESSION['message'] = "An error occurred.";
        $_SESSION['message_type'] = 'error';
    }

    // Redirect back to menu
    header("Location: ../menu_page.php");
    exit();
}
?>