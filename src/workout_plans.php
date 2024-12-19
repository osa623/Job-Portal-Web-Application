<?php
// Include navbar
include("navbar.php");
include("php/db.php");

// CREATE Workout Plan
if (isset($_POST['add_plan'])) {
    $user_id = $_POST['user_id']; // Assumed to come from session or similar context
    $package_id = $_POST['package_id'];
    $coach_id = $_POST['coach_id'];
    $schedule = $_POST['schedule'];

    $sql = "INSERT INTO workout_plans (user_id, package_id, coach_id, schedule) VALUES (:user_id, :package_id, :coach_id, :schedule)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'package_id' => $package_id, 'coach_id' => $coach_id, 'schedule' => $schedule]);

    $message = "Workout Plan added successfully!";
}

// READ Workout Plans
$sql = "SELECT * FROM workout_plans";
$stmt = $conn->query($sql);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DELETE Workout Plan
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM workout_plans WHERE plan_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    header('Location: workout_plans.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Workout Plans</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
    <script src="js/scripts.js" defer></script> <!-- External JavaScript -->
</head>
<body>
    <div class="container">
        <h1>Manage Workout Plans</h1>

        <?php if (isset($message)) { ?>
            <div class="feedback"><?php echo $message; ?></div>
        <?php } ?>

        <div class="contact-form">
            <h2>Add a New Plan</h2>
            <form method="POST" action="">
                <input type="text" name="user_id" placeholder="User ID" required>
                <input type="text" name="package_id" placeholder="Package ID" required>
                <input type="text" name="coach_id" placeholder="Coach ID" required>
                <input type="text" name="schedule" placeholder="Schedule" required>
                <button type="submit" name="add_plan">Add Plan</button>
            </form>
        </div>

        <h2>Existing Plans</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Package ID</th>
                    <th>Coach ID</th>
                    <th>Schedule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan) { ?>
                    <tr>
                        <td><?php echo $plan['plan_id']; ?></td>
                        <td><?php echo $plan['user_id']; ?></td>
                        <td><?php echo $plan['package_id']; ?></td>
                        <td><?php echo $plan['coach_id']; ?></td>
                        <td><?php echo $plan['schedule']; ?></td>
                        <td>
                            <a href="edit_plan.php?edit_id=<?php echo $plan['plan_id']; ?>" class="action-btn edit">Edit</a>
                            <a href="workout_plans.php?delete_id=<?php echo $plan['plan_id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php
// Include footer
include("footer.php");
?>
</body>
</html>
