<?php
session_start();
include "../pages/database/connection.php";

$error = ""; // Initialize an error variable to display messages in HTML

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-id-value'], $_POST['login-id-pw'])) {
    $std_roll = $_POST['login-id-value'];
    $std_pw = $_POST['login-id-pw'];

    // Prepared statement to prevent SQL injection
    $sql = "SELECT * FROM student_info WHERE Rollnum = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param('s', $std_roll);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($std_pw, $user['Password'])) {
            $_SESSION['roll'] = $std_roll; // Store roll number in session
            $_SESSION['name'] = $user['Name']; 
            header("Location: menu.php");   // Redirect to menu on success
            exit();
        } else {
            $error = "Invalid username or password."; // Wrong password
        }
    } else {
        $error = "Invalid username or password."; // No such user
    }

    $stmt->close();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = "Please fill out the login form correctly.";
    }
}

// Close the connection if it was established
if ($conn) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body class="main-body login">
    <div class="login-wrapper">
        <div class="login-text">
            <h2>Login</h2>
        </div>
        <form action="" method="POST">
            <div class="login-info-container">
                <div class="login-img">
                    <img src="../assets/image/icon/eat.png">
                </div>
                <div class="login-info">
                    <?php if ($error): ?> <!-- Display error message if set -->
                        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <div class="login-id">
                        <input type="text" placeholder="Roll number" id="login-id-value" name="login-id-value" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13t3.25.388t3.15 1.162q.725.375 1.163 1.088T20 17.2V20z" />
                        </svg>
                    </div>
                    <div class="login-pw">
                        <input type="password" placeholder="Password" id="login-id-pw" name="login-id-pw" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 17a2 2 0 0 0 2-2a2 2 0 0 0-2-2a2 2 0 0 0-2 2a2 2 0 0 0 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                        </svg>
                    </div>
                    <div class="login-btn">
                        <button type="submit" class="btn login-btn">Login</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="create-acc-container">
            <span id="create-acc">Not registered? <a href="register.php">Create account</a></span>
        </div>
    </div>
</body>

</html>
