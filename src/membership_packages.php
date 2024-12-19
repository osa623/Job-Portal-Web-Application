<?php
// Include navbar
include("navbar.php");
include("php/db.php");

// CREATE Membership Package
if (isset($_POST['add_package'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $sql = "INSERT INTO membership_packages (name, price, duration) VALUES (:name, :price, :duration)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name, 'price' => $price, 'duration' => $duration]);

    $message = "Package added successfully!";
}

// READ Membership Packages
$sql = "SELECT * FROM membership_packages";
$stmt = $conn->query($sql);
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DELETE Membership Package
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM membership_packages WHERE package_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    header('Location: membership_packages.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Membership Packages</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
    <script src="js/scripts.js" defer></script> <!-- External JavaScript -->
</head>
<body>
    <div class="container">
        <h1>Manage Membership Packages</h1>

        <?php if (isset($message)) { ?>
            <div class="feedback"><?php echo $message; ?></div>
        <?php } ?>

        <div class="contact-form">
            <h2>Add a New Package</h2>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Package Name" required>
                <input type="number" name="price" placeholder="Price" required>
                <input type="number" name="duration" placeholder="Duration (in months)" required>
                <button type="submit" name="add_package">Add Package</button>
            </form>
        </div>

        <h2>Existing Packages</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $package) { ?>
                    <tr>
                        <td><?php echo $package['package_id']; ?></td>
                        <td><?php echo $package['name']; ?></td>
                        <td><?php echo $package['price']; ?></td>
                        <td><?php echo $package['duration']; ?> months</td>
                        <td>
                            <a href="edit_package.php?edit_id=<?php echo $package['package_id']; ?>" class="action-btn edit">Edit</a>
                            <a href="membership_packages.php?delete_id=<?php echo $package['package_id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this package?');">Delete</a>
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
