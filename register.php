<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'booknest');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['registration'])) {
    // Capture form data
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $suffix = $conn->real_escape_string($_POST['suffix']);
    $birth_day = $conn->real_escape_string($_POST['birth_day']);
    $birth_month = $conn->real_escape_string($_POST['birth_month']);
    $birth_year = $conn->real_escape_string($_POST['birth_year']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href = 'registration.php';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email or username already exists
    $email_check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $username_check = $conn->query("SELECT * FROM users WHERE username = '$username'");

    if ($email_check->num_rows > 0) {
        echo "<script>alert('Email already exists'); window.location.href = 'registration.php';</script>";
        exit();
    }

    if ($username_check->num_rows > 0) {
        echo "<script>alert('Username already exists'); window.location.href = 'registration.php';</script>";
        exit();
    }

    // Insert data into database
    $sql = "INSERT INTO users (first_name, middle_name, surname, suffix, birth_day, birth_month, birth_year, email, username, password) 
            VALUES ('$first_name', '$middle_name', '$surname', '$suffix', '$birth_day', '$birth_month', '$birth_year', '$email', '$username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! Please login'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href = 'registration.php';</script>";
    }
}

// Close the connection
$conn->close();
?>
