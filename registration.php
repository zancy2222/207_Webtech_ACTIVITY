<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for MySQL
$password = ""; // Default password for MySQL
$dbname = "booknest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $surname = $_POST['surname'];
    $suffix = $_POST['suffix'];
    $birth_day = $_POST['birth_day'];
    $birth_month = $_POST['birth_month'];
    $birth_year = $_POST['birth_year'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, surname, suffix, birth_day, birth_month, birth_year, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiisss", $first_name, $middle_name, $surname, $suffix, $birth_day, $birth_month, $birth_year, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Registration</title>
    <link rel="stylesheet" href="registration.css">
    <style>
        .message-response {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: yellowgreen;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <br><br>
        <h1>Library Registration</h1>
        <?php if ($message): ?>
            <div class="message-response"><?php echo $message; ?></div>
        <?php endif; ?>


        <form action="registration.php" method="POST">
            <br>
            <div class="name-inputs">
                <input type="text" name="first_name" placeholder="First Name" class="input-field" required>
                <input type="text" name="middle_name" placeholder="Middle Name" class="input-field">
                <input type="text" name="surname" placeholder="Surname" class="input-field" required>
                <input type="text" name="suffix" placeholder="Suffix" class="input-field">
            </div>
            <div class="birthdate-inputs">
                <input type="number" name="birth_day" placeholder="Day" min="1" max="31" class="input-field1" required>
                <input type="number" name="birth_month" placeholder="Month" min="1" max="12" class="input-field1" required>
                <input type="number" name="birth_year" placeholder="Year" min="1900" max="2023" class="input-field1" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" class="input-field" required>
                <span class="remove-icon"></span>
            </div>
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" class="input-field" required>
                <span class="remove-icon"></span>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" class="input-field" required>
                <span class="remove-icon"></span>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="input-field" required>
                <span class="remove-icon"></span>
            </div>
            <button type="submit">Register</button>
        </form>


        <p class="signup-link" id="sign3">Already have an account? <a href="login.php" id="sign4">Click here</a></p>
    </div>
</body>

</html>