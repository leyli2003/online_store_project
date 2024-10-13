<?php
require_once("db.php");

session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["type_of_user"] == '1') {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["user"]["user_id"];

$stmt1 = $db->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
$stmt1->execute([$user_id]);
$cartItems = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$detailedCartItems = [];
foreach ($_SESSION["cart"] as $cartItem) {
    $stmt2 = $db->prepare("SELECT title, image_url, discounted_price FROM products WHERE product_id = ?");
    $stmt2->execute([$cartItem['product_id']]);
    $product = $stmt2->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $detailedCartItems[] = array_merge($cartItem, $product);
    }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity > 0) {
            foreach ($_SESSION["cart"] as $key => $cartItem) {
                if ($cartItem["product_id"] == $product_id) {
                    $stmt = $db->prepare("SELECT stock from products where product_id= ?");
                    $stmt->execute([$product_id]);
                    $stock = (int)$stmt->fetch(PDO::FETCH_ASSOC)['stock'];
                    if($quantity > $stock) $quantity = $stock;
                    $_SESSION["cart"][$key]["quantity"] = $quantity;
                }
            }
        }
    }

    if (isset($_POST['clear_cart'])) {
        $_SESSION["cart"] = [];
        header("Location: shoppingcart.php");
        exit;
    }
    if (isset($_POST['buy_cart'])) {
        foreach ($_SESSION["cart"] as $key => $cartItem) {
            $quantity = $_SESSION["cart"][$key]["quantity"];
            $stmt = $db->prepare("SELECT stock from products where product_id= ?");
            $stmt->execute([$_SESSION["cart"][$key]["product_id"]]);
            $stock = (int)$stmt->fetch(PDO::FETCH_ASSOC)['stock'];
            $stock = $stock - $quantity;

            $stmt = $db->prepare("UPDATE products set stock = ? where product_id= ?");
            $stmt->execute([$stock, $_SESSION["cart"][$key]["product_id"]]);

        } 
        $_SESSION["cart"] = [];
        header("Location: shoppingcart.php");
        exit;
    }
    header("Location: shoppingcart.php");
    exit;
}
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])){
    $id = $_GET['id'];
    foreach($_SESSION["cart"] as $key => $cartItem){
        if($cartItem["product_id"] == $id){
            unset($_SESSION["cart"][$key]);

        }
    }

    header("Location: shoppingcart.php");
    exit;
}

$totalPrice = 0;
foreach ($detailedCartItems as $item) {
    $totalPrice += $item['discounted_price'] * $item['quantity'];
}

// var_dump($_SESSION["cart"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>

body {
    background-color: #FFFDF6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    color: #fff;
    padding: 20px;
    background-color: #40674A;
}

header h1 {
    margin: 0;
    color: #DC9D23;
}

nav ul {
    list-style-type: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

main {
    padding: 20px;
}

.cart {
    max-width: 800px;
    margin: 0 auto;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.cart-item img {
    max-width: 100px;
    height: auto;
    border-radius: 4px;
}

.cart-item-details {
    flex-grow: 1;
    margin-left: 20px;
}

.cart-item-details h4 {
    margin: 0;
    font-size: 1.2em;
}

.cart-item-details p {
    margin: 5px 0;
    color: #333;
}

.cart-item-actions {
    display: flex;
    align-items: center;
}

.cart-item-actions input[type="number"] {
    width: 50px;
    padding: 5px;
    margin-right: 10px;
}

.cart-item-actions button {
    background-color: #DC9D23;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-item-actions button:hover {
    background-color: #ff9900;
}

.total {
    text-align: right;
    margin-top: 20px;
    font-size: 1.2em;
    font-weight: bold;
}

footer {
    background-color: #1f4034;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
}
a{
    text-decoration:none;
    color:white;
}
</style>
<body>

<header>
    <h1>Bil<span>Grocer</span></h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="cart">
        <h2>Shopping Cart</h2>
        <form id="cartForm" method="post">
            <?php foreach ($detailedCartItems as $item): ?>

                <div class="cart-item">
                    <img src="<?=$item['image_url']?>" alt="<?=$item['title']?>">
                    <div class="cart-item-details">
                        <h4><?=$item['title']?></h4>
                        <p><?=$item['discounted_price']?> TL</p>
                    </div>
                    <div class="cart-item-actions">
                        <input class="quantityInput" type="number" name="quantities[<?=$item['product_id']?>]" value="<?=$item['quantity']?>" min="0">
                        <button name="remove"><a href="shoppingcart.php?id=<?=$item['product_id']?>">Remove</a></button>
                        <input type="hidden" name="product_id" value="<?=$item['product_id']?>">
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="total">
                Total: <?=$totalPrice?> TL
            </div>
            
            <button type="submit" name="clear_cart" style="background-color: orange; color: white; padding: 10px;border:white; margin-top: 20px;">Clear Cart</button>
            <button type="submit" name="buy_cart" style="background-color: orange; color: white; padding: 10px;border:white; margin-top: 20px;">Buy Cart</button>
            
        </form>
        
    </div>
</main>

<script>

document.addEventListener('DOMContentLoaded', function() {
    var quantityInputs = document.querySelectorAll('.quantityInput');
    quantityInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            document.getElementById('cartForm').submit();
        });
    });
});
</script>

</body>
</html>


