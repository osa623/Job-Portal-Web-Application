<?php
// Start the session (if not already started)
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    header("Location: home.php"); // Redirect to home page or dashboard
    exit();
}

// Include your database connection file
require('db.php');

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use filter_input to get POST values safely
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Prepare and execute the query to check if the user exists
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Check user type
            if ($user['type'] == 1) { // Admin login
                // Admin password is stored in plain text, so verify directly
                if ($password === $user['password']) {
                    // Successful login for admin
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['type'] = $user['type']; // Store user type in session
                    header("Location: viewCoaches.php"); // Redirect to admin dashboard
                    exit();
                } else {
                    $error = "Invalid email or password";
                }
            } else { // Customer login
                // For customers, password is hashed, so use password_verify
                if (password_verify($password, $user['password'])) {
                    // Successful login for customer
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['type'] = $user['type']; // Store user type in session
                    header("Location: home.php"); // Redirect to customer home page
                    exit();
                } else {
                    $error = "Invalid email or password";
                }
            }
        } else {
            $error = "Invalid email or password";
        }

        // Close statement
        $stmt->close();
    } else {
        // Handle statement preparation failure
        echo "Failed to prepare SQL statement.";
    }
}

// Close connection only if $con is defined
if (isset($con)) {
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Login</title>
    <link rel="stylesheet" type="text/css" href="styles/loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
<header>
    <div class="logo">
        <a href="#">Fitness Master</a>
    </div>
    <nav>
        <ul>
            <li><a href="register.php">Sign Up</a></li>
            <li><a href="login.php" class="active">Sign In</a></li>
            <li><a href="bmi.php">Calculate BMI</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>

<div id="page-container">
    <div id="content-wrap">
        <div class="container">
            <form class="form" method="post" name="login">
                <h1 class="login-title">Login</h1>
                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                
                <div class="input-wrapper">
                    <input type="email" class="login-input" name="email" placeholder="Email Address" autofocus required>
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="input-wrapper">
                    <input type="password" class="login-input" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>

                <input type="submit" value="Login" name="submit" class="login-button">
                
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
