<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Football Kits Nepal - Your premier destination for authentic football jerseys and kits in Nepal.">
    <title>About Us - Football Kits Nepal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/aboutus.css">
</head>
<body>
    <?php include '../components/nav.php'; ?>

    <section class="about-page">
        <div class="about-container">
            <h2>Our Story</h2>
            <p>
                At <strong>Football Kits Nepal</strong>, we're more than just a store – we're a community of passionate football fans dedicated to bringing the world's most iconic football jerseys to Nepal. Founded in 2020, we've grown from a small startup to Nepal's leading destination for authentic football kits.
            </p>
            <p>
                Our journey began with a simple idea: to make it easy for Nepali football fans to show their support for their favorite teams with high-quality, officially licensed jerseys. Today, we're proud to serve thousands of customers across the country with an ever-expanding collection of kits from top leagues and national teams worldwide.
            </p>
            <p>
                What sets us apart is our unwavering commitment to authenticity and customer satisfaction. Every kit we sell is 100% genuine, sourced directly from official manufacturers and distributors. We understand that when you wear your team's colors, you're not just wearing a shirt – you're making a statement.
            </p>


            
    </section>

    <footer class="about-footer">
        <div class="about-container">
            <p>&copy; <?php echo date('Y'); ?> Football Kits Nepal. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
