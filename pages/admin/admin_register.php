<?php
// Start the session (if you need to store session data later)
session_start();

// Include the database connection
include '../database/connection.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $stmt = $conn->prepare("INSERT INTO admin (Name, Password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Admin registered successfully!";
    } else {
        echo "Error: Could not register admin.";
    }

    $stmt->close(); // Close the statement
    $conn->close(); // Close the database connection
}
?>

<!-- HTML Form for Admin Registration -->
<h2>Register New Admin</h2>
<form method="POST" action="admin_register.php">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>
