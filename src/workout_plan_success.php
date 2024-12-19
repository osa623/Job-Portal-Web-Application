<?php
// Include the navbar and database connection if necessary
include("navbar.php");
session_start();

// Check if the feedback message is set
if (isset($_SESSION['feedbackMessage'])) {
    $feedbackMessage = $_SESSION['feedbackMessage'];
    unset($_SESSION['feedbackMessage']); // Clear the message after displaying it
} else {
    // Redirect to the view schedule page if no message
    header("Location: view_schedule.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Plan Success</title>
    <link rel="stylesheet" href="./styles/feedbackstyle.css"> <!-- External CSS -->
    <script>
        // Redirect to view_schedule.php after 3 seconds
        setTimeout(function() {
            window.location.href = "view_schedule.php";
        }, 3000);
    </script>
</head>
<body>
    <div class="container">
        <h1>Success!</h1>
        <div class="feedback-message"><?php echo htmlspecialchars($feedbackMessage); ?></div>
        <p>You will be redirected to your schedule plans shortly.</p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
