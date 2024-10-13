<?php

require_once "db.php";
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["type_of_user"] == '0') {
    header("Location: index.php");
    exit;
}
// if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }
$stmt = $db->prepare('select * from products where user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION["user"]["user_id"]]);
$list = $stmt->fetchAll();

$stmt = $db->prepare("SELECT * FROM products WHERE user_id = :user_id");
$stmt->execute(['user_id' =>$_SESSION["user"]["user_id"]]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome{
            width: fit-content;
            margin: 50px auto;
            color: #40674A;
        }

        td,th{
            padding: 10px;
            border-bottom: 1px solid grey;
        }
        table {
            margin: auto;
            border-collapse: collapse;
        }
        span{ color: #DC9D23;}
        /* *{
            border: 1px solid red;
        } */
        .cross{
            color: lightgray;
        }
        .btncross a{
            margin-right: 10px;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            color: white;
            background-color: lightgray;
        }
        .btncross a:hover{
            background-color: gray;
        }
        header{
            background-color: #40674A;
            margin: 0;
            padding: 0;
            height: 100px;
        }
        .home{
            display: flex;
            height: 100px;
            line-height: 100px;
            gap: 20px;
            margin-left: 20px;
        }
        .home a {
            text-decoration: none;
            color: white;
        }
        .bighome{
            display: flex;
        }
        .logo{
            margin-left: 20px;
            line-height: 50px;
            color:white;
        }
    </style>
</head>
<body>
<header>
        <div class="bighome">
            <h1 class="logo">Bil<span>Grocer</span></h1>
                <div class="home"><a href="index.php">Home</a></li>
                    <?php
                        echo "<div><a href='logout.php'>Log out</a></div>";
                        echo "<div><a href='edit_profile.php'>Edit Profile</a></div>";
                    ?>
                    <div><a href='add_product.php'>Add Product</a></div>
        </div>
</header>
    <h1 class="welcome">Welcome, <span> 
        <?php 
            $marketname = htmlspecialchars($_SESSION['user']['market_name'],ENT_QUOTES, 'UTF-8');
            echo $marketname; 
        ?>!</span></h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Stock</th>
                <th>Normal Price</th>
                <th>Discounted Price</th>
                <th>Expiration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $row): ?>
                    <?php 
                        if(isDateBeforeToday($row['expiration_date'])==true) {
                            echo "<tr class='cross'>";
                        }
                        else{ echo "<tr>";}
                    ?>
                    
                    <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['stock'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['normal_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['discounted_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['expiration_date'], ENT_QUOTES, 'UTF-8');?></td>
                    <?php 
                        if(isDateBeforeToday($row['expiration_date'])==true) {
                            echo "<td class='btncross'>";
                        }
                        else{ echo "<td class='action-links'>";}
                        echo "<a href='edit_product.php?id=" . htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
                        echo "<a href='view.php?id=" . htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8') . "'>View</a>";
                        echo "<a href='delete_product.php?id=" . htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8') . "' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>";
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</body>
</html>
