<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_name = isset($_POST['order_name']) ? $_POST['order_name'] : '';
    $order_price = isset($_POST['order_price']) ? $_POST['order_price'] : 0;
    $order_quantity = isset($_POST['order_quantity']) ? $_POST['order_quantity'] : 1;

   
        // Add to cart functionality
        if (isset($_POST['add_to_cart'])) {
            // Initialize cart if not set
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
    
            // Check if the item is already in the cart
            $check_product = array_column($_SESSION['cart'], 'orderName');
            if (in_array($order_name, $check_product)) {
                echo "
                <script>
                    alert('Order already placed!');
                    window.location.href ='../menu_page.php';
                </script>";
            } else {
                // Add item to cart with user-specified quantity
                $_SESSION['cart'][] = [
                    'orderName' => $order_name,
                    'orderPrice' => $order_price,
                    'orderQuantity' => $order_quantity
                ];
    
                header("Location: view_cart.php");
                exit;
            }
        }
  
    



    // Remove item from cart
    if (isset($_POST['remove_item'])) {
        $order_to_remove = isset($_POST['order_name']) ? $_POST['order_name'] : '';

        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['orderName'] === $order_to_remove) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the cart array
                $_SESSION['message'] = "Item removed successfully!"; // Set success message
                break;
            }
        }
        header("Location: view_cart.php");
        exit;
    }

    // Update item in cart
    if (isset($_POST['update_item'])) {
        $order_name = isset($_POST['order_name']) ? $_POST['order_name'] : '';
        $order_quantity = isset($_POST['order_quantity']) ? $_POST['order_quantity'] : 1;

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['orderName'] === $order_name) {
                // Update the quantity
                $_SESSION['cart'][$key]['orderQuantity'] = $order_quantity;
                $_SESSION['message'] = "Item updated successfully!"; // Success message
                break;
            }
        }

        header("Location: view_cart.php");
        exit;
 }
}

?>
