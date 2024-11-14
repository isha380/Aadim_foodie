<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        if (isset($_SESSION['cart'])) {

            $items = array_column($_SESSION['cart'], 'order_name');
            if (in_array($_POST['order_name'], $items)) {
                echo "<script>
                alert('Dish Already added');
            
                </script>";
                header("Location:menu.php");
            } 
            else {
                $count = count($_SESSION['cart']);
                $_SESSION['cart'][$count] = array('order_name' => $_POST['order_name'], 'order_price' => $_POST['order_price'], 'quantity' => $_POST['quantity']);
                echo "<script>
                alert('Dish Already added');
      
                </script>";
                header("Location:menu.php");
            }
        } 
        else {
            $_SESSION['cart'] = array('order_name' => $_POST['order_name'], 'order_price' => $_POST['order_price'], 'quantity' => $_POST['quantity']);
            echo "<script>
            alert('Dish added');
        
            </script>";
            header("Location:menu.php");
        }
    }
    if(isset($_POST['remove_dish'])){
        foreach($_SESSION['cart'] as $key => $value){
           if($value['order_name']== $_POST['remove_dish']){
              unset($_SESSION['cart'][$key]);
              $_SESSION['cart']=array_values($_SESSION['cart']);
              echo"
              <script>
              alert ('Dish removed');
              </script>
              
              ";
              header("Location:menu.php");
           }
        }
    }
}
