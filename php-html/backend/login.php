<?php
session_start(); // Start the session at the beginning

require './../config/db.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize user input to prevent SQL injection
    $email = mysqli_real_escape_string($db_connect, $email);

    // Query to check if the user exists
    $user = mysqli_query($db_connect, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($user) > 0) {
        $data = mysqli_fetch_assoc($user);

        // Verify the password
        if (password_verify($password, $data['password'])) {
            // Authorize and store session variables
            $_SESSION['name'] = $data['name'];
            $_SESSION['role'] = $data['role'];

            // Redirect based on role
            if ($_SESSION['role'] == 'admin') {
                header('Location: ./../admin.php');
            } else {
                header('Location: ./../profile.php');
            }
            exit(); // Stop further execution after header redirect

        } else {
            echo "Password is incorrect.";
            exit();
        }
    } else {
        echo "Email or password is incorrect.";
        exit();
    }
}

// Close the database connection
mysqli_close($db_connect);
?>