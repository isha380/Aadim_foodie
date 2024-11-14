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
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are set
    if (isset($_POST['reg-std-name-value'], $_POST['reg-std-id-value'], $_POST['reg-std-role-value'], $_POST['reg-std-email-value'], $_POST['reg-std-phone-value'], $_POST['reg-std-pw-value'], $_POST['reg-std-r-pw-value'])) {

        // Retrieve and sanitize form inputs
        $name = mysqli_real_escape_string($conn, $_POST['reg-std-name-value']);
        $staff_id = mysqli_real_escape_string($conn, $_POST['reg-std-id-value']);
        $role = mysqli_real_escape_string($conn, $_POST['reg-std-role-value']);
        $email = mysqli_real_escape_string($conn, $_POST['reg-std-email-value']);
        $phone = mysqli_real_escape_string($conn, $_POST['reg-std-phone-value']);
        $password = mysqli_real_escape_string($conn, $_POST['reg-std-pw-value']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['reg-std-r-pw-value']);

        // Check if passwords match
        if ($password !== $confirm_password) {
            $message = "Passwords do not match!";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the staff_info table
            $sql = "INSERT INTO staff_info (Name, Staff_Id, Role, Password, Phone, Email) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $staff_id, $role, $hashed_password, $phone, $email);

            try {
                if ($stmt->execute()) {
                    $message = "Staff Added successfully!";
                } else {
                    $message = "Error: " . $stmt->error;
                }
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}
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
    <link rel="stylesheet" href="../../assets/css/user_add.css">

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

                    <button  class="btn reg-btn dash"><a href="../admin/admin_dash.php"  style="text-decoration: none;">Back</a>
                    </button>

                </div>
            </div>
        </div>


        <div class="dash-reg-view-wrapper">
            <div class="dash-view-txt user-add">
                <h1>DASHBOARD</h1>
            </div>
            <div class="reg-wrapper dash staff">
                <div class="reg-headline staff">
                    <h2 class="reg-headline staff">Add Staff</h2>
                </div>
                <div class="reg-img staff">
                    <img src="../../assets/image/icon/staff.png">
                </div>
                <div class="reg-info-container staff">
                
                <?php if ($message): ?>
                <p style="color: darkblue; font-size: 20px;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
                    <form action="../admin/staff_add.php" method="POST">
                        <div class="std-teach name staff">
                            <label for="name">Name:</label>
                            <input type="text" id="reg-std-name-value staff" name="reg-std-name-value" required>
                        </div>
                        <div class="std-teach id staff">
                            <label for="roll-num">Staff-Id:</label>
                            <input type="text" id="reg-std-id-value staff" name="reg-std-id-value" required>
                        </div>
                        <div class="std-teach role staff">
                        <label for="roll-num">Role:</label>
                        <input type="text" id="reg-std-role-value staff" name="reg-std-role-value" required>
                        </div>
                        <div class="std-teach email staff">
                            <label for="email">Email:</label>
                            <input type="text" id="reg-std-email-value staff" name="reg-std-email-value" required>
                        </div>
                        <div class="std-teach phone staff">
                            <label for="phone">Phone:</label>
                            <input type="number" id="reg-std-phone-value staff" name="reg-std-phone-value" required>
                        </div>
                        <div class="std-teach pw staff">
                            <label for="email">Password:</label>
                            <input type="password" id="reg-std-pw-value staff" name="reg-std-pw-value" required>
                        </div>
                        <div class="std-teach repeat-pw staff">
                            <label for="repeat-pw">Repeat Password:</label>
                            <input type="password" id="reg-std-r-pw-value staff" name="reg-std-r-pw-value" required>
                        </div>
                        <div class="register-btn staff">
                            <button type="submit" class="btn reg-btn staff">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="../js/dropdown-dash.js">
        
        </script>




</body>

</html>