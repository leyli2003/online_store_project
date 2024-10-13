<?php
require_once "db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete the product from the database
    $stmt = $db->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    header("Location: seller.php");
    exit;
}
?>
