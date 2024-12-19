<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .footer {
    opacity: 0; /* Start as invisible */
    transition: opacity 1s ease-in; /* Smooth transition */
}

.footer.visible {
    opacity: 1; /* Fade in */
}

    </style>
</head>
<body>
    <footer id="footer" class="footer hidden" style="background-color: #1c3d3f; color: #fafafa; padding: 2rem 1rem; display: flex; flex-wrap: wrap; justify-content: space-between;">
        <div class="footer-container" style="flex: 1; display: flex; justify-content: space-between; width: 100%;">
            <div class="footer-section" style="flex: 1; margin: 1rem;">
                <h3 style="font-size: 1.3rem; margin-bottom: 1rem; color: #4ecdc4;">Company</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">About Us</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Become a Coach</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Login & Support</a></li>
                </ul>
            </div>
            <div class="footer-section" style="flex: 1; margin: 1rem;">
                <h3 style="font-size: 1.3rem; margin-bottom: 1rem; color: #4ecdc4;">Services</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Online Coaching</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Corporate Wellness</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Weight Loss Diet Plan</a></li>
                </ul>
            </div>
            <div class="footer-section" style="flex: 1; margin: 1rem;">
                <h3 style="font-size: 1.3rem; margin-bottom: 1rem; color: #4ecdc4;">Tools</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">EMI Calculator</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Macro Calculator</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Body Fat Calculator</a></li>
                </ul>
            </div>
            <div class="footer-section" style="flex: 1; margin: 1rem;">
                <h3 style="font-size: 1.3rem; margin-bottom: 1rem; color: #4ecdc4;">Legal</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Terms & Conditions</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Privacy Policy</a></li>
                    <li style="margin: 0.5rem 0;"><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Warranty Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom" style="text-align: center; width: 100%; margin-top: 2rem; border-top: 1px solid #4ecdc4; padding-top: 1rem;">
            <p style="font-size: 0.9rem; margin: 0.5rem 0;">Copyright © All rights reserved - 2024 Designed by SLIT Matara Center, Group 08</p>
            <ul class="bottom-links" style="list-style: none; padding: 0; display: flex; justify-content: center; gap: 1.5rem;">
                <li><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Terms & Conditions</a></li>
                <li><a href="#" style="color: #fafafa; text-decoration: none; transition: color 0.3s;">Privacy Policy</a></li>
            </ul>
            <div class="social-icons" style="display: flex; justify-content: center; gap: 1rem; margin-top: 1rem;">
                <a href="#"><img src="images/facebook-icon.png" alt="Facebook" style="width: 32px; height: 32px; transition: transform 0.3s;"></a>
                <a href="#"><img src="images/twitter-icon.png" alt="Twitter" style="width: 32px; height: 32px; transition: transform 0.3s;"></a>
                <a href="#"><img src="images/instagram-icon.png" alt="Instagram" style="width: 32px; height: 32px; transition: transform 0.3s;"></a>
            </div>
        </div>
    </footer>

    <script src="js/footer.js"></script> <!-- Link to your JavaScript -->
</body>
</html>
