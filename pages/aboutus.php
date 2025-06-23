<?php
session_start();
// Uncomment and modify this section if you want to handle admin redirects
// if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
//     header("Location: admin.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Learn about Football Kits Nepal - Your premier destination for authentic football jerseys and kits in Nepal.">
    <title>About Us - Football Kits Nepal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/aboutus.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../components/nav.php'; ?>

    <section class="about-page">
        <div class="container">
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

            <div class="about-image">
                <img src="../img/about-banner.jpg" alt="Football Kits Nepal Team" />
            </div>

            <h3 style="margin-top: 3rem; color: var(--secondary-color);">Our Mission</h3>
            <p>
                To connect Nepali football fans with their passion for the beautiful game by providing authentic, high-quality football kits and exceptional customer service. We believe that every fan deserves to wear their team's colors with pride, no matter where they are in the world.
            </p>

            <h3 style="margin-top: 2rem; color: var(--secondary-color);">Our Values</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0;">
                <div class="value-card">
                    <i class="fas fa-check-circle" style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4>Authenticity</h4>
                    <p>Only 100% genuine products from official sources</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-truck" style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4>Fast Delivery</h4>
                    <p>Nationwide shipping with reliable delivery partners</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-headset" style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4>Customer Support</h4>
                    <p>Dedicated team ready to assist you</p>
                </div>
            </div>

            <div class="cta-section" style="text-align: center; margin: 3rem 0;">
                <h3>Join Our Football Family</h3>
                <p style="margin: 1rem 0 2rem;">Be the first to know about new arrivals and exclusive offers</p>
                <a href="../pages/register.php" class="btn-primary" style="display: inline-block; padding: 12px 30px; background: var(--primary-color); color: white; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.3s ease;">
                    Shop Now
                </a>
            </div>
        </div>
    </section>

    <footer style="background: var(--secondary-color); color: white; padding: 2rem 0; text-align: center;">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Football Kits Nepal. All rights reserved.</p>
            <div class="social-links" style="margin-top: 1rem;">
                <a href="#" style="color: white; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: white; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: white; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
