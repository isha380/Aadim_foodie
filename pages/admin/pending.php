<?php
session_start();
include "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve admin name
$adminName = $_SESSION['admin'];

// Fetching data from pending_payments table
$info = [];
$query = "SELECT pending_payments.*, student_info.Name 
          FROM pending_payments 
          INNER JOIN student_info ON pending_payments.Student_Id = student_info.Rollnum"; // Fetch all data


$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $info[] = $row; // Store result in $info array
    }
} else {
    echo "Error: " . mysqli_error($conn); // Handle query error
}

// Initialize search results
$searchResults = $info; // Default to all orders
$searchMessage = ""; // Initialize search message

// Search functionality
if (isset($_POST['submit'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search_data']); // Secure the search input

    if (empty($search)) {
        $searchMessage = "Please enter a search term."; // Message if search field is empty
    } else {
        $sql = "SELECT pending_payments.*, student_info.Name 
                FROM pending_payments 
                INNER JOIN student_info ON pending_payments.Student_Id = student_info.Rollnum
                WHERE pending_payments.Student_Id LIKE ? 
                OR pending_payments.Dish_Name LIKE ? 
                OR student_info.Name LIKE ? 
                OR DATE(pending_payments.Date) LIKE ?";
        
        $stmt = $conn->prepare($sql);
        $searchTerm = "%{$search}%"; // Add wildcard to search term
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm); // Bind params for search
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $searchResults = []; // Clear previous search results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row; // Store search results
                }
            } else {
                $searchMessage = "No results found for '$search'."; // Message if no results are found
            }
        } else {
            echo "Search Error: " . mysqli_error($conn); // Handle query error
        }
    }
}

// Fetch total order count (if needed)
$stmt = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pending_payments");
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
            <button class="student-info-btn">PAYMENT DUE DETAILS</button>
            <span class="current-date" style="color: purple; font-size:20px; font-weight:bold;">Date:<?php echo date("Y-m-d"); ?></span>
            <div class="search-total-wrapper">
                <form method="POST" class="search-wrapper">
                    <div class="search-box">
                        <input type="text" placeholder="Search by ID/name/food/date" name="search_data">
                    </div>
                    <div class="search-btn">
                        <button class="btn search" name="submit">Search</button>
                    </div>
                </form>
                <div class="total-data-container">
                    <span class="total data"> Total orders:<button class="total-data"><?php echo  $total; ?></button></span>
                </div>
            </div>

            <?php if ($searchMessage): ?>
                <div class="search-message">
                    <p><?php echo htmlspecialchars($searchMessage); ?></p>
                </div>
            <?php endif; ?>

            <div class="table student">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Dish Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Due Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['Student_Id']); ?></td>
                                <td><?php echo htmlspecialchars($order['Name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Dish_Name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['Price']); ?></td>
                                <td><?php echo htmlspecialchars($order['Due_Amount']); ?></td>
                                <td><?php echo htmlspecialchars($order['Date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script src="../js/dropdown-dash.js"></script>
</body>

</html>
