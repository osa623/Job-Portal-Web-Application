<?php
// Start the session
session_start();

// Include database connection
require('db.php');

// Initialize success and error messages
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $meal_plan = filter_input(INPUT_POST, 'meal_plan', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_path = 'uploads/' . $image;

    // Ensure the image was uploaded successfully
    if (move_uploaded_file($image_temp, $image_path)) {
        // Insert into database (plan_id is auto-incremented)
        $query = "INSERT INTO diet_plans (meal_plan, description, notes, image) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssss", $meal_plan, $description, $notes, $image_path);

        if ($stmt->execute()) {
            $success = "Diet plan created successfully.";
        } else {
            $error = "Failed to create diet plan. Please try again.";
        }
    } else {
        $error = "Failed to upload the image. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Diet Plan</title>
    <link rel="stylesheet" href="styles/addDiet.css"> <!-- External CSS for styling -->
    <script>
        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }

        function validateForm() {
            const mealPlan = document.getElementById('meal_plan');
            const notes = document.getElementById('notes');
            if (mealPlan.value.trim() === '') {
                alert("Meal Plan cannot be empty!");
                mealPlan.focus();
                return false;
            }
            if (notes.value.length > 200) {
                alert("Notes should not exceed 200 characters.");
                notes.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="form-container">
        <h2>Add New Diet Plan</h2>

        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="meal_plan">Meal Plan:</label>
                <textarea id="meal_plan" name="meal_plan" required></textarea>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Optional: Provide additional details..."></textarea>
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" placeholder="Optional: Any specific instructions..."></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <div style="position: relative; display: inline-block;">
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)" 
                           style="display: none;">
                    <button type="button" 
                            style="background-color: #4ECDC4; color: white; border: none; padding: 10px 20px; 
                                   cursor: pointer; border-radius: 5px;">
                        Choose Image
                    </button>
                    <span id="file-name" style="margin-left: 10px; font-style: italic; color: #5C646C;">No file chosen</span>
                </div>
                <img id="image-preview" src="#" alt="Image Preview" style="display:none; margin-top: 10px; max-width: 100%; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <button type="submit" class="btn-submit" 
                    style="background-color: #4ECDC4; color: white; border: none; padding: 10px 20px; 
                           cursor: pointer; border-radius: 5px;">Create Diet Plan</button>
        </form>
    </div>

    <script>
        const imageInput = document.getElementById('image');
        const fileNameSpan = document.getElementById('file-name');

        document.querySelector('button[type="button"]').addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            const fileName = imageInput.files.length > 0 ? imageInput.files[0].name : 'No file chosen';
            fileNameSpan.textContent = fileName;
        });
    </script>
</body>
</html>
