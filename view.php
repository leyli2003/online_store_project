<?php
session_start();

require_once("db.php");

if (!isset($_SESSION["user"])) {
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

// if((int)$cnt < (int)$id) {
//     header("Location: login.php");
//     exit;
// }



$stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);




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
            <li><a href="seller.php">Home</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="product-details">
        <img src="<?=$product['image_url']?>" alt="Product Image">
        <h2><?=$product['title']?></h2>
        <p class="normal_price" id="cross"><?=$product['normal_price']?> TL</p>
        <p class="price"><?=$product['discounted_price']?> TL</p>
        <p><?=$product['expiration_date']?></p>
    </div>
</main>
<footer>
    <p>&copy; 2024 Online Store. All rights reserved.</p>
</footer>

</body>
</html>
