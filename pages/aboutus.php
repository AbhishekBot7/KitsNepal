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
    <style>
        :root {
            --primary-color: #ff3c00;
            --primary-hover: #ff6b3d;
            --background: #f8f9fa;
            --text-color: #333;
            --light-text: #666;
            --white: #ffffff;
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section */
        .page-hero {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: var(--white);
            padding: 100px 0 80px;
            text-align: center;
            margin-bottom: 60px;
        }
        
        .page-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .page-hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        /* About Section */
        .about-section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--text-color);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .about-image {
            position: relative;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.03);
        }
        
        .about-text h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--text-color);
        }
        
        .about-text p {
            margin-bottom: 20px;
            color: var(--light-text);
            line-height: 1.8;
        }
        
        /* Team Section */
        .team-section {
            padding: 80px 0;
            background-color: #fff;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .team-member {
            background: #fff;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .member-image {
            height: 300px;
            overflow: hidden;
        }
        
        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .team-member:hover .member-image img {
            transform: scale(1.1);
        }
        
        .member-info {
            padding: 25px 20px;
            text-align: center;
        }
        
        .member-info h4 {
            margin: 0 0 5px;
            font-size: 1.3rem;
            color: var(--text-color);
        }
        
        .member-info p {
            margin: 0 0 15px;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #f5f5f5;
            color: var(--text-color);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-3px);
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('../img/stats-bg.jpg') no-repeat center/cover;
            color: #fff;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .stat-item {
            padding: 30px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            backdrop-filter: blur(5px);
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-10px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .stat-text {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: #fff;
            text-align: center;
        }
        
        .cta-content {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .cta-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta-content p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .btn {
            display: inline-block;
            background: #fff;
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn:hover {
            background: transparent;
            color: #fff;
            border-color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .about-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .about-image {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .page-hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .page-hero {
                padding: 80px 0 60px;
            }
            
            .page-hero h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }
    </style>
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
