<?php
session_start();
include '../database/connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM staff_info WHERE Name = ? AND Role='cook'");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
   

    if ($user) {
       
        if (password_verify($password, $user['Password'])) {
            $_SESSION['cook'] = $user['Name'];
            $_SESSION['cook_id'] = $user['Staff_Id'];
            header('Location:cook_dash.php');
            exit(); // Ensure script stops after redirection
        } else {
            echo "Invalid name or password!";
        }
    } else {
        echo "Invalid credentials!";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef login</title>
    <link rel="stylesheet" href="../../assets/css/admin_login.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="admin-body login">
    <form method="POST">
        <div class="login-wrapper admin">
            <div class="login-text admin">
                <h2>Login</h2>
            </div>
            <div class="login-info-wrapper">
                <div class="login-img">
                    <img src="../../assets/image/icon/admin.png">
                </div>
                <div class="login-info admin">
                    <div class="admin-name">
                        <input type="text" name="username" placeholder="Username" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13t3.25.388t3.15 1.162q.725.375 1.163 1.088T20 17.2V20z" />
                        </svg>
                    </div>
                    <div class="admin-pw">
                        <input type="password" name="password" placeholder="Password" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 17a2 2 0 0 0 2-2a2 2 0 0 0-2-2a2 2 0 0 0-2 2a2 2 0 0 0 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                        </svg>
                    </div>
                    <div class="admin-btn">
                        <button type="submit" class="btn login admin">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>