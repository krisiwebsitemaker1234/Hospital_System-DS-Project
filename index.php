<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/33/33777.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitalMS - Modern Healthcare Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: #333;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }
        
        .navbar-brand i {
            color: var(--accent-color);
            margin-right: 8px;
        }

        .nav-link {
            font-weight: 600;
            margin: 0 10px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: var(--accent-color) !important;
        }
        
        .btn-login {
            background-color: transparent;
            border: 2px solid white;
            border-radius: 25px;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: white;
            color: var(--primary-color) !important;
        }
        
        .btn-signup {
            background-color: var(--accent-color);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            transition: all 0.3s;
        }
        
        .btn-signup:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0;
            border-radius: 0 0 50% 50% / 100px;
        }
        
        .hero-text h1 {
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 25px;
        }
        
        .hero-text p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .hero-btn {
            background-color: white;
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 50px;
            padding: 15px 35px;
            font-size: 1.1rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .hero-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            background-color: var(--light-color);
        }
        
        .features-section {
            padding: 80px 0;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .feature-title {
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .testimonial-section {
            background-color: var(--light-color);
            padding: 80px 0;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
        }
        
        .testimonial-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 70px 0;
            text-align: center;
        }
        
        .cta-title {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 25px;
        }
        
        .footer-list {
            list-style: none;
            padding: 0;
        }
        
        .footer-list li {
            margin-bottom: 15px;
        }
        
        .footer-list a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-list a:hover {
            color: white;
            text-decoration: none;
        }
        
        .social-icons a {
            color: white;
            margin-right: 15px;
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--accent-color);
        }
        
        .copyright {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Staff Section Styles */
.staff-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.staff-card {
    position: relative;
    padding: 25px;
    text-align: center;
}

.staff-header {
    margin-bottom: 15px;
}

.staff-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 15px;
    background: rgba(52, 152, 219, 0.1);
    padding: 15px;
    border-radius: 50%;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.staff-details {
    line-height: 1.6;
}

.staff-details p {
    margin-bottom: 5px;
}

.scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 80px;
            height: 80px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid white; 
        }

        .scroll-to-top:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .scroll-to-top.active {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-hospital"></i> HospitalMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="scrollToSection('features')">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="scrollToSection('testimonials')">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="scrollToSection('contact')">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-login" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-text">
                    <h1>Modern Healthcare Management</h1>
                    <p>Streamline your hospital operations with our comprehensive management system. Manage patients, appointments, staff, and more with ease.</p>
                    <a href="signup.php" class="btn hero-btn">Get Started Today</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://qtxasset.com/quartz/qcloud5/media/image/Hospital%20beds.jpg?VersionId=2UcFqhix9C8xLqX.GsJlJZ.zJQ9NGMdn" alt="Hospital Management" class="img-fluid w-75 rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose HospitalMS?</h2>
                <p class="text-muted">Our platform offers powerful tools to optimize your healthcare facility</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user-md feature-icon"></i>
                        <h3 class="feature-title">Doctor Management</h3>
                        <p>Easily manage doctor schedules, specialties, and patient assignments with our intuitive interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-calendar-check feature-icon"></i>
                        <h3 class="feature-title">Appointment System</h3>
                        <p>Streamline appointment bookings, reduce wait times, and send automated reminders to patients.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-file-medical-alt feature-icon"></i>
                        <h3 class="feature-title">Patient Records</h3>
                        <p>Maintain comprehensive digital patient records with medical history, medications, and treatment plans.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-pills feature-icon"></i>
                        <h3 class="feature-title">Pharmacy Integration</h3>
                        <p>Connect your pharmacy system for seamless medication management and prescription tracking.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-chart-line feature-icon"></i>
                        <h3 class="feature-title">Analytics Dashboard</h3>
                        <p>Access real-time data and insights to make informed decisions and optimize healthcare delivery.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <h3 class="feature-title">Secure & Compliant</h3>
                        <p>Rest easy knowing that patient data is protected with industry-leading security measures.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
include 'config.php';

// Fetch active staff members
$staffQuery = "SELECT staff.name, staff.position, staff.department 
               FROM staff 
               INNER JOIN users ON staff.user_id = users.id 
               WHERE users.active = 1 
               ORDER BY RAND() 
               LIMIT 6";
$staffResult = $conn->query($staffQuery);
$staffMembers = $staffResult ? $staffResult->fetch_all(MYSQLI_ASSOC) : [];
$conn->close();
?>

<!-- Staff Section -->
<section class="staff-section" id="team">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Meet Our Medical Team</h2>
            <p class="text-muted">Dedicated professionals delivering exceptional care</p>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($staffMembers)): ?>
                <?php foreach ($staffMembers as $staff): ?>
                    <div class="col-md-4 col-lg-4">
                        <div class="feature-card staff-card">
                            <div class="staff-header">
                                <i class="fas fa-user-md staff-icon"></i>
                                <h3 class="feature-title mb-0"><?= htmlspecialchars($staff['name']) ?></h3>
                            </div>
                            <div class="staff-details">
                                <p class="mb-1 text-primary"><?= htmlspecialchars($staff['position']) ?></p>
                                <?php if ($staff['department']): ?>
                                    <p class="mb-0">
                                        <i class="fas fa-hospital"></i>
                                        <?= htmlspecialchars($staff['department']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Our team profiles are currently being updated</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section class="testimonial-section" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Trusted by Healthcare Professionals</h2>
                <p class="text-muted">See what our clients have to say about HospitalMS</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"HospitalMS has transformed how we manage our patients. The appointment system alone has saved us countless hours of administrative work."</p>
                        <p class="testimonial-author">- Dr. Sarah Johnson, Chief of Medicine</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The interface is intuitive and user-friendly. Our staff required minimal training to get up and running with the system."</p>
                        <p class="testimonial-author">- Michael Chen, Hospital Administrator</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Patient satisfaction has increased dramatically since implementing HospitalMS. The streamlined processes mean shorter wait times."</p>
                        <p class="testimonial-author">- Dr. James Wilson, Cardiologist</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Modernize Your Healthcare Facility?</h2>
            <p class="mb-4">Join thousands of healthcare providers who are already using HospitalMS</p>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="footer-title"><i class="fas fa-hospital"></i> HospitalMS</h4>
                    <p>Providing innovative healthcare management solutions that help medical facilities deliver better patient care.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-list">
                        <li><a href="#">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Resources</h5>
                    <ul class="footer-list">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">API Reference</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Contact Us</h5>
                    <ul class="footer-list">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Medical Drive, Suite 456</li>
                        <li><i class="fas fa-phone me-2"></i> (555) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> info@hospitalms.com</li>
                    </ul>
                </div>
            </div>
            <div class="text-center copyright">
                <p>© 2025 HospitalMS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent auto-scrolling on page load
        if (window.location.hash) {
            // Prevent scrolling to the hash on page load
            window.scrollTo(0, 0);
            
            // Remove the hash from the URL without triggering a page reload
            history.pushState("", document.title, window.location.pathname + window.location.search);
        }
        
        // Function to handle smooth scrolling
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                window.scrollTo({
                    top: section.offsetTop - 80, // Offset for navbar height
                    behavior: 'smooth'
                });
            }
        }
    </script>
        <script>
        // Scroll to Top Functionality
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('active');
            } else {
                scrollToTopBtn.classList.remove('active');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Existing JavaScript (keep this)
        if (window.location.hash) {
            window.scrollTo(0, 0);
            history.pushState("", document.title, window.location.pathname + window.location.search);
        }
        
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                window.scrollTo({
                    top: section.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>
</html>