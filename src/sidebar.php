<?php


// Include your database connection file
require('db.php'); // Ensure db.php sets up a MySQLi connection

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login after logout
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar - Fitness Master</title>
    <link rel="stylesheet" href="styles/sidebar.css"> <!-- External CSS for Sidebar -->
</head>
<body>
    <div class="sidebar-container">
        <div class="sidebar-logo">
            <img src="images/logo.png" alt="Fitness Master Logo">
        </div>
        <ul class="sidebar-menu">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Packages</a>
                <ul class="dropdown-menu">
                    <li><a href="addPackages.php">Add Packages</a></li>
                    <li><a href="viewPackages.php">View Packages</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Coaches</a>
                <ul class="dropdown-menu">
                    <li><a href="addCoach.php">Add Coaches</a></li>
                    <li><a href="viewCoaches.php">View Coaches</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Diets</a>
                <ul class="dropdown-menu">
                    <li><a href="addDiet_plans.php">Add Diets</a></li>
                    <li><a href="manageDiet_plans.php">View Diets</a></li>
                </ul>
            </li>
            <li><a href="customerFeedbacks.php">Feedbacks</a></li>

            <!-- Logout button with inline styles -->
            <li>
                <form id="logoutForm" action="" method="POST" style="display: inline;">
                    <button type="submit" name="logout" 
                        style="background-color: #e76f51; color: #fff; border: none; padding: 10px 15px; 
                               border-radius: 5px; cursor: pointer; font-size: 16px; 
                               transition: background-color 0.3s ease; width: 100%; text-align: center;"
                        onclick="return confirmLogout()">Log Out</button>
                </form>
            </li>
        </ul>
    </div>

    <script>
        // JavaScript for dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault(); // Prevent default anchor click behavior
                const dropdownMenu = item.nextElementSibling;
                dropdownMenu.classList.toggle('show');
            });
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.dropdown-toggle')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Confirmation for logout
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
</body>
</html>
