<?php
// Start session
session_start();

// Include database connection
require('db.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch diet plans from the database
$query = "SELECT * FROM diet_plans";
$result = $con->query($query);

// Initialize messages
$message = '';
$error = '';

// Handle deletion of a diet plan
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM diet_plans WHERE diet_id=?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Diet plan deleted successfully.";
    } else {
        $error = "Error deleting diet plan: " . $stmt->error;
    }
    header("Location: manageDiet_plans.php");
    exit();
}

// Handle edit logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_diet'])) {
    $diet_id = (int)$_POST['diet_id'];
    $meal_plan = mysqli_real_escape_string($con, trim($_POST['meal_plan']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));
    $notes = mysqli_real_escape_string($con, trim($_POST['notes']));

    // Initialize variable to hold image path
    $image_path = '';

    // Query to get the existing image path from the database
    $existing_query = "SELECT image FROM diet_plans WHERE diet_id=?";
    $existing_stmt = $con->prepare($existing_query);
    $existing_stmt->bind_param("i", $diet_id);
    $existing_stmt->execute();
    $existing_stmt->bind_result($existing_image);
    $existing_stmt->fetch();
    $existing_stmt->close();

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . $image;
        move_uploaded_file($image_temp, $image_path);
    } else {
        // If no new image is uploaded, use the existing image path
        $image_path = $existing_image;
    }

    // Prepare the update SQL statement
    $update_sql = "UPDATE diet_plans SET meal_plan=?, description=?, notes=?, image=? WHERE diet_id=?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssssi", $meal_plan, $description, $notes, $image_path, $diet_id);

    if ($stmt->execute()) {
        $message = "Diet plan updated successfully.";
    } else {
        $error = "Error updating diet plan: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Diet Plans</title>
    <link rel="stylesheet" href="styles/manageDiet.css">
    <script>
        function toggleEditForm(plan) {
            const form = document.getElementById('editForm');
            form.classList.toggle('visible');

            if (plan) {
                form.querySelector('#diet_id').value = plan.diet_id;
                form.querySelector('#meal_plan').value = plan.meal_plan;
                form.querySelector('#description').value = plan.description;
                form.querySelector('#notes').value = plan.notes;
            } else {
                form.reset();
                form.classList.remove('visible');
            }
        }

        function confirmDelete(dietId) {
            if (confirm("Are you sure you want to delete this diet plan?")) {
                window.location.href = "manageDiet_plans.php?delete_id=" + dietId;
            }
        }

        function validateForm() {
            const mealPlan = document.getElementById('meal_plan').value.trim();
            if (!mealPlan) {
                alert("Meal plan cannot be empty.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php include('sidebar.php'); ?>

<div class="container">
    <h2>Manage Diet Plans</h2>
    <?php if ($message): ?>
        <div id="successMessage" class="notification success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div id="errorMessage" class="notification error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="plan-card">
        <div class="meal-plan"><?php echo htmlspecialchars($row['meal_plan']); ?></div>
        <div class="description"><?php echo htmlspecialchars($row['description']); ?></div>
        <div class="notes"><?php echo htmlspecialchars($row['notes']); ?></div>
        <div class="image"><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Diet Image"></div>
        <div class="action-buttons">
            <button class="btn-edit" onclick="toggleEditForm(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
            <button class="btn-delete" onclick="confirmDelete(<?php echo $row['diet_id']; ?>)">Delete</button>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Edit Diet Plan Form Popup -->
<div id="editForm" class="popup-card">
    <h2>Edit Diet Plan</h2>
    <form method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
        <input type="hidden" id="diet_id" name="diet_id">
        <div class="form-group">
            <label for="meal_plan">Meal Plan:</label>
            <textarea id="meal_plan" name="meal_plan" required></textarea>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image">
        </div>
        <div class="form-actions">
            <button type="submit" name="edit_diet" class="button submit">Update</button>
            <button type="button" class="button cancel" onclick="toggleEditForm()">Cancel</button>
        </div>
    </form>
</div>

<script>
    // Hide the success message after 5 seconds
    setTimeout(function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); // 5000 milliseconds = 5 seconds

    // Hide the error message after 5 seconds
    setTimeout(function() {
        const errorMessage = document.getElementById('errorMessage');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 2000); // 5000 milliseconds = 5 seconds
</script>
</body>
</html>
