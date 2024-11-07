<?php

session_start();
include "database/connection.php";

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

            // Insert the data into the student_info table
            $sql = "INSERT INTO student_info (Name, Rollnum, Faculty, Email, Phone, Password)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $roll_num, $faculty, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful!";
                header("Location: login.php"); // Redirect to login page
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $message = "Invalid request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="main-body register">
    <div class="reg-wrapper">
        <div class="reg-headline">
            <h2>Register here</h2>
        </div>
        <div class="reg-img ">
            <img src="../assets/image/icon/reg.png">
        </div>
        <div class="reg-info-container">
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
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
                    <button type="submit" class="btn reg-btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
