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
    $package_name = filter_input(INPUT_POST, 'package_name', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $features = filter_input(INPUT_POST, 'features', FILTER_SANITIZE_STRING);

    // Insert into database
    $query = "INSERT INTO membership_packages (package_name, price, duration, description, features) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sdis", $package_name, $price, $duration, $description, $features);

    if ($stmt->execute()) {
        $success = "Package created successfully.";
    } else {
        $error = "Failed to create package. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package</title>
    <link rel="stylesheet" href="styles/addpackages.css">
</head>
<body>
<?php include('sidebar.php'); ?>

<div class="form-container">
    <h2>Add New Membership Package</h2>

    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="package_name">Package Name:</label>
            <input type="text" id="package_name" name="package_name" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (in days):</label>
            <input type="number" id="duration" name="duration" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="features">Features:</label>
            <textarea id="features" name="features"></textarea>
        </div>
        <button type="submit" class="btn-submit">Create Package</button>
    </form>
</div>


</body>
</html>
