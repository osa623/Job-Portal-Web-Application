<?php
// Start the session
session_start();

// Include your database connection file
require('db.php'); // Ensure db.php sets up a MySQLi connection

// Initialize variables for success and error messages
$success = '';
$error = '';

// Handle coach addition logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = filter_input(INPUT_POST, 'coach_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'coach_email', FILTER_SANITIZE_EMAIL);
    $phone_number = filter_input(INPUT_POST, 'coach_phone', FILTER_SANITIZE_STRING);
    $specialization = filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_STRING);
    $experience_years = filter_input(INPUT_POST, 'experience_years', FILTER_VALIDATE_INT);
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);
    $availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_STRING);

    // Check if email already exists
    $checkQuery = "SELECT * FROM coaches WHERE email = ?";
    $stmt = $con->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        // Insert new coach into the database
        $query = "INSERT INTO coaches (name, email, specialization, experience_years, bio, phone_number, availability) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssiiss", $name, $email, $specialization, $experience_years, $bio, $phone_number, $availability);

        if ($stmt->execute()) {
            // Coach added successfully
            $success = "Coach added successfully.";
        } else {
            $error = "Failed to add coach. Please try again.";
        }
    }
}
?>

<?php
// Include sidebar
include("sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coach - Fitness Master</title>
    <link rel="stylesheet" href="styles/addCoach.css"> <!-- External CSS for Add Coach Form -->
    <script>
        function validateForm() {
            const name = document.getElementById('coach_name').value;
            const email = document.getElementById('coach_email').value;
            const phone = document.getElementById('coach_phone').value;
            const specialization = document.getElementById('specialization').value;
            const experienceYears = document.getElementById('experience_years').value;

            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach((elem) => {
                elem.innerText = '';
            });

            // Validate Name
            if (name.trim() === '') {
                document.getElementById('name-error').innerText = 'Name is required.';
                isValid = false;
            }

            // Validate Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('email-error').innerText = 'Invalid email format.';
                isValid = false;
            }

            // Validate Phone
            if (phone.trim() === '') {
                document.getElementById('phone-error').innerText = 'Phone number is required.';
                isValid = false;
            }

            // Validate Specialization
            if (specialization.trim() === '') {
                document.getElementById('specialization-error').innerText = 'Specialization is required.';
                isValid = false;
            }

            // Validate Experience Years
            if (isNaN(experienceYears) || experienceYears <= 0) {
                document.getElementById('experience_years-error').innerText = 'Enter a valid number of years.';
                isValid = false;
            }

            return isValid; // Return the overall validity
        }
    </script>
</head>
<body>
    <div class="add-coach-container">
        <h2>Add New Coach</h2>

        <!-- Display success or error message -->
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="add-coach-form" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="coach_name">Full Name:</label>
                <input type="text" id="coach_name" name="coach_name" placeholder="Enter full name" required>
                <span class="error-message" id="name-error"></span>
            </div>
            <div class="form-group">
                <label for="coach_email">Email:</label>
                <input type="email" id="coach_email" name="coach_email" placeholder="Enter email" required>
                <span class="error-message" id="email-error"></span>
            </div>
            <div class="form-group">
                <label for="coach_phone">Phone:</label>
                <input type="text" id="coach_phone" name="coach_phone" placeholder="Enter phone number" required>
                <span class="error-message" id="phone-error"></span>
            </div>
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" placeholder="Enter specialization" required>
                <span class="error-message" id="specialization-error"></span>
            </div>
            <div class="form-group">
                <label for="experience_years">Experience Years:</label>
                <input type="number" id="experience_years" name="experience_years" placeholder="Enter years of experience" required>
                <span class="error-message" id="experience_years-error"></span>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" placeholder="Enter brief bio"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Add Coach</button>
            </div>
        </form>
    </div>
</body>
</html>
