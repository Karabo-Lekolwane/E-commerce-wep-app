<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get category filter - ensure it's an integer or null
$category_id = isset($_GET['category']) && is_numeric($_GET['category']) ? (int)$_GET['category'] : null;

// Get price filter - ensure they're numeric values
$min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : 10000;

// Get pagination - ensure it's a positive integer
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 9;
$offset = ($page - 1) * $items_per_page;

// Build SQL query with prepared statements to prevent SQL injection
$sql = "SELECT * FROM products WHERE price >= ? AND price <= ?";
$params = array($min_price, $max_price);
$types = "dd";

if ($category_id) {
    $sql .= " AND category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

$sql .= " LIMIT ? OFFSET ?";
$params[] = $items_per_page;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();

// Get total products count for pagination
$count_sql = "SELECT COUNT(*) AS total FROM products WHERE price >= ? AND price <= ?";
$count_params = array($min_price, $max_price);
$count_types = "dd";

if ($category_id) {
    $count_sql .= " AND category_id = ?";
    $count_params[] = $category_id;
    $count_types .= "i";
}

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($count_types, ...$count_params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_products = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $items_per_page);

// Get categories
$categories_sql = "SELECT * FROM categories";
$categories = $conn->query($categories_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - KR's Tech</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Your existing CSS remains exactly the same */

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
        
        /* Products-specific styles */
        .page-title {
            margin: 30px 0;
            font-size: 28px;
            text-align: center;
        }
        
        .products-container {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .filters {
            width: 250px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            align-self: flex-start;
            position: sticky;
            top: 90px;
        }
        
        .filter-section {
            margin-bottom: 20px;
        }
        
        .filter-title {
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .filter-list {
            list-style: none;
        }
        
        .filter-list li {
            margin-bottom: 10px;
        }
        
        .filter-list label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .filter-list input {
            margin-right: 10px;
        }
        
        .price-range {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .price-inputs {
            display: flex;
            gap: 10px;
        }
        
        .price-input {
            width: 50%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .apply-filter {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .apply-filter:hover {
            background-color: #2980b9;
        }
        
        .products-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            padding: 15px;
            background-color: #f9f9f9;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-size: 20px;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .add-to-cart {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: #27ae60;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }
        
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .pagination a:hover, .pagination a.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        .no-products {
            text-align: center;
            padding: 50px 0;
            color: #7f8c8d;
            grid-column: 1 / -1;
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

        /* Toast Notification */
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #2ecc71;
    color: white;
    padding: 15px 25px;
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.toast.show {
    transform: translateY(0);
    opacity: 1;
}

.toast i {
    margin-right: 10px;
    font-size: 20px;
}

/* Close button for toast */
.toast-close {
    margin-left: 15px;
    cursor: pointer;
    font-weight: bold;
}

.cart-count {
    background-color: #3498db;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    margin-left: 5px;
}

.search-bar {
            display: flex;
            margin: 25px 0;
        }
        
        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        
        .search-bar button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
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

    <div class="search-bar">
    <form method="GET" action="index.php" style="display: flex; width: 100%;">
        <input type="text" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
    </div>

        <h1 class="page-title">Our Products</h1>

        <div class="products-container">
            <aside class="filters">
                <form action="products.php" method="GET">
                    <!-- Hidden fields to maintain price filters when only category changes -->
                    <input type="hidden" name="min_price" value="<?php echo $min_price; ?>">
                    <input type="hidden" name="max_price" value="<?php echo $max_price; ?>">
                    
                    <div class="filter-section">
                        <h3 class="filter-title">Categories</h3>
                        <ul class="filter-list">
                            <li>
                                <label>
                                    <input type="radio" name="category" value="" <?php echo !$category_id ? 'checked' : ''; ?> 
                                    onclick="this.form.submit()">
                                    All Categories
                                </label>
                            </li>
                            <?php if ($categories->num_rows > 0): ?>
                                <?php while($category = $categories->fetch_assoc()): ?>
                                    <li>
                                        <label>
                                            <input type="radio" name="category" value="<?php echo $category['id']; ?>" 
                                            <?php echo $category_id == $category['id'] ? 'checked' : ''; ?>
                                            onclick="this.form.submit()">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </label>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li>
                                    <label>
                                        <input type="radio" name="category" value="1" onclick="this.form.submit()">
                                        Laptops
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="category" value="2" onclick="this.form.submit()">
                                        Smartphones
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="category" value="3" onclick="this.form.submit()">
                                        Desktop PCs
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="category" value="4" onclick="this.form.submit()">
                                        Accessories
                                    </label>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Price Range</h3>
                        <div class="price-range">
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Min" class="price-input" value="<?php echo $min_price; ?>">
                                <input type="number" name="max_price" placeholder="Max" class="price-input" value="<?php echo $max_price != 10000 ? $max_price : ''; ?>">
                            </div>
                            <button type="submit" class="apply-filter">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </aside>

            <div class="products-grid">
                <?php if ($products->num_rows > 0): ?>
                    <?php while($product = $products->fetch_assoc()): ?>
                        <div class="product-card">
                            <a href="productdetails.php?id=<?php echo $product['id']; ?>">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-price">R<?php echo number_format($product['price'], 2); ?></p>
                                <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-products">
                        <p>No products found with current filters</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>       
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

    <!-- Toast Notification -->
<div id="toast" class="toast">
    <i>✓</i>
    <span id="toast-message">Product added to cart!</span>
    <span class="toast-close" onclick="hideToast()">×</span>
</div>

<script>
    // Toast notification functions
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        toastMessage.textContent = message;
        toast.classList.add('show');
        
        // Auto-hide after 3 seconds
        setTimeout(hideToast, 3000);
    }
    
    function hideToast() {
        document.getElementById('toast').classList.remove('show');
    }

    // Function to update cart count
    function updateCartCount() {
    fetch('get_cart_count.php', {
        method: 'GET',
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                cartCountEl.textContent = data.count;
            }
        }
    })
    .catch(error => {
        console.error('Error fetching cart count:', error);
    });
}

// Call once when page loads
//document.addEventListener('DOMContentLoaded', updateCartCount);

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
      const productId = this.getAttribute('data-id');
      const productName = this.closest('.product-info').querySelector('.product-title').textContent.trim();
      const button = this;
      
      // Disable button to prevent multiple clicks
      button.disabled = true;
      button.textContent = 'Adding...';
      
      // Send AJAX request to add to cart
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if(data.success) {
          showToast(`${productName} added to cart!`);
          updateCartCount();
        } else {
          showToast(data.message || 'Error adding product to cart');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Added to cart!');
        // Even if we got an error, try to update the count in case it worked
        updateCartCount();
      })
      .finally(() => {
        button.disabled = false;
        button.textContent = 'Add to Cart';
      });
    });

// Add CSS for pulse animation
const style = document.createElement('style');
    style.textContent = `
        .pulse {
            animation: pulse 0.5s ease-in-out;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);

    // Initial update of cart count when page loads
    updateCartCount();
});
</script>
</body>
</html>

<?php $conn->close(); ?>

