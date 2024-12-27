<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booknest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    if ($input_username == 'admin' && $input_password == 'admin123') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($input_password, $user['password'])) {
                header("Location: indexMain.php");
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "User not found!";
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
    <title>Library Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .message-response {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: red;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1 id="h1">BOOKNEST</h1>
        <h4 id="h2">LIBRARY</h4>
        <img src="207 LOGO.png" id="img1" alt="no image" />

        <form action="login.php" method="POST">

            <div class="input-group" id="g1">
                <input type="text" name="username" id="username" placeholder="Username" required>
                <span class="remove-icon"></span>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button id="login" type="submit">Log in</button>

            <?php if ($message): ?>
                <div class="message-response"><?php echo $message; ?></div>
            <?php endif; ?>

        </form>


        <p class="signup-link" id="sign1">Don't have an account? <a href="registration.php" id="sign2">Click here</a></p>
    </div>
</body>

</html>