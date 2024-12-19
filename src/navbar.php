<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include your database connection file
require('db.php'); // Ensure db.php sets up a MySQLi connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensure proper scaling on mobile -->
    <title>Fitness Master</title>
    <style>
        /* Inline styles for the profile dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1c3d3f;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .dropdown-content a {
            color: #fafafa;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #4ecdc4;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Add hover effect for the logout button */
        .dropdown-content button {
            color: #fafafa;
            padding: 12px 16px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            transition: color 0.3s, background-color 0.3s;
        }

        .dropdown-content button:hover {
            color: red; /* Keep text white */
            background-color: #4ecdc4;
        }
    </style>
</head>
<body style="margin: 0; font-family: Arial, sans-serif;">
    <header style="padding: 15px 0; background-color: #1c3d3f;">
        <div style="max-width: 1200px; margin: auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
            <div class="logo">
                <a href="#" style="text-decoration: none; color: white; font-size: 24px; font-weight: bold;">Fitness Master</a>
            </div>
            <nav>
                <ul style="list-style: none; padding: 0; display: flex; gap: 20px;">
                    <li>
                        <a href="home.php" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;">Home</a>
                    </li>
                    <li>
                        <a href="view_schedule.php" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;">Schedules</a>
                    </li>
                    <li>
                        <a href="view_schedule.php" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;">Workout Schedules</a>
                    </li>
                    <li>
                        <a href="" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;">Tips</a>
                    </li>
                    <li>
                        <a href="manage_feedbacks.php" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;">Feedbacks</a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" style="text-decoration: none; color: white; font-weight: 600; padding: 10px 15px; border-radius: 5px; position: relative;">
                            <img src="images/profile-icon.png" alt="Profile" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 5px;">
                        </a>
                        <div class="dropdown-content">
                            <a href="profile.php">User Profile</a>
                            <form method="POST" style="margin: 0;" onsubmit="return confirmLogout();">
    <button type="submit" name="logout" style="width: 100%; background: none; border: none; color: #fafafa; text-align: left; padding: 12px 16px; cursor: pointer; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='red'; this.style.color='white';" onmouseout="this.style.backgroundColor=''; this.style.color='#fafafa';">
        Logout
    </button>
</form>

                        </div>
                    </li>
                </ul>
            </nav>
            <div class="burger" style="display: none; cursor: pointer;">
                <div style="width: 25px; height: 3px; background: white; margin: 4px 0; transition: 0.4s;"></div>
                <div style="width: 25px; height: 3px; background: white; margin: 4px 0; transition: 0.4s;"></div>
                <div style="width: 25px; height: 3px; background: white; margin: 4px 0; transition: 0.4s;"></div>
            </div>
        </div>
    </header>

   

    <style>
        /* Inline styles for enhanced hover effects */
        nav ul li a:hover {
            background-color: #4ecdc4; /* Lighter teal on hover */
            color: #ffffff; /* Keep text white */
            transform: scale(1.1); /* Slightly scale up the link */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Add shadow */
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5); /* Subtle text shadow */
        }
    </style>
    <script>
    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }
</script>
</body>
</html>
