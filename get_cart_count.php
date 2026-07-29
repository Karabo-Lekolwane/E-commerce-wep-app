<?php
// Make sure this is at the very top, before any output
ini_set('session.cookie_path', '/');
session_start();

// Set proper headers
header('Content-Type: application/json');

// Initialize count
$count = 0;

// Check if cart exists and is an array
if (isset($_SESSION['cart']) ){
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $count += (int)$item['quantity'];
        }
    }
}

// Return JSON response
echo json_encode([
    'success' => true,
    'count' => $count
]);

// Make sure nothing else is output after this
exit();
?>

<?php $conn->close(); ?>