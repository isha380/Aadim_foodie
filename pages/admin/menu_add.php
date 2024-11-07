<?php
session_start();
include "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

$adminName = $_SESSION['admin'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['food_name'], $_POST['price'], $_POST['status']) && isset($_FILES['image'])) {
        // Retrieve and sanitize form inputs
        $name = mysqli_real_escape_string($conn, $_POST['food_name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $file_name = $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $folder = '../../assets/image/menu/' . $file_name;

        // Move the uploaded file to the target folder
        if (move_uploaded_file($temp_name, $folder)) {
            
            $query = "INSERT INTO menu_items (Image, Name, Status, Price) VALUES ('$file_name', '$name', '$status', '$price')";
            if (mysqli_query($conn, $query)) {
                
                $_SESSION['message'] = "Food item added successfully!";
            } else {
               
                $_SESSION['message'] = "Error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['message'] = "Failed to upload the image.";
        }
    } else {
        $_SESSION['message'] = "Please fill in all required fields.";
    }
    // Redirect to the same page to clear form inputs and show message only once
    header('Location: menu_add.php');
    exit();
}

// Retrieve the message from session (if any) and then unset it
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);  // Clear the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/component.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="../../assets/css/user_content.css">
    <link rel="stylesheet" href="../../assets/css/user_add.css">
    <link rel="stylesheet" href="../../assets/css/menu_add.css">
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
        <div class="dash-reg-view-wrapper">
            <div class="dash-view-txt user-add">
                <h1>DASHBOARD</h1>
            </div>
            <div class="reg-wrapper dash menu">
                <div class="reg-headline">
                    <h2>Add Food</h2>
                </div>
                <div class="reg-img">
                    <img src="../../assets/image/icon/eat.png">
                </div>
                <div class="reg-info-container">
                    <?php if ($message): ?>
                        <p style="margin-bottom: 8px; color: darkblue; font-size: 20px; margin-top: -22px;"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    <form action="menu_add.php" method="POST" enctype="multipart/form-data">
                        <div class="std-teach image">
                            <label for="image">Image:</label>
                            <input type="file" id="food_image" name="image" required>
                        </div>
                        <div class="std-teach name">
                            <label for="name">Name:</label>
                            <input type="text" id="food_menu_name" name="food_name" required>
                        </div>
                        <div class="std-teach price">
                            <label for="price">Price:</label>
                            <input type="number" id="food_price" name="price" required>
                        </div>
                        <div class="std-teach status">
                            <label>Status:</label>
                            <div class="menu_add status">
                            <label for="status-available">
                                <input type="radio" id="status-available" value="available" name="status" required>
                                Available
                            </label>
                            <label for="status-unavailable">
                                <input type="radio" id="status-unavailable" value="unavailable" name="status">
                                Unavailable
                            </label>
                            </div>
                        </div>

                        <div class="register-btn">
                            <button type="submit" class="btn reg-btn">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/dropdown-dash.js"></script>
</body>

</html>