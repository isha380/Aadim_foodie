<?php
session_start();
include "../database/connection.php";

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$adminName = $_SESSION['admin'];

// Initialize the $stud variable to avoid "undefined variable" warnings
$stud = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute query to fetch student data
    $stmt = $conn->prepare("SELECT * FROM student_info WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stud = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prepare and execute update query
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $faculty = $_POST['faculty'];

            $updateStmt = $conn->prepare("UPDATE student_info SET Name = ?, Phone = ?, Email = ?, Faculty = ? WHERE Id = ?");
            $updateStmt->bind_param("ssssi", $name, $phone, $email, $faculty, $id); // Correct binding types

            if ($updateStmt->execute()) {
                $_SESSION['update_success'] = "Updated Successfully!";
                header("Location: update_std.php?id=" . $id); // Redirect to the same page with the updated ID
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $updateStmt->close();
        }
    } else {
        echo "Invalid student ID.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
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
                                <a href="#">View </a>
                                <a href="#">Add Menu</a>
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
                unset($_SESSION['update_success']); // Remove the message after displaying it
            }
            ?>
            <div class="login-wrapper std update">
                <form method="POST" action="update_std.php?id=<?php echo $stud['Id'] ?? ''; ?>">
                    <div class="id-num staff">
                        <span> ID:<?php echo htmlspecialchars($stud['Id']) ?></span> <br>
                    </div>
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($stud['Name'] ?? ''); ?>" required><br>

                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($stud['Phone'] ?? ''); ?>" required><br>

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($stud['Email'] ?? ''); ?>" required><br>

                    <label>Faculty:</label>
                    <input type="text" name="faculty" value="<?php echo htmlspecialchars($stud['Faculty'] ?? ''); ?>" required><br>

                    <button type="submit" class="login update">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/dropdown-dash.js"></script>
</body>

</html>