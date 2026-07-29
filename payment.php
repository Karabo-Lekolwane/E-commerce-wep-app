<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerse";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if user is logged in and has items in cart
if (!isset($_SESSION['user_id'])) {
    header("Location: account.php?error=unauthorized");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Calculate cart totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$vat = $subtotal * 0.1; // 10% VAT
$total = $subtotal + $vat;

// Database connection


// Get user details
$userDetails = [];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

$subtotal_zar = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal_zar += $item['price'] * $item['quantity'];
}
$vat_zar = $subtotal_zar * 0.1; // 10% VAT
$total_zar = $subtotal_zar + $vat_zar;

// Convert to USD (using current exchange rate)
$exchange_rate = 18.5; // Update this with current ZAR/USD rate
$subtotal_usd = $subtotal_zar / $exchange_rate;
$vat_usd = $vat_zar / $exchange_rate;
$total_usd = $total_zar / $exchange_rate;


$conn->close();

// Generate a unique invoice ID
$invoice_id = 'KR-' . uniqid();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Purchase - KR's Tech</title>
    <style>
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
            margin-bottom: 25px;
        }
        
        .card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
        }
        
        .order-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }
        
        .order-details {
            flex: 2;
            min-width: 300px;
        }
        
        .payment-options {
            flex: 1;
            min-width: 300px;
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
        
        .btn-secondary {
            background-color: #95a5a6;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .paypal-btn {
            background-color: #003087;
            margin-top: 15px;
        }
        
        .paypal-btn:hover {
            background-color: #002366;
        }
        
        .secure-note {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
        
        .payment-method {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .payment-method h3 {
            margin-top: 0;
        }
        
        .payment-method.selected {
            border-color: #3498db;
            background-color: #f0f8ff;
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
        
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
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
            
            .order-summary {
                flex-direction: column;
            }
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
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="account.php">Account</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <h1 style="margin: 20px 0;">Complete Your Purchase</h1>
        
        <div class="order-summary">
            <div class="order-details card">
                <h2>Order Summary</h2>
                
                <!-- Shipping Address Box (styled like saved-details) -->
                <div class="saved-details">
                    <h4>Shipping Address</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']); ?></p>
                    <?php if (!empty($userDetails['address'])): ?>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($userDetails['address']); ?></p>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($userDetails['city']); ?></p>
                        <p><strong>State:</strong> <?php echo htmlspecialchars($userDetails['state']); ?></p>
                        <p><strong>ZIP:</strong> <?php echo htmlspecialchars($userDetails['zip']); ?></p>
                        <p><strong>Country:</strong> <?php echo htmlspecialchars($userDetails['country']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($userDetails['phone'])): ?>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($userDetails['phone']); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="order-items">
                    <h3>Your Items</h3>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="product-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                            <div class="product-info">
                                <h4 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p>R<?php echo number_format($item['price'], 2); ?> each</p>
                            </div>
                            <div>
                                R<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span>R<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Shipping:</span>
                        <span>R0.00</span>
                    </div>
                    <div class="price-row">
                        <span>VAT (10%):</span>
                        <span>R<?php echo number_format($vat, 2); ?></span>
                    </div>
                    <div class="price-row grand-total">
                        <span>Total:</span>
                        <span>R<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Total (USD):</span>
                        <span>$<?php echo number_format($total_usd, 2); ?> (Approx)</span>
                    </div>

                </div>
            </div>
            
            <div class="payment-options card">
                <h2>Payment Method</h2>
                
                <div class="payment-method selected">
                    <h3>PayPal</h3>
                    <p>Pay securely using your PayPal account or credit card.</p>
                    <p><small>Amount will be converted from ZAR to USD at checkout</small></p>
                    <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" style="margin: 10px 0;">
                    
                    <!-- PayPal Button Container -->
                    <div id="paypal-button-container"></div>
                    
                    <div class="secure-note">
                        <i class="fas fa-lock"></i> Your payment is secure and encrypted
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.location.href='account.php'" style="padding: 15px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 20px; cursor: pointer;">
            Back to Account
        </button>
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

    <!-- PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AZPJ3cQT1CxOIu65dNGG-PpV_VyphGOCzi_XdhmHVbUIQEgtYnsbqkgUASh3ScI8uQve2xR4YXsSqmnM&currency=ZAR"></script>
    
    <script>
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'paypal'
        },
        createOrder: function(data, actions) {
    return actions.order.create({
        purchase_units: [{
            amount: {
                value: '<?php echo number_format($total, 2, '.', ''); ?>',
                currency_code: 'ZAR',
                breakdown: {
                    item_total: {
                        value: '<?php echo number_format($subtotal, 2, '.', ''); ?>',
                        currency_code: 'ZAR'
                    },
                    tax_total: {
                        value: '<?php echo number_format($vat, 2, '.', ''); ?>',
                        currency_code: 'ZAR'
                    }
                }
            },
            items: [
                <?php 
                $first = true;
                foreach ($_SESSION['cart'] as $item): 
                    if (!$first) echo ',';
                    $first = false;
                ?>
                {
                    name: <?php echo json_encode($item['name']); ?>,
                    unit_amount: {
                        value: '<?php echo number_format($item['price'], 2, '.', ''); ?>',
                        currency_code: 'ZAR'
                    },
                    quantity: '<?php echo $item['quantity']; ?>'
                }
                <?php endforeach; ?>
            ],
            invoice_id: '<?php echo $invoice_id; ?>'
        }],
        application_context: {
            shipping_preference: 'NO_SHIPPING'
        }
    });
},
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                document.getElementById('paypal-button-container').innerHTML = 
                    '<div style="padding:15px;background:#d4edda;border-radius:4px;">Processing payment...</div>';
                
                return fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        details: details,
                        invoiceID: '<?php echo $invoice_id; ?>'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
                });
            });
        },
        onError: function(err) {
            document.getElementById('paypal-button-container').innerHTML = 
                '<div style="padding:15px;background:#f8d7da;border-radius:4px;">Error: ' + 
                err.message + '</div>' +
                '<button class="btn" onclick="window.location.reload()">Try Again</button>';
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
