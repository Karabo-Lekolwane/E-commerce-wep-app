<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - KR's Tech</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    


    <style>

:root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #95a5a6;
            --secondary-dark: #7f8c8d;
            --card-bg: #fff;
            --card-border: #ebebeb;
            --gray: #8e9196;
            --background: #f5f5f5;
            --nav-bg: #2c3e50;
            --nav-link-hover: #3498db;
            --success: #d4edda;
            --success-border: #c3e6cb;
            --success-text: #155724;
            --danger: #f8d7da;
            --danger-border: #f5c6cb;
            --danger-text: #721c24;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .logo span {
            color: #3498db;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 20px;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: #3498db;
        }
        
        .page-title {
            text-align: center;
            margin: 40px 0;
        }
        
        .page-title h1 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .page-title p {
            color: #7f8c8d;
            font-size: 18px;
        }
        
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 60px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-form {
            flex: 2;
            min-width: 300px;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            height: 100%;
        }
        
        .card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 25px;
        }
        
        .info-item .icon {
            width: 50px;
            height: 50px;
            background-color: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
        }
        
        .info-item .content h3 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .info-item .content p {
            color: #7f8c8d;
        }
        
        .social-links {
            display: flex;
            margin-top: 30px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background-color: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .social-links a:hover {
            background-color: #2980b9;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .map-container {
            height: 400px;
            margin-bottom: 60px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }
        
        .footer-column h3 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column ul li a:hover {
            color: #3498db;
        }
        
        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #34495e;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        header {
    background-color: var(--nav-bg); /* Changed from #2c3e50 to variable */
    color: #fff;
    padding: 22px 0 15px 0; /* Changed from 15px 0 to asymmetric padding */
    position: sticky;
    top: 0;
    z-index: 101; /* Changed from 100 to 101 */
    box-shadow: 0 2px 6px rgba(44,62,80,0.05); /* Added box shadow */
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem; /* Added gap */
}

.logo {
    font-size: 2rem; /* Added logo styling */
    font-weight: 700;
    letter-spacing: 2px;
}

.logo span {
    color: var(--primary); /* Added colored span */
}

nav ul {
    display: flex;
    gap: 20px; /* Changed from margin to gap */
    list-style: none;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: color .3s, border-bottom-color .3s; /* Added transitions */
    border-bottom: 2px solid transparent;
    padding-bottom: 2px;
}

nav ul li a:hover,
nav ul li a.active {
    color: var(--nav-link-hover);
    border-bottom: 2px solid var(--nav-link-hover);
}

/* Responsive adjustments - changed from 768px to 700px */
@media (max-width: 700px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start; /* Changed from center to flex-start */
        gap: 0.5rem; /* Added smaller gap */
    }
    
    nav ul {
        margin-top: 0; /* Removed the 15px margin */
        flex-wrap: wrap; /* Added wrapping */
        gap: 10px; /* Smaller gap for mobile */
        justify-content: flex-start; /* Changed from center */
    }
    
    /* Remove the unrelated product grid styles from here */
}

        .cart-count {
    background-color: #3498db;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    margin-left: 5px;
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">KR's<span>Tech</span></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li>
                            <a href="cart.php">
                                <i class="fas fa-shopping-cart"></i>
                                Cart <span id="cart-count" class="cart-count">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="account.php">
                                <i class="fas fa-user"></i>
                                Account
                            </a>
                        </li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-title">
            <h1>Contact Us</h1>
            <p>Have questions? We'd love to hear from you!</p>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                <div class="card">
                    <h2>Get In Touch</h2>
                    
                    <div class="info-item">
    <div class="icon">📍</div>
    <div class="content">
        <h3>Our Location</h3>
        <p>
            <a href="https://www.google.com/maps/search/?api=1&query=Molotlegi+St,+Ga-Rankuwa+Zone+1,+Ga-Rankuwa,+0208" target="_blank" style="color:#2c3e82;">
                Molotlegi St, Ga-Rankuwa Zone 1, Ga-Rankuwa, 0208
            </a>
        </p>
    </div>
</div>

<div class="info-item">
    <div class="icon">📞</div>
    <div class="content">
        <h3>Phone Number</h3>
        <p><a href="tel:+27822178270" style="color:#2c3e80;">+27 (082) 217-8270</a></p>
        <p><a href="tel:+27125214111" style="color:#2c3e80;">+27 (012) 521-4111</a></p>
    </div>
</div>

<div class="info-item">
    <div class="icon">✉️</div>
    <div class="content">
        <h3>Email Address</h3>
        <p><a href="mailto:krs.tech.store@gmail.com" style="color:#2c3e80;">krs.tech.store@gmail.com</a></p>
        <p><a href="mailto:sales@krtech.com" style="color:#2c3e80;">sales@krtech.com</a></p>
    </div>
</div>

                    
                    <div class="info-item">
                        <div class="icon">⏰</div>
                        <div class="content">
                            <h3>Working Hours</h3>
                            <p>Monday - Friday: 9:00 AM - 4:00 PM</p>
                            <p>Saturday: 10:00 AM - 3:00 PM</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>


                </div>
            </div>
            
            <div class="contact-form">
                <div class="card">
                    <h2>Send Us A Message</h2>
                    
                    <?php if (isset($_POST['submit'])): ?>
                        <div class="alert alert-success">
                            Thank you for your message! We'll get back to you shortly.
                        </div>
                    <?php endif; ?>
                    
                    <form action="contact.php" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Your Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="inquiry-type">Inquiry Type</label>
                            <select id="inquiry-type" name="inquiry_type" required>
                                <option value="">Select an option</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="returns">Returns & Exchanges</option>
                                <option value="feedback">Product Feedback</option>
                                <option value="business">Business Inquiry</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        
                        <button type="submit" name="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <h1 style="text-align: center; color:#2c3e50"> Our Location </h1>
        <br>
        
        <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8526.386699360706!2d28.012055760243232!3d-25.61724018941119!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ebfd1f56c98b89f%3A0x8c61d7b58168a8ed!2sSefako%20Makgatho%20Health%20Sciences%20University!5e1!3m2!1sen!2sza!4v1744924561437!5m2!1sen!2sza" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>KR's Tech</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="careers.php">Careers</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="shipping.php">Shipping Policy</a></li>
                        <li><a href="returns.php">Returns & Exchanges</a></li>
                        <li><a href="terms.php">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>My Account</h3>
                    <ul>
                        <li><a href="account.php">Account</a></li>
                        <li><a href="orders.php">Order History</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                        <li><a href="newsletter.php">Newsletter</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Connect With Us</h3>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 KR's Tech. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <?php
    // Process form submission
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $inquiry_type = $_POST['inquiry_type'];
        $message = $_POST['message'];
        
        // This would normally be where you would process the form
        // e.g., send an email, save to database, etc.
        
        // For example, to send an email (this won't actually work in this environment):
        /*
        $to = "krs.tech.store.com";
        $headers = "From: $email";
        $email_subject = "Contact Form: $subject ($inquiry_type)";
        $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email\n\nInquiry Type: $inquiry_type\n\nMessage:\n$message";
        
        mail($to, $email_subject, $email_body, $headers);
        */
        
        // Redirect to prevent form resubmission
        // header("Location: contact.php?status=success");
        // exit;
    }
    ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
    // Toast notification functions
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        toastMessage.textContent = message;
        toast.classList.add('show');
        
        setTimeout(hideToast, 3000);
    }
    
    function hideToast() {
        document.getElementById('toast').classList.remove('show');
    }

    // Cart functionality
    function updateCartCount() {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('cart-count').textContent = data.count;
                }
            })
            .catch(console.error);
    }

    // Initialize cart count on page load
    document.addEventListener('DOMContentLoaded', updateCartCount);

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.closest('.product-info').querySelector('.product-title').textContent;
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(`${productName} added to cart!`);
                    updateCartCount();
                } else {
                    showToast(data.message || 'Error adding to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart');
            });
        });
    });


    
</script>


</body>
</html>

