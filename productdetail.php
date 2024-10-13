<?php
session_start();

require_once("db.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["type_of_user"] == '1') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user"];

$stmt = $db->prepare("select count(*) from products");
$stmt->execute();
$cnt = $stmt->fetch(PDO::FETCH_ASSOC)["count(*)"];

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
else {
    header("Location: login.php");
    exit;
}

if((int)$cnt < (int)$id) {
    header("Location: login.php");
    exit;
}



$stmt = $db->prepare("SELECT * FROM products WHERE product_id = :product_id");
$stmt->execute(['product_id'=>$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['quantity'])) {
    $quantity = $_GET['quantity'];
    $cart_item = ['product_id' => $id, 'quantity' => $quantity];
    
   
    $existing_key = array_search($id, array_column($_SESSION['cart'], 'product_id'));

    if ($existing_key !== false) {
      
        $_SESSION['cart'][$existing_key]['quantity'] = $quantity;
    } else {
        
        $_SESSION['cart'][] = $cart_item;
    }
}


$quantity = 0;
foreach ($_SESSION['cart'] as $item) {
    if ($item['product_id'] == $id) {
        $quantity = $item['quantity'];
        break;
    }
}
$stmt = $db->prepare("SELECT stock from products where product_id= :product_id");
$stmt->execute(['product_id'=>$id]);
$stock = $stmt->fetch(PDO::FETCH_ASSOC)['stock'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    
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
    background-color: #1f4034;
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

.product-details {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.product-details img {
    max-width: 50%;
    height: auto;
    margin-bottom: 20px;
    border-radius: 2px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

.price {
    font-size: 1.2em;
    font-weight: bold;
    color: #333;
}

.normal_price {
    font-size: 1.2em;
    color: #333;
}

.description {
    color: #666;
}

.button {
    background-color: #DC9D23;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #ff9900;
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

span {
    color: #40674A;
}

#cross {
    text-decoration: line-through;
}
a {
    text-decoration: none;
    color: white;
}
#control {
    display: flex;
    justify-content: center;
}
#control div {
   
    border-radius:10px;
}
#quantity {
    font-size: 1.5em;
    width: 50px;
    text-align: center;
}
#updateCart{
margin-top:22px;
border-radius: 20px;
box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

}
</style>
<body>

<header>
    <h1>Bil<span>Grocer</span></h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="shoppingcart.php">Cart</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="product-details">
        <img src="<?=$product['image_url']?>" alt="Product Image">
        <h2><?=$product['title']?></h2>
        <p class="normal_price" id="cross"><?=$product['normal_price']?> TL</p>
        <p class="price"><?=$product['discounted_price']?> TL</p>
        <div id="control">
            <div id="decreaseQuantity" class="button">-</div>
            <span id="quantity"><?=$quantity?></span>
            <div id="increaseQuantity" class="button">+</div>
        </div>
        <button id="updateCart" class="button">Update Cart</button>
    </div>
</main>
 <footer>
    <p>&copy; 2024 Online Store. All rights reserved.</p>
</footer> 

<script type = "text/javascript">
  
document.getElementById('decreaseQuantity').addEventListener('click', function() {
   console.log("yes1");
    var quantityElement = document.getElementById('quantity');
    var quantity = parseInt(quantityElement.textContent);
    if (quantity > 0) {
        quantityElement.textContent = quantity - 1;
    }
});

document.getElementById('increaseQuantity').addEventListener('click', function() {
    console.log("yes2");
    var quantityElement = document.getElementById('quantity');
    var quantity = parseInt(quantityElement.textContent);
    console.log(quantity);
    <?php if(isset($stock)) : ?>
        var stock = <?php echo json_encode($stock); ?>;
        if (quantity < stock) {
            quantityElement.textContent = quantity + 1;
        }
        else 
        quantityElement.textContent = stock;
    <?php endif; ?>
});



document.getElementById('updateCart').addEventListener('click', function() {
    var quantity = document.getElementById('quantity').textContent;
    var urlParams = new URLSearchParams(window.location.search);
    var id = urlParams.get('id');
    window.location.href = 'productdetail.php?quantity=' + quantity + '&id=' + id;
});
</script>
</body>
</html>
