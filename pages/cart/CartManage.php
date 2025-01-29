<?php
session_start();
error_reporting(E_ALL);
// session_destroy();
ini_set('display_errors', 1);
// include_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add to cart
    if (isset($_POST['add_to_cart'])) {
        var_dump($_POST);
        if (isset($_SESSION['cart'])) {
            $myitems = array_column($_SESSION['cart'], 'Item_Name');

            if (in_array($_POST['Item_Name'], $myitems)) {
                $_SESSION['msg'] = "Item already added";
                $_SESSION['type'] = 'warning';
                header("Location: ../menu.php");
                exit();
            } else {
                $count = count($_SESSION['cart']);
                $_SESSION['cart'][$count] = array(
                    'image' => $_POST['image'],
                    'Item_Name' => $_POST['Item_Name'],
                    'Price' => $_POST['Price'],
                    'Quantity' => 1
                );
                $_SESSION['msg'] = "Item added";
                header("Location: ../menu.php");
                exit();
            }
        } else {
            $_SESSION['cart'][0] = array(
                'image' => $_POST['image'],
                'Item_Name' => $_POST['Item_Name'],
                'Price' => $_POST['Price'],
                'Quantity' => 1
            );
            $_SESSION['msg'] = "Item added";
            header("Location: ../menu.php");
            exit();
        }
    } elseif (isset($_POST['remove_item']) && isset($_POST['item_name'])) {
        $item_name = $_POST['item_name'];
    
        // Check if the cart exists in the session
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $item_removed = false;
            
            // Loop through the cart and find the item with the matching Item_Name
            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['Item_Name'] === $item_name) {
                    unset($_SESSION['cart'][$key]);  // Remove the item
                    $item_removed = true;
                    break;
                }
            }
    
            if ($item_removed) {
                // Reindex the session array or simply re assign the updated value
                $_SESSION['cart'] = array_values($_SESSION['cart']); 
                $_SESSION['msg'] = "Item removed successfully.";
                $_SESSION['type'] = 'success';
            } else {
                $_SESSION['msg'] = "Item not found in cart.";
                $_SESSION['type'] = 'error';
            }
        } else {
            $_SESSION['msg'] = "Cart is empty.";
            $_SESSION['type'] = 'warning';
        }
    
        // Redirect back to menu
        header("Location: ../menu.php");
        exit();
    } else {
        $_SESSION['msg'] = "uh ahh ! An Error Occured";
        $_SESSION['type'] = 'error';
        header("Location: ../menu.php");
        exit();
    }
}
?>
