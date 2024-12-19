<?php

ob_start(); 
session_start();
// Include navbar and database connection
include("navbar.php");
include("db.php"); // Ensure db.php sets up a MySQLi connection

// CREATE Workout Plan
if (isset($_POST['add_schedule_plan'])) {
    $user_id = $_POST['user_id']; // Assumed to come from session or similar context
    $package_id = $_POST['package_id']; // Membership package selected
    $coach_id = $_POST['coach_id']; // Coach selected for workout plan
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $session_count = $_POST['session_count'];
    $notes = $_POST['notes'];

    // Validate input data
    if (empty($user_id) || empty($package_id) || empty($coach_id) || empty($start_date) || empty($end_date)) {
        $feedbackMessage = "All fields are required!";
    } else {
        // Prepare the SQL insert statement
        // Note: Ensure that you properly escape your inputs to prevent SQL Injection
        $sql = "INSERT INTO workout_plans (user_id, package_id, coach_id, start_date, end_date, status, session_count, notes) 
                VALUES ('$user_id', '$package_id', '$coach_id', '$start_date', '$end_date', '$status', '$session_count', '$notes')";
        
        if ($con->query($sql) === TRUE) {
            // Set the feedback message in the session
            $_SESSION['feedbackMessage'] = "Workout plan added successfully!";

            // Redirect to the view schedule page
            header("Location: view_schedule.php");
            exit(); // Terminate script after redirection
        } else {
            $feedbackMessage = "Error: " . $con->error; // Use $con->error for error reporting
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Add Schedule Plan</title>
    <link rel="stylesheet" href="./styles/feedbackstyle.css"> <!-- External CSS -->
</head>
<body>
    <div class="container">
        <h1>Add Schedule Plan</h1>

        <?php if (isset($feedbackMessage)) { ?>
            <div class="feedback-message"><?php echo $feedbackMessage; ?></div>
        <?php } ?>

        <div class="contact-form">
            <form method="POST" action="add_schedule_plan.php">
                <div class="form-group">
                    <label for="coach_id">Select Coach:</label>
                    <select name="coach_id" required class="form-select" id="coach_id">
                        <option value="">Choose a coach</option>
                        <?php
                        // Fetch coaches from the database
                        $sql = "SELECT coach_id, name FROM coaches";
                        $result = $con->query($sql);

                        while ($coach = $result->fetch_assoc()) {
                            echo "<option value='{$coach['coach_id']}'>{$coach['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="package_id">Select Package:</label>
                    <select name="package_id" required class="form-select" id="package_id">
                        <option value="">Choose a package</option>
                        <?php
                        // Fetch membership packages from the database
                        $sql = "SELECT package_id, package_name FROM membership_packages";
                        $result = $con->query($sql);

                        while ($package = $result->fetch_assoc()) {
                            echo "<option value='{$package['package_id']}'>{$package['package_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" required class="form-input" id="start_date">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" required class="form-input" id="end_date">
                </div>

                <div class="form-group">
                    <label for="session_count">Session Count:</label>
                    <input type="number" name="session_count" class="form-input" id="session_count" min="1" value="1">
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" required class="form-select" id="status">
                        <option value="Active">Active</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea name="notes" class="form-textarea" id="notes"></textarea>
                </div>

                <div>
                    <input type="hidden" name="user_id" value="1"> <!-- Example user_id -->
                    <button type="submit" name="add_schedule_plan" class="submit-button">Submit Schedule Plan</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
