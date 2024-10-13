<?php

require_once "db.php";
asd(1);
asd(2);
asd(3);
asd(4);
asd(5);
function isDateBeforeToday($date) {
    // Parse the input date
    $inputDate = strtotime($date);
    if ($inputDate === false) {
        // Invalid date format
        return false;
    }

    // Get today's date
    $today = strtotime(date('Y-m-d'));

    // Compare the dates
    return $inputDate < $today;
}
asd(6);

session_start();

$logged = false;
if (isset($_SESSION["user"])) {
    $logged = true;
    $user = $_SESSION["user"];
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination logic
$products_per_page = 4;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

// Fetch total number of products

// Fetch products for the current page
if($logged){
     $products = getProducts("Ankara", $search, $offset, $products_per_page);
     $total_products = getTotal("Ankara",$search);
}
else {
     $products = getProducts("", $search, $offset, $products_per_page);
     $total_products = getTotal("",$search);
}

$total_pages = ceil($total_products / $products_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <style>
        body {
            background-color: #FFFDF6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        header {
          background-color: #40674A;
          height: 80px;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            color: white;
        }
        span{
          color: #DC9D23;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            padding-left: 20px;
        }
        nav ul li {
            margin-right: 15px;
            line-height: 80px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            line-height: 80px;
        }
        #searchInput {
            padding: 5px;
            width: 300px;
            height: 25px;
            font-size: 1em;
            margin-left: 15px;
        }
        .grid-container {
          width: 80%;
          margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .item {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            position: relative;
        }
        .item a {
            text-decoration: none;
            color: inherit;
            margin-top: auto;
        }
        .item img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 2px;
            margin-bottom: auto;
        }
        .item-details {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            margin-top: auto;
        }
        .item p span {
            background-color: #DC9D23;
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
        }
        .item p {
            color: black;
            margin: 0;
        }
        .menu a {
            color: white;
            text-decoration: none;
        }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            background-color: #1f4034;
            color: white;
            border-radius: 3px;
        }
        .pagination a.active {
            background-color: #DC9D23;
        }
    </style>
    <script>
        function clearPlaceholder(input) {
            input.placeholder = '';
        }
        function restorePlaceholder(input) {
            if (input.value === '') {
                input.placeholder = 'Search...';
            }
        }
    </script>
</head>
<body>
<header>
    <div style="display: flex; align-items: center;">
        <h1>Bil<span>Grocer</span></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shoppingcart.php">Cart</a></li>
                <?php
                if ($logged) {
                    echo '<li><a href="logout.php">Log out</a></li>';
                    echo '<li><a href="edit_profile.php">Edit Profile</a></li>';
                } else {
                    echo '<li><a href="login.php">Log in</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
    <form method="GET" action="" style="display: flex; align-items: center;">
        <input type="text" id="searchInput" name="search" placeholder="Search..." onfocus="clearPlaceholder(this)"
               onblur="restorePlaceholder(this)" value="<?php echo htmlspecialchars($search); ?>">
    </form>
</header>
<div class="grid-container">
    <?php
//     var_dump()
    foreach ($products as $i) {
          if((!$logged || $i["user_district"] == $user["user_district"]) && (!isDateBeforeToday($i["expiration_date"]))){
               echo '<div class="item">';
               echo '<a href="productdetail.php?id=', $i['product_id'], '">';
               echo '<img src="', $i['image_url'], '" alt="', $i['title'], '">';
               echo '<div class="item-details">';
               echo '<p><span>', $i['discounted_price'], ' TL</span></p>';
               echo '<p>', $i['title'], '</p>';
               echo '</div></a></div>';
          }
    }
    foreach($products as $i) {
          if(($logged && $i["user_district"] != $user["user_district"]) && (!isDateBeforeToday($i["expiration_date"]))){
               echo '<div class="item">';
               echo '<a href="productdetail.php?id=', $i['product_id'], '">';
               echo '<img src="', $i['image_url'], '" alt="', $i['title'], '">';
               echo '<div class="item-details">';
               echo '<p><span>', $i['discounted_price'], ' TL</span></p>';
               echo '<p>', $i['title'], '</p>';
               echo '</div></a></div>';
          }
     }
    ?>
</div>
<div class="pagination">
    <?php
    for ($page = 1; $page <= $total_pages; $page++) {
        $active = $page == $current_page ? 'active' : '';
        echo '<a class="' . $active . '" href="?page=' . $page . '&search=' . htmlspecialchars($search) . '">' . $page . '</a>';
    }
    ?>
</div>
</body>
</html>

<?php
// Database connection function
function dbConnect() {
    $host = 'localhost';
    $dbname = 'UsersDB';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Function to count the total number of products
function countProducts($search = '') {
    $pdo = dbConnect();
    $sql = "SELECT COUNT(*) FROM products WHERE title LIKE :search";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', '%' . $search . '%');
    
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>