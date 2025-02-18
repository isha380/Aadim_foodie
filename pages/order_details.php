<?php
session_start(); // Ensure session is started

include "../pages/database/connection.php";

// Check if the student is logged in
if (!isset($_SESSION['roll'])) {
    die("Unauthorized access. Please log in.");
}

// Get logged-in student's ID
$studentId = $_SESSION['roll']; // Assuming 'roll' stores Student_Id

// Fetch pending payment details for the logged-in student
$query = "SELECT 
            Student_Id, 
            Name, 
            Dish_Name, 
            Quantity, 
            Price, 
            Date, 
            Due_Amount
          FROM 
            pending_payments
          WHERE 
            Due_Amount > 0 
            AND Student_Id = ?"; // Fetch only logged-in student's pending payments



// Prepare and execute the query safely
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Details</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/order_table.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
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
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            background-color: #FFEBAE;
            border-radius: 8px;
        }

        .my_order thead#order_table_head {
            background-color: #FFEBAE !important;
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

        .total-container button,
        .back-btn button {
            background-color: #543787;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .total-container button:hover,
        .back-btn button:hover {
            background-color: #6a4ba3;
        }
    </style>
</head>

<body id="view_cart">
    <section id="food-order-wrapper">
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
                        <li><a href="../menu_page.php"><button>Back</button></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-wrapper">
            <table class="my_order">
                <thead id="order_table_head">
                    <tr>
                        <th>S.No</th>
                        <th>Dish Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Order Date</th>
                      
                       
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $orderName = htmlspecialchars($row['Dish_Name']); // Corrected case
                        $orderPrice = floatval($row['Price']);
                        $orderQuantity = intval($row['Quantity']);
                        $orderDate = htmlspecialchars($row['Date']); // Use 'Date' instead of 'Order_Date'
                       
                        $itemTotalPrice = $orderPrice * $orderQuantity;
                        $total += $itemTotalPrice;
                    ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $orderName; ?></td>
                            <td><?php echo $orderQuantity; ?></td>
                            <td><?php echo number_format($itemTotalPrice, 2); ?></td>
                            <td><?php echo $orderDate; ?></td>
                        </tr>
                    <?php } ?>
                    
                </tbody>
            </table>

            <div class="order-total-wrapper">
                <div class="total-container">
                    <h3>Total dues:</h3>
                    <h5>Rs.<?php echo number_format($total, 2); ?></h5>
                    
                </div>
                <div class="back-btn">
                    <button onclick="window.location.href='../pages/menu_page.php';">Back</button>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
