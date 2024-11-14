<?php
session_start();
include "../database/connection.php";

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$adminName = $_SESSION['admin'];

// Initialize the $food variable to avoid "undefined variable" warnings
$food = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute query to fetch food item data
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $food = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['food_name'];
            $price = $_POST['price'];
            $status = $_POST['status'];
        
            // Handle the file upload
            if (!empty($_FILES['image']['name'])) {
                // A new image is uploaded
                $file_name = $_FILES['image']['name'];
                $temp_name = $_FILES['image']['tmp_name'];
                $folder =  $file_name;

        
                // Move the uploaded file to the target folder
                if (move_uploaded_file($temp_name, $folder)) {
                    $imagePath = $folder; // path for updating the database
                } else {
                    $_SESSION['message'] = "Failed to upload the image.";
                    $imagePath = $food['Image']; // keep the old image if the upload fails
                }
            } else {
                // No new image uploaded, keep the old one
                $imagePath = $food['Image'];
            }
        
            // Prepare and execute the update query
            $updateStmt = $conn->prepare("UPDATE menu_items SET Image = ?, Name = ?, Price = ?, Status = ? WHERE Id = ?");
            $updateStmt->bind_param("ssssi", $imagePath, $name, $price, $status, $id);
        
            if ($updateStmt->execute()) {
                $_SESSION['update_success'] = "Updated Successfully!";
                header("Location: cook_update_menu.php?id=" . $id);
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
            $updateStmt->close();
        }
    }
} else {
    echo "Invalid request.";
}

$stmt->close();
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Menu</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/component.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="../../assets/css/user_content.css">
</head>

<body class="bg-img update-std">
    <div class="dash-wrapper">
        <div class="dash-nav-wrapper">
            <div class="dash-nav-img">
                <img src="../../assets/image/icon/final_light logo.png">
            </div>
            <div class="dash-nav-profile">
                <div class="dash-profile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.96 11.947A4.99 4.99 0 0 1 9 14h6a4.99 4.99 0 0 1 3.96 1.947A8 8 0 0 0 12 4m7.943 14.076q.188-.245.36-.502A9.96 9.96 0 0 0 22 12c0-5.523-4.477-10-10-10S2 6.477 2 12a9.96 9.96 0 0 0 2.057 6.076l-.005.018l.355.413A9.98 9.98 0 0 0 12 22q.324 0 .644-.02a9.95 9.95 0 0 0 5.031-1.745a10 10 0 0 0 1.918-1.728l.355-.413zM12 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="dash-profile-name">
                    <h1>Welcome,<br> <?php echo htmlspecialchars($adminName); ?>!</h1>
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
                    <button class="btn reg-btn dash"><a href="../admin/admin_dash.php" style="text-decoration: none;">Back</a></button>
                </div>
            </div>
        </div>
        <div class="dash-view-wrapper">
            <div class="dash-view-txt">
                <h1>DASHBOARD</h1>
            </div>
            <?php
            if (isset($_SESSION['update_success'])) {
                echo "<p style='color: darkblue;'>" . $_SESSION['update_success'] . "</p>";
                unset($_SESSION['update_success']); // Removes the message after displaying it
            }
            ?>
            <div class="login-wrapper std update">
                <form method="POST" action="update_menu.php?id=<?php echo $food['Id'] ?? ''; ?>" enctype="multipart/form-data">
                    <div class="id-num staff update">
                        <span> ID:<?php echo htmlspecialchars($food['Id']) ?></span> <br>
                    </div>
                    <label>Image:</label>
                    <input type="file" name="image"  ><br>
                    <?php if (!empty($food['Image'])): ?>
    <p>Current Image:</p>
    <img src="../../assets/image/menu/<?php echo htmlspecialchars($food['Image']); ?>" alt="Current Image" style="width: 150px; height: auto;">
<?php endif; ?>


                    <label>Name:</label>
                    <input type="text" name="food_name" value="<?php echo htmlspecialchars($food['Name'] ?? ''); ?>" required><br>

                   
                    <label for="status-available">
                        <input type="radio" id="status-available" value="1" name="status" <?php echo ($food['Status'] == '1') ? 'checked' : ''; ?> required>
                        Available
                    </label>
                    <label for="status-unavailable">
                        <input type="radio" id="status-unavailable" value="0" name="status" <?php echo ($food['Status'] == '0') ? 'checked' : ''; ?>>
                        Unavailable
                    </label>


                    <button type="submit" class="login update">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/dropdown-dash.js"></script>
</body>

</html>