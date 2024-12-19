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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Plans</title>
    <link rel="stylesheet" href="styles/diet_plan.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="diet-plan-container">
        <section class="plans-section">
            <h2>The Right Diet Plan for Your Health</h2>
            <p>Choose the perfect plan for your fitness needs. Flexible and easy to follow.</p>

            <div class="plan-cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="plan-card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Diet Plan Image" class="plan-image">
                    <h3><?php echo htmlspecialchars($row['meal_plan']); ?></h3>
                    <button onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">View Details</button>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>

    <!-- Modal for showing full details -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle"></h3>
            <img id="modalImage" src="" alt="Diet Plan Image" class="modal-image">
            <p id="modalDescription"></p>
            <ul id="modalNotes"></ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/diet_plan.js"></script>
</body>
</html>
