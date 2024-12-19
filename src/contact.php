<?php
// Include navbar
include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Master - Contact Us</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
    <script src="js/scripts.js" defer></script> <!-- External JavaScript -->
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <h1>Where to find us</h1>
            <div class="contact-info">
                <p><strong>Address:</strong> EH Cooray Building, No:24, 5th Floor, Matara</p>
                <p><strong>Email:</strong> support@fitnessmaster.com</p>
                <p><strong>Hotline:</strong> +94 041 222 1048</p>
            </div>
        </div>

        <div class="contact-content">
            <div class="contact-form">
                <h2>We care about you</h2>
                <form>
                    <input type="text" name="full_name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="text" name="phone" placeholder="Mobile Number" required>
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit">Send Inquiry</button>
                </form>
            </div>
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> <!-- Embedded Google Map -->
            </div>
        </div>
    </div>

<?php
// Include footer
include("footer.php");
?>
</body>
</html>
