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

$email = $_SESSION['email'];

// Fetch user information
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login after logout
    exit();
}

// Handle update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $gender = mysqli_real_escape_string($con, trim($_POST['gender']));
    $phone_number = mysqli_real_escape_string($con, trim($_POST['phone_number']));
    $new_password = mysqli_real_escape_string($con, trim($_POST['new_password']));
    
    // Prepare the update SQL statement
    $update_sql = "UPDATE users SET name=?, gender=?, phone_number=?" . 
                  ($new_password ? ", password=?" : "") . 
                  " WHERE email=?";
    
    $stmt = $con->prepare($update_sql);
    if ($new_password) {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $name, $gender, $phone_number, $new_password, $email);
    } else {
        $stmt->bind_param("ssss", $name, $gender, $phone_number, $email);
    }
    
    if ($stmt->execute()) {
        $message = "Profile updated successfully.";
        // Refresh user data after update
        $user['name'] = $name;
        $user['gender'] = $gender;
        $user['phone_number'] = $phone_number;
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
}

// Handle account deletion
if (isset($_POST['delete'])) {
    $delete_sql = "DELETE FROM users WHERE email=?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        session_destroy(); // Destroy the session
        header("Location: login.php"); // Redirect to login after deletion
        exit();
    } else {
        $error = "Error deleting account: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="styles/profile.css">
    <script>
        function toggleEditForm() {
            const editForm = document.getElementById('editForm');
            editForm.classList.toggle('visible');
        }

        function confirmAction(message, form) {
            if (confirm(message)) {
                form.submit();
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

<?php include('navbar.php'); ?>

<div class="profile-container">
    <div class="sidebar">
        <div class="profile-info">
            <img src="images/profile-icon.jpg" alt="User Profile" class="profile-image">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
            <p>Membership State</p>
        </div>
        <ul class="nav-links">
            <li><a href="#">Get a coach</a></li>
            <li><a href="#">My plans</a></li>
            <li><a href="#">Membership State</a></li>
            <li><a href="#">My bookmarks</a></li>
        </ul>
        <button class="add-post-btn">Add Post</button>
    </div>

    <div class="main-content">
        <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        
        <div class="card">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>

            <div class="form-actions">
                <button class="button update" onclick="toggleEditForm()">Edit Profile</button>
                <form method="post" style="display: inline;">
                    <button type="button" class="button delete" onclick="confirmAction('Are you sure you want to logout?', this.form)">Logout</button>
                    <input type="hidden" name="logout">
                </form>
            </div>

            <?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </div>

        <div class="stats-section">
            <div class="water-steps">
                <div class="water-consumed">
                    <h4>Water Consumed</h4>
                    <p>0 L</p>
                </div>
                <div class="steps-walked">
                    <h4>Steps Walked</h4>
                    <p>0 Km</p>
                </div>
            </div>
            <div class="calories-burned">
                <h4>Calories Burned</h4>
                <p>0 Kcal</p>
            </div>
            <div class="macros">
                <h4>Macros</h4>
                <label>Carbs <input type="range" value="0"></label>
                <label>Protein <input type="range" value="0"></label>
                <label>Fat <input type="range" value="0"></label>
            </div>
        </div>

        <div class="appointments-goals">
            <div class="appointments">
                <h4>My Appointments</h4>
                <p>xxxxxx</p>
                <p>xxxxxx</p>
            </div>
            <div class="goals">
                <h4>My Goals</h4>
                <p>85kg > 60kg</p>
                <p>Estimated time: xx weeks</p>
                <button>Add Progress</button>
            </div>
        </div>
    </div>

    <div class="people-follow">
        <h4>People to Follow</h4>
        <ul>
            <li>xxxxxxx <button>Follow</button></li>
            <li>xxxxxxx <button>Follow</button></li>
            <li>xxxxxxx <button>Follow</button></li>
        </ul>
    </div>
</div>

<!-- Edit Profile Form Popup -->
<div id="editForm" class="popup-card">
    <h2>Edit Profile</h2>
    <form method="post" onsubmit="return validateForm();">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Leave blank if no change">
        </div>
        <div class="form-actions">
            <button type="submit" name="update" class="button submit">Update</button>
            <button type="button" class="button cancel" onclick="toggleEditForm()">Cancel</button>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>

</body>
</html>
