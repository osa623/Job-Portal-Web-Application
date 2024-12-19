<?php
// Start the session
session_start();
// Include navbar and database connection
include("navbar.php");
include("db.php"); // Make sure db.php sets up a MySQLi connection

// CREATE Feedback
if (isset($_POST['add_feedback'])) {
    $user_id = $_POST['user_id']; // Assumed to come from session or similar context
    $coach_id = $_POST['coach_id']; // Coach selected for feedback
    $rating = $_POST['rating']; // User's rating
    $system_rating = $_POST['system_rating']; // Rating for the system
    $comments = $_POST['comments'];
    $improvement_suggestions = $_POST['improvement_suggestions'];

    // Validate input data
    if (empty($user_id) || empty($coach_id) || empty($rating) || empty($system_rating) || empty($comments)) {
        $feedbackMessage = "All fields are required!";
    } else {
        // Prepare the SQL insert statement
        $sql = "INSERT INTO feedbacks (user_id, coach_id, rating, system_rating, comments, improvement_suggestions) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        // Bind parameters and execute
        $stmt->bind_param('iiisss', $user_id, $coach_id, $rating, $system_rating, $comments, $improvement_suggestions);

        if ($stmt->execute()) {
            // Set the feedback message in the session
            session_start();
            $_SESSION['feedbackMessage'] = "Feedback added successfully!";

            // Redirect to the same page to prevent resubmission
            header("Location: manage_feedbacks.php");
            exit(); // Terminate script after redirection
        } else {
            $feedbackMessage = "Error: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Add Feedback</title>
   
    <style>
        body {
    font-family: Arial, sans-serif;
    background: #fff;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 700px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.container:hover {
    transform: translateY(-5px);
}

h1 {
    text-align: center;
    color: #1c3d3f;
    margin-bottom: 20px;
}

.feedback-message {
    background: #d4edda;
    color: #1c3d3f;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    margin-bottom: 20px;
}

.contact-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 15px;
    margin-right: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-select, .form-input, .form-textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: border-color 0.3s;
}

.form-select:focus, .form-input:focus, .form-textarea:focus {
    border-color: #1c3d3f;
    outline: none;
}

.form-textarea {
    resize: vertical;
}

.submit-button {
    padding: 10px;
    background-color: #1c3d3f;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #152e30;
}

.link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #1c3d3f;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}
.star-rating {
    display: flex;
    justify-content: center;
    cursor: pointer;
}
.star {
    font-size: 50px; /* Increase the font size for larger stars */
    color: #ccc;
    transition: color 0.2s;
}
.star:hover,
.star.selected {
    color: #f1c40f; /* Yellow color for hovered and selected stars */
}
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Feedback</h1>

        <?php if (isset($feedbackMessage)) { ?>
            <div class="feedback-message"><?php echo $feedbackMessage; ?></div>
        <?php } ?>

        <div class="contact-form">
            <form method="POST" action="add_feedback.php" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="system_rating">Overall System Rating:</label>
                    <div class="star-rating" data-rating="0">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="system_rating" class="system_rating" required>
                </div>

                <div class="form-group">
                    <label for="coach_id">Select Coach:</label>
                    <select name="coach_id" required class="form-select" id="coach_id">
                        <option value="">Choose a coach</option>
                        <?php
                        // Fetch coaches from the database
                        $sql = "SELECT coach_id, name FROM coaches";
                        $result = $con->query($sql);

                        while ($coach = $result->fetch_assoc()) {
                            echo "<option value='{$coach['coach_id']}'>{$coach['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <div class="star-rating" data-rating="0">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="rating" class="rating" required>
                </div>

                <div class="form-group">
                    <label for="comments">Comments:</label>
                    <textarea name="comments" required class="form-textarea" id="comments"></textarea>
                </div>

                <div class="form-group">
                    <label for="improvement_suggestions">Improvement Suggestions:</label>
                    <textarea name="improvement_suggestions" class="form-textarea" id="improvement_suggestions"></textarea>
                </div>

                <div>
                    <input type="hidden" name="user_id" value="1"> <!-- Example user_id -->
                    <button type="submit" name="add_feedback" class="submit-button">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Star rating functionality
        const starRatings = document.querySelectorAll('.star-rating');
        starRatings.forEach(rating => {
            const stars = rating.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    stars.forEach(s => s.classList.remove('selected'));
                    star.classList.add('selected');
                    let previousSibling = star.previousElementSibling;
                    while (previousSibling) {
                        previousSibling.classList.add('selected');
                        previousSibling = previousSibling.previousElementSibling;
                    }
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach(s => s.classList.remove('selected'));
                    const currentRating = rating.getAttribute('data-rating');
                    if (currentRating) {
                        stars.forEach(s => {
                            if (s.getAttribute('data-value') <= currentRating) {
                                s.classList.add('selected');
                            }
                        });
                    }
                });

                star.addEventListener('click', () => {
                    const ratingValue = star.getAttribute('data-value');
                    const ratingInput = rating.closest('.form-group').querySelector('input');
                    rating.setAttribute('data-rating', ratingValue);
                    ratingInput.value = ratingValue;

                    stars.forEach(s => s.classList.remove('selected'));
                    star.classList.add('selected');
                    let previousSibling = star.previousElementSibling;
                    while (previousSibling) {
                        previousSibling.classList.add('selected');
                        previousSibling = previousSibling.previousElementSibling;
                    }
                });
            });
        });

        // Input validation
        function validateForm() {
            const coachId = document.getElementById("coach_id").value;
            const comments = document.getElementById("comments").value.trim();
            const systemRating = document.querySelector(".system_rating").value;
            const rating = document.querySelector(".rating").value;

            if (coachId === "") {
                alert("Please select a coach.");
                return false;
            }

            if (comments === "") {
                alert("Comments are required.");
                return false;
            }

            if (systemRating === "") {
                alert("Overall system rating is required.");
                return false;
            }

            if (rating === "") {
                alert("Rating is required.");
                return false;
            }

            return true;
        }
    </script>
     <?php include 'footer.php'; ?>
</body>
</html>
