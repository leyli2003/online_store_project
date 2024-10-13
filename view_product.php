<?php
require_once "db.php";
$product_id = $_GET['id'];
$product = getProductbyId($product_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($product['title']); ?></h1>
    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" />
    <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
    <p>Normal Price: $<?php echo htmlspecialchars($product['normal_price']); ?></p>
    <p>Discounted Price: $<?php echo htmlspecialchars($product['discounted_price']); ?></p>
    <p>Expiration Date: <?php echo htmlspecialchars($product['expiration_date']); ?></p>
    <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
    <a href="seller.php">Save</a>
</body>
</html>
