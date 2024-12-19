<?php
// Start the session
session_start();

// Include your database connection file
require('db.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Home</title>
</head>
<body>

<div class="home-container">
    <section class="hero">
        <h2>How Fitness Master Helps You</h2>
        <p>At Fitness Master, we understand your goals and lifestyle before creating a personalized plan that works for YOU.</p>
    </section>
    
    <section class="plans">
        <h2>The Right Plan for Your Health</h2>
        <p>Choose the perfect plan for your fitness needs. Flexible and easy to follow.</p>
        
        <div class="plan-cards">
            <div class="plan-card card-1">
                <h3><i class="fas fa-dumbbell"></i> Fitness Coaching</h3>
                <ul>
                    <li>Internationally certified coaches</li>
                    <li>Personalized workout plans</li>
                    <li>Nutrition advice tailored to your needs</li>
                    <li>Weekly check-ins with your coach</li>
                </ul>
                <button>View Coaches</button>
            </div>

            <div class="plan-card card-2">
                <h3><i class="fas fa-apple-alt"></i> Nutrition Coaching</h3>
                <ul>
                    <li>Scientifically backed plans</li>
                    <li>Personalized based on your fitness level</li>
                    <li>Ongoing adjustments for progress</li>
                    <li>1-on-1 coaching support</li>
                </ul>
                <button>View Coaches</button>
            </div>

            <div class="plan-card card-3">
                <h3><i class="fas fa-chart-line"></i> Advanced Strategies</h3>
                <ul>
                    <li>Custom-tailored to your goals</li>
                    <li>Daily check-ins with your coach</li>
                    <li>Exclusive coaching advice</li>
                </ul>
                <button>View Coaches</button>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
