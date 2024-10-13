<?php
session_start();

require_once "db.php";

if(isset($_SESSION["user"])) {
     setcookie("access_token", $token, time() - 100000000) ;
     setTokenByEmail($_SESSION["user"]["user_email"], "NULL");
     session_destroy();
}
header("Location: index.php") ;