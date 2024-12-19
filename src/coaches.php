<?php
// Include navbar
include("navbar.php");
include("php/db.php");

// CREATE Coach
if (isset($_POST['add_coach'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];

    $sql = "INSERT INTO coaches (name, email, specialization) VALUES (:name, :email, :specialization)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name, 'email' => $email, 'specialization' => $specialization]);

    $message = "Coach added successfully!";
}

// READ Coaches
$sql = "SELECT * FROM coaches";
$stmt = $conn->query($sql);
$coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DELETE Coach
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM coaches WHERE coach_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    header('Location: coaches.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Coaches</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
    <script src="js/scripts.js" defer></script> <!-- External JavaScript -->
</head>
<body>
    <div class="container">
        <h1>Manage Coaches</h1>

        <?php if (isset($message)) { ?>
            <div class="feedback"><?php echo $message; ?></div>
        <?php } ?>

        <div class="contact-form">
            <h2>Add a New Coach</h2>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="specialization" placeholder="Specialization" required>
                <button type="submit" name="add_coach">Add Coach</button>
            </form>
        </div>

        <h2>Existing Coaches</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coaches as $coach) { ?>
                    <tr>
                        <td><?php echo $coach['coach_id']; ?></td>
                        <td><?php echo $coach['name']; ?></td>
                        <td><?php echo $coach['email']; ?></td>
                        <td><?php echo $coach['specialization']; ?></td>
                        <td>
                            <a href="edit_coach.php?edit_id=<?php echo $coach['coach_id']; ?>" class="action-btn edit">Edit</a>
                            <a href="coaches.php?delete_id=<?php echo $coach['coach_id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this coach?');">Delete</a>
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
