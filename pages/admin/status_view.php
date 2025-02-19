<?php
session_start();
include "../database/connection.php";

// Checking if user logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Handle status update via regular form submission
if (isset($_POST['update_status'])) {
    $cartId = $_POST['cartId'];
    $newStatus = $_POST['status'];
    
    // Update the status in the database
    $updateQuery = "UPDATE order_food SET Order_Status = ? WHERE Cart_Id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ss", $newStatus, $cartId);
    
    if ($updateStmt->execute()) {
        $_SESSION['message'] = "Order status updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update order status: " . $conn->error;
    }
    
    // Redirect back to the same page to reflect changes
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Rest of your existing code for retrieving admin name and order information
$adminName = $_SESSION['admin'];

// Fetching orders for today
$info = [];
$query = "SELECT order_food.*, student_info.Name 
          FROM order_food 
          INNER JOIN student_info ON order_food.Student_Id = student_info.Rollnum
          WHERE DATE(order_food.Order_Date) = CURDATE()";

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $info[] = $row;
    }
} else {
    echo "Error ACCESS DENIED!! " . mysqli_error($conn);
}


// Initialize search results
$searchResults = $info; // Default to all students
$searchMessage = ""; // Message variable for search feedback

if (isset($_POST['submit'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search_data']); // Secure the search input

    if (empty($search)) {
        $searchMessage = "Please enter a search term."; // Message for empty search input
    } else {
        $sql = "SELECT order_food.*, student_info.Name FROM order_food 
        INNER JOIN student_info ON order_food.Student_Id = student_info.Rollnum
        WHERE order_food.Student_Id LIKE ? OR order_food.Dish_name LIKE ? OR student_info.Name LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%{$search}%";
        $stmt->bind_param("sss", $searchTerm, $searchTerm,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $searchResults = []; // Clear previous results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row; // Store only search results
                }
            } else {
                $searchMessage = "No results found for '$search'."; // Message for no matching records
            }
        } else {
            echo "Search Error: " . mysqli_error($conn);
        }
    }
}

// Count total orders
$stmt = mysqli_query($conn, "SELECT COUNT(*) AS total FROM order_food");
$data = mysqli_fetch_assoc($stmt);
$total = $data['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/component.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="../../assets/css/user_content.css">
    <style>
        .order-status-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: transparent;
            z-index: 1000;
            width: 150px;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: rgb(255, 243, 209);
        }

        .current-date {
            font-size: 16px;
            margin-left: 10px;
            color: #333;
        }
    </style>
</head>

<body class="dashboard">
    <div class="dash-wrapper">
        <div class="dash-nav-wrapper">
            <div class="dash-nav-img">
                <img src="../../assets/image/icon/final_light logo.png">
            </div>
            <div class="dash-nav-profile">
                <div class="dash-profile"><svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.96 11.947A4.99 4.99 0 0 1 9 14h6a4.99 4.99 0 0 1 3.96 1.947A8 8 0 0 0 12 4m7.943 14.076q.188-.245.36-.502A9.96 9.96 0 0 0 22 12c0-5.523-4.477-10-10-10S2 6.477 2 12a9.96 9.96 0 0 0 2.057 6.076l-.005.018l.355.413A9.98 9.98 0 0 0 12 22q.324 0 .644-.02a9.95 9.95 0 0 0 5.031-1.745a10 10 0 0 0 1.918-1.728l.355-.413zM12 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6" clip-rule="evenodd" />
                    </svg></div>
                <div class="dash-profile-name">
                    <h1>Welcome,</br> <?php echo htmlspecialchars($adminName); ?>!</h1>
                </div>

            </div>
            <div class="dash-panel">
                <div class="dash-panel-txt">
                    <span class="dash underline">Dashboard</span>
                </div>
                <div class="dash-panel-container">

                    <div class="dash-panel-wrapper status">
                        <div class="dash-status-txt">
                            <span class="dash dropdown ">Status</span>


                        </div>
                    </div>
                    <div class="dash-panel-wrapper users">
                        <div class="dash-users-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Users</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/user_content.php">Users</a>
                                <a href="../admin/user_add.php">Add User</a>
                            </div>
                        </div>
                    </div>
                    <div class="dash-panel-wrapper staffs">
                        <div class="dash-staffs-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Staffs</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/staff_view.php">Users</a>
                                <a href="../admin/staff_add.php">Add Staffs</a>
                            </div>
                        </div>
                    </div>
                    <div class="dash-panel-wrapper menu">
                        <div class="dash-menu-txt">
                            <span class="dash dropdown" onclick="dropdown_show(this)">Menu</span>
                            <div class="dropdown-content" style="display: none;">
                                <a href="../admin/menu_view.php">View </a>
                                <a href="../admin/menu_add.php">Add Menu</a>
                            </div>
                        </div>
                    </div>
                    <button class="btn reg-btn dash"><a href="../admin/admin_dash.php" style="text-decoration: none;">Back</a>
                    </button>
                </div>
            </div>
        </div>
        <div class="dash-view-wrapper">
            <div class="dash-view-txt">
                <h1>DASHBOARD</h1>
            </div>
            <!-- student info view     -->
            <button class="student-info-btn">TODAY'S ORDER DETAILS</button>
            <span class="current-date" style="color: purple; font-size:20px; font-weight:bold;">Date:<?php echo date("Y-m-d"); ?></span>
            <div class="search-total-wrapper">
                <form method="POST" class="search-wrapper">
                    <div class="search-box">
                        <input type="text" placeholder="Search id/name/food" name="search_data">
                    </div>
                    <div class="search-btn">
                        <button class="btn search" name="submit">Search</button>
                    </div>
                </form>
                <div class="total-data-container">
                    <span class="total data"> Total orders:<button class="total-data"><?php echo  $total; ?></button></span>
                </div>
            </div>

            <?php if ($searchMessage): // Display the search message if it exists 
            ?>
                <div class="search-message">
                    <p><?php echo htmlspecialchars($searchMessage); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['message'])): ?>
                <p id="message" style='color: green; font-weight: bold;'><?php echo $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <!-- Order table -->
            <div class="table student">
                <table>
                    <thead>
                        <tr>
                            <th>Roll Num</th>
                            <th>Cart No.</th>
                            <th>Name</th>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['Student_Id']); ?></td>
                                <td class="cart-id"><?php echo htmlspecialchars($order['Cart_Id']); ?></td>
                                <td><?php echo htmlspecialchars($order['Name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Dish_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['Price']); ?></td>
                                <td>
                                    <div class="order-status-dropdown">
                                        <button class="edit-btn status-btn" onclick="toggleDropdown(this)">
                                            <span class="status-icon" id="status-icon-<?php echo $order['Cart_Id']; ?>">
                                                <?php if ($order['Order_Status'] == 'Received'): ?>
                                                    <!-- Received Icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" title="Received">
                                                        <path fill="#48cb0d" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m-.232-5.36l5-6l-1.536-1.28l-4.3 5.159l-2.225-2.226l-1.414 1.414l3 3l.774.774z" clip-rule="evenodd" />
                                                    </svg>
                                                <?php else: ?>
                                                    <!-- Pending Icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                        <g>
                                                            <path fill="currentColor" d="M7 3H17V7.2L12 12L7 7.2V3Z">
                                                                <animate id="eosIconsHourglass0" fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="1" to="0" />
                                                            </path>
                                                            <path fill="currentColor" d="M17 21H7V16.8L12 12L17 16.8V21Z">
                                                                <animate fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="0" to="1" />
                                                            </path>
                                                            <path fill="currentColor" d="M6 2V8H6.01L6 8.01L10 12L6 16L6.01 16.01H6V22H18V16.01H17.99L18 16L14 12L18 8.01L17.99 8H18V2H6ZM16 16.5V20H8V16.5L12 12.5L16 16.5ZM12 11.5L8 7.5V4H16V7.5L12 11.5Z" />
                                                            <animateTransform id="eosIconsHourglass1" attributeName="transform" attributeType="XML" begin="eosIconsHourglass0.end" dur="0.5s" from="0 12 12" to="180 12 12" type="rotate" />
                                                        </g>
                                                    </svg>
                                                <?php endif; ?>
                                            </span>
                                        </button>

                                        <div class="dropdown-menu">
                                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type="hidden" name="cartId" value="<?php echo $order['Cart_Id']; ?>">
                                                <input type="hidden" name="status" value="Pending">
                                                <button type="submit" name="update_status" class="dropdown-item" data-status="Pending">
                                                    <span class="icon-pending">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                            <g>
                                                                <path fill="currentColor" d="M7 3H17V7.2L12 12L7 7.2V3Z">
                                                                    <animate id="eosIconsHourglass0" fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="1" to="0" />
                                                                </path>
                                                                <path fill="currentColor" d="M17 21H7V16.8L12 12L17 16.8V21Z">
                                                                    <animate fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="0" to="1" />
                                                                </path>
                                                                <path fill="currentColor" d="M6 2V8H6.01L6 8.01L10 12L6 16L6.01 16.01H6V22H18V16.01H17.99L18 16L14 12L18 8.01L17.99 8H18V2H6ZM16 16.5V20H8V16.5L12 12.5L16 16.5ZM12 11.5L8 7.5V4H16V7.5L12 11.5Z" />
                                                                <animateTransform id="eosIconsHourglass1" attributeName="transform" attributeType="XML" begin="eosIconsHourglass0.end" dur="0.5s" from="0 12 12" to="180 12 12" type="rotate" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    Pending..
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type="hidden" name="cartId" value="<?php echo $order['Cart_Id']; ?>">
                                                <input type="hidden" name="status" value="Received">
                                                <button type="submit" name="update_status" class="dropdown-item" data-status="Received">
                                                    <span class="icon-received">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                            <path fill="#48cb0d" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m-.232-5.36l5-6l-1.536-1.28l-4.3 5.159l-2.225-2.226l-1.414 1.414l3 3l.774.774z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                    Received
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action button">
                                        <!-- Paid Button -->
                                        <div class="update button">
                                            <a href="../order/paid.php?id=<?php echo $order['Cart_Id']; ?>" onclick="return confirm('Did customer pay?')" <?php if($order['Order_Status'] == 'Pending') echo 'style="pointer-events: none; opacity: 0.5;"'; ?>>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor">
                                                        <path d="M19.745 13a7 7 0 1 0-12.072-1" />
                                                        <path d="M14 6c-1.105 0-2 .672-2 1.5S12.895 9 14 9s2 .672 2 1.5s-.895 1.5-2 1.5m0-6c.87 0 1.612.417 1.886 1M14 6V5m0 7c-.87 0-1.612-.417-1.886-1M14 12v1M3 14h2.395c.294 0 .584.066.847.194l2.042.988c.263.127.553.193.848.193h1.042c1.008 0 1.826.791 1.826 1.767c0 .04-.027.074-.066.085l-2.541.703a1.95 1.95 0 0 1-1.368-.124L5.842 16.75M12 16.5l4.593-1.411a1.985 1.985 0 0 1 2.204.753c.369.51.219 1.242-.319 1.552l-7.515 4.337a2 2 0 0 1-1.568.187L3 20.02" />
                                                    </g>
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Not Paid Button -->
                                        <div class="update button">
                                            <a href="../order/notpaid.php?id=<?php echo $order['Cart_Id']; ?>" onclick="return confirm('Did customer not pay?')" <?php if($order['Order_Status'] == 'Pending') echo 'style="pointer-events: none; opacity: 0.5;"'; ?>>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 20 20">
                                                    <g fill="currentColor">
                                                        <path fill-rule="evenodd" d="M7.897 5.7c-.551.413-.8.908-.8 1.37s.249.958.8 1.372c.552.414 1.36.7 2.295.7a1 1 0 1 1 0 2c-1.326 0-2.565-.402-3.495-1.1s-1.6-1.738-1.6-2.971s.67-2.274 1.6-2.972C7.627 3.402 8.867 3 10.192 3c2.053 0 3.994.983 4.766 2.62a1 1 0 0 1-1.81.853C12.798 5.726 11.706 5 10.193 5c-.935 0-1.743.286-2.295.7" clip-rule="evenodd" />
                                                        <path fill-rule="evenodd" d="M12.157 14.583c.551-.413.799-.908.799-1.37s-.248-.959-.8-1.372c-.551-.414-1.36-.7-2.294-.7a1 1 0 1 1 0-2c1.326 0 2.565.402 3.495 1.1s1.599 1.738 1.599 2.971s-.669 2.274-1.6 2.971c-.93.698-2.168 1.1-3.494 1.1c-2.053 0-3.995-.983-4.766-2.621a1 1 0 0 1 1.809-.853c.352.748 1.444 1.474 2.957 1.474c.935 0 1.743-.286 2.295-.7M10 1a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V2a1 1 0 0 1 1-1" clip-rule="evenodd" />
                                                        <path fill-rule="evenodd" d="M10 16a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1" clip-rule="evenodd" />
                                                        <path d="M1.293 2.707a1 1 0 0 1 1.414-1.414l16 16a1 1 0 0 1-1.414 1.414z" />
                                                    </g>
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Delete Button -->
                                        <div class="delete button">
                                            <a href="../order/cancel_order.php?id=<?php echo $order['Cart_Id']; ?>" onclick="return confirm('Are you sure you want to cancel order?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zM19 4h-3.5l-1-1h-5l-1 1H5v2h14z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript for dropdowns -->
    <script src="../js/dropdown-dash.js"></script>
    
    <script>
        // Function to handle dropdown toggle
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            
            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('show');
        }
        
        // Close dropdowns when clicking elsewhere on the page
        document.addEventListener('click', function(event) {
            if (!event.target.matches('.status-btn') && !event.target.closest('.status-btn')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
        
        // Auto-hide messages after 2 seconds
        window.onload = function() {
            setTimeout(function() {
                var message = document.getElementById('message');
                if (message) {
                    message.style.display = 'none';
                }
            }, 2000);
        };
    </script>
</body>
</html>