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

// Fetch membership packages from the database
$query = "SELECT * FROM membership_packages";
$result = $con->query($query);

// Check if query is successful
if (!$result) {
    die("Error fetching data: " . $con->error);
}

// Handle deletion of a package
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM membership_packages WHERE package_id=?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: viewPackages.php");
    exit();
}

// Handle edit logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_package'])) {
    $package_id = (int)$_POST['package_id'];
    $package_name = mysqli_real_escape_string($con, trim($_POST['package_name']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $duration = (int)$_POST['duration'];

    // Prepare the update SQL statement
    $update_sql = "UPDATE membership_packages SET package_name=?, description=?, price=?, duration=? WHERE package_id=?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssdii", $package_name, $description, $price, $duration, $package_id);
    if ($stmt->execute()) {
        $message = "Package updated successfully.";
    } else {
        $error = "Error updating package: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Packages - Fitness Master</title>
    <link rel="stylesheet" href="styles/viewPackages.css"> <!-- External CSS for packages -->
    <script>
        function toggleEditForm(package) {
            const form = document.getElementById('editForm');
            form.classList.toggle('visible'); // Toggle visibility of the form

            if (package) {
                // Populate the form fields with the package data
                form.querySelector('#package_id').value = package.package_id;
                form.querySelector('#package_name').value = package.package_name;
                form.querySelector('#description').value = package.description;
                form.querySelector('#price').value = package.price;
                form.querySelector('#duration').value = package.duration;
            } else {
                form.reset(); // Clear form fields when adding a new package
                form.classList.remove('visible'); // Hide the form if no package is provided
            }
        }

        function confirmDelete(packageId) {
            if (confirm("Are you sure you want to delete this package?")) {
                window.location.href = "viewPackages.php?delete_id=" + packageId;
            }
        }

        function validateForm() {
            const packageName = document.getElementById('package_name').value.trim();
            const price = document.getElementById('price').value.trim();
            if (!packageName || !price) {
                alert("Please fill in all fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<?php include('sidebar.php'); ?> <!-- Sidebar -->


<div class="header-container">
    <h2>Available Membership Packages</h2>
</div>
    <div class="container">
    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="package-card">
        <div class="package-icon">
            <img src="images/gym.svg" alt="Package Icon"> <!-- Replace with actual icon path -->
        </div>
        <div class="package-name"><?php echo htmlspecialchars($row['package_name']); ?></div>
        <div class="package-details"><?php echo htmlspecialchars($row['description']); ?></div>
        <div class="package-price">$<?php echo number_format($row['price'], 2); ?></div>
        <div class="package-duration"><?php echo htmlspecialchars($row['duration']); ?> days</div>
        <div class="action-buttons">
            <button class="btn-edit" onclick="toggleEditForm(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
            <button class="btn-delete" onclick="confirmDelete(<?php echo $row['package_id']; ?>)">Delete</button>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Edit Package Form Popup -->
<div id="editForm" class="popup-card">
    <h2>Edit Package</h2>
    <form method="post" onsubmit="return validateForm();">
        <input type="hidden" id="package_id" name="package_id">
        <div class="form-group">
            <label for="package_name">Package Name:</label>
            <input type="text" id="package_name" name="package_name" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (Days):</label>
            <input type="number" id="duration" name="duration" required>
        </div>
        <div class="form-actions">
            <button type="submit" name="edit_package" class="button submit">Update</button>
            <button type="button" class="button cancel" onclick="toggleEditForm()">Cancel</button>
        </div>
    </form>
</div>

</body>
</html>
