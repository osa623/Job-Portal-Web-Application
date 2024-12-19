<?php
// Start the session
session_start();

// Include your database connection file
require('db.php'); // Ensure db.php sets up a MySQLi connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch coaches data
$sql = "SELECT * FROM coaches";
$result = $con->query($sql);

// Handle deletion of a coach
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM coaches WHERE coach_id=?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: viewCoaches.php"); // Refresh the page after deletion
    exit();
}

// Handle edit logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_coach'])) {
    $coach_id = (int)$_POST['coach_id'];
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $specialization = mysqli_real_escape_string($con, trim($_POST['specialization']));
    $phone_number = mysqli_real_escape_string($con, trim($_POST['phone_number']));
    $experience_years = (int)$_POST['experience_years'];

    // Prepare the update SQL statement
    $update_sql = "UPDATE coaches SET name=?, email=?, specialization=?, phone_number=?, experience_years=? WHERE coach_id=?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssssii", $name, $email, $specialization, $phone_number, $experience_years, $coach_id);
    if ($stmt->execute()) {
        $message = "Coach updated successfully.";
    } else {
        $error = "Error updating coach: " . $stmt->error;
    }
}

// Include sidebar
include("sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Coaches - Fitness Master</title>
    <link rel="stylesheet" href="styles/viewCoaches.css"> <!-- External CSS for Coaches List -->
    <script>
        function toggleEditForm(coach) {
    const form = document.getElementById('editForm');
    form.classList.toggle('visible'); // Toggle visibility of the form

    if (coach) {
        // Populate the form fields with the coach data
        form.querySelector('#coach_id').value = coach.coach_id;
        form.querySelector('#name').value = coach.name;
        form.querySelector('#email').value = coach.email;
        form.querySelector('#specialization').value = coach.specialization;
        form.querySelector('#phone_number').value = coach.phone_number;
        form.querySelector('#experience_years').value = coach.experience_years;
    } else {
        form.reset(); // Clear form fields when adding a new coach
        form.classList.remove('visible'); // Hide the form if no coach is provided
    }
}


        function confirmDelete(coachId) {
            if (confirm("Are you sure you want to delete this coach?")) {
                window.location.href = "viewCoaches.php?delete_id=" + coachId;
            }
        }

        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const phoneNumber = document.getElementById('phone_number').value.trim();
            if (!name || !phoneNumber) {
                alert("Please fill in all fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="coaches-container">
        <h2>Coaches List</h2>
        <div class="search-bar">
            <input type="text" placeholder="Search coaches by name...">
        </div>
        <table class="coaches-table">
            <thead>
                <tr>
                    <th>Coach ID</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Service Type</th>
                    <th>Phone</th>
                    <th>Experience Years</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($coach = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($coach['coach_id']); ?></td>
                    <td><?php echo htmlspecialchars($coach['email']); ?></td>
                    <td><?php echo htmlspecialchars($coach['name']); ?></td>
                    <td><?php echo htmlspecialchars($coach['specialization']); ?></td>
                    <td><?php echo htmlspecialchars($coach['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($coach['experience_years']); ?></td>
                    <td>
    <button class="btn-edit" 
        onclick="toggleEditForm(<?php echo htmlspecialchars(json_encode($coach)); ?>)">Edit</button>
    <button class="btn-delete" 
        onclick="confirmDelete(<?php echo htmlspecialchars($coach['coach_id']); ?>)">Delete</button>
</td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button class="btn-add" onclick="toggleEditForm()">Add New Coach</button>
    </div>

    <!-- Edit Coach Form Popup -->
    <div id="editForm" class="popup-card">
        <h2>Edit Coach</h2>
        <form method="post" onsubmit="return validateForm();">
            <input type="hidden" id="coach_id" name="coach_id">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>
            <div class="form-group">
                <label for="experience_years">Experience Years:</label>
                <input type="number" id="experience_years" name="experience_years" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="edit_coach" class="button submit">Update</button>
                <button type="button" class="button cancel" onclick="toggleEditForm()">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
