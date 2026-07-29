<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: account.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerse";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order details
$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT o.*, u.first_name, u.last_name, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: account.php");
    exit;
}

// Get order items
$items = json_decode($order['items'], true);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - KR's Tech</title>
    <style>
        /* Same styles as your payment.php page */
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
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin: 25px 0;
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .confirmation-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .confirmation-header .success-icon {
            color: #2ecc71;
            font-size: 50px;
            margin-bottom: 15px;
        }
        
        .order-details {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .order-info, .shipping-info {
            flex: 1;
            min-width: 300px;
        }
        
        .saved-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .saved-details h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .saved-details p {
            margin-bottom: 5px;
        }
        
        .order-items {
            margin-top: 30px;
        }
        
        .product-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .price-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .grand-total {
            font-size: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            nav ul {
                margin-top: 15px;
                justify-content: center;
            }
            
            nav ul li {
                margin: 0 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="account.php">Account</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <div class="confirmation-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Thank You For Your Order!</h1>
                <p>Your order #<?php echo htmlspecialchars($order['invoice_id']); ?> has been confirmed</p>
                <p>We've sent a confirmation email to <?php echo htmlspecialchars($order['email']); ?></p>
            </div>
            
            <div class="order-details">
                <div class="order-info">
                    <h2>Order Details</h2>
                    <div class="saved-details">
                        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['invoice_id']); ?></p>
                        <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                        <p><strong>Total:</strong> R<?php echo number_format($order['amount'], 2); ?></p>
                        <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                        <p><strong>Status:</strong> <span style="color: #2ecc71;"><?php echo ucfirst($order['status']); ?></span></p>
                    </div>
                </div>
                
                <div class="shipping-info">
                    <h2>Customer Information</h2>
                    <div class="saved-details">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h2>Order Items</h2>
                <?php foreach ($items as $item): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                        <div class="product-info">
                            <h4 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div>
                            R<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span>R<?php echo number_format(array_reduce($items, function($carry, $item) { 
                            return $carry + ($item['price'] * $item['quantity']); 
                        }, 0), 2); ?></span>
                    </div>
                    <div class="price-row">
                        <span>VAT (10%):</span>
                        <span>R<?php echo number_format($order['amount'] * 0.1, 2); ?></span>
                    </div>
                    <div class="price-row grand-total">
                        <span>Total:</span>
                        <span>R<?php echo number_format($order['amount'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="products.php" class="btn">Continue Shopping</a>
                <a href="account.php" class="btn" style="margin-left: 10px;">View Order History</a>
            </div>
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
                        <li><a href="blog.php">Blog</a></li>
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
</body>
</html>


