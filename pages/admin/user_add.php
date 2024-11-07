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
    if (isset($_POST['reg-std-name-value'], $_POST['reg-std-id-value'], $_POST['reg-std-faculty-value'], $_POST['reg-std-email-value'], $_POST['reg-std-phone-value'], $_POST['reg-std-pw-value'], $_POST['reg-std-r-pw-value'])) {

        // Retrieve and sanitize form inputs
        $name = mysqli_real_escape_string($conn, $_POST['reg-std-name-value']);
        $roll_num = mysqli_real_escape_string($conn, $_POST['reg-std-id-value']);
        $faculty = mysqli_real_escape_string($conn, $_POST['reg-std-faculty-value']);
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

            // Insert data into the student_info table
            $sql = "INSERT INTO student_info (Name, Rollnum, Faculty, Email, Phone, Password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $roll_num, $faculty, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $message = "User added successfully!";
            } else {
                $message = "Error: " . $stmt->error;
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
                                <a href="#">View </a>
                                <a href="#">Add Menu</a>
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
            <div class="reg-wrapper dash">
                <div class="reg-headline">
                    <h2>Add user</h2>
                </div>
                <div class="reg-img ">
                    <img src="../../assets/image/icon/reg.png">
                </div>
                <div class="reg-info-container">
                
                    <?php if ($message): ?>
                        <p style="    margin-bottom: 8px;color: darkblue;font-size: 20px;margin-top: -22px;"><?php echo $message; ?></p>
                    <?php endif; ?>
                    <form action="../admin/user_add.php" method="POST">
                        <div class="std-teach name">
                            <label for="name">Name:</label>
                            <input type="text" id="reg-std-name-value" name="reg-std-name-value" required>
                        </div>
                        <div class="std-teach roll">
                            <label for="roll-num">Roll-num:</label>
                            <input type="text" id="reg-std-id-value" name="reg-std-id-value" required>
                        </div>
                        <div class="std-teach faculty">
                            <label for="faculty">Faculty:</label>
                            <select id="reg-std-faculty-value" name="reg-std-faculty-value" required>
                                <option value="">Select faculty</option>
                                <option value="BCA">BCA</option>
                                <option value="BSc.CSIT">BSc.CSIT</option>
                                <option value="BSW">BSW</option>
                                <option value="BBA">BBA</option>
                                <option value="BBS">BBS</option>
                                <option value="MBA">MBA</option>
                            </select>
                        </div>
                        <div class="std-teach email">
                            <label for="email">Email:</label>
                            <input type="text" id="reg-std-email-value" name="reg-std-email-value" required>
                        </div>
                        <div class="std-teach phone">
                            <label for="phone">Phone:</label>
                            <input type="number" id="reg-std-phone-value" name="reg-std-phone-value" required>
                        </div>
                        <div class="std-teach pw">
                            <label for="email">Password:</label>
                            <input type="password" id="reg-std-pw-value" name="reg-std-pw-value" required>
                        </div>
                        <div class="std-teach repeat-pw">
                            <label for="repeat-pw">Repeat Password:</label>
                            <input type="password" id="reg-std-r-pw-value" name="reg-std-r-pw-value" required>
                        </div>
                        <div class="register-btn">
                            <button type="submit" class="btn reg-btn">Add</button>
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