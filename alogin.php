<?php
session_start();
$fail = false;
require_once "db.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function checkUser($email, $password) {
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        }
    } catch (Exception $e) {
        error_log($e->getMessage(), 3, '/tmp/php_errors.log');
    }
    return false;
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? $_POST['remember'] : null;

    $user = checkUser($email, $password);
    if ($user) {
        if ($remember) {
            $token = sha1(uniqid() . "Private Key is Here" . time());
            setcookie("access_token", $token, time() + 60*60*24*365*10, "/", "", false, true);
            setTokenByEmail($email, $token);
        }

        // login as $user
        $_SESSION["user"] = $user;

        // Check if the user is a market user (adjust the condition as necessary)
        if ($user["type_of_user"] == 'market') {
            markExpiredProducts(); // Call the function to mark expired products
            header("Location: seller.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $fail = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_COOKIE["access_token"])) {
    $user = getUserByToken($_COOKIE["access_token"]);
    if ($user) {
        $_SESSION["user"] = $user; // auto login

        // Check if the user is a market user (adjust the condition as necessary)
        if ($user["type_of_user"] == 'market') {
            markExpiredProducts(); // Call the function to mark expired products
            header("Location: seller.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isAuthenticated()) {
    if ($_SESSION["user"]["type_of_user"] == 'market') {
        header("Location: seller.php");
    } else {
        header("Location: index.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        span { color: #DC9D23; }
        .checkbox-container {
            display: flex;
            margin-left: 50px;
            width: 150px;
        }
        .checkbox-label {
            text-align: left;
            text-indent: 10px;
        }
        #p {
            margin: 20px 190px;
        }
    </style>
</head>
<body>
    <h1 class="title"><span>Bil</span>Grocer</h1>
    <div id="logindiv" class="box">
        <form method="post">
            <br>
            <input type="text" class="input" name="email" placeholder="E-MAIL" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : '' ?>">
            <input type="password" class="input" name="password" placeholder="PASSWORD" value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : '' ?>">
            <div class="checkbox-container">
                <div id="a">
                    <input type="checkbox" id="remember" name="remember" style="margin-right: 5px;">
                </div>
                <div class="checkbox-label"><label for="remember">Remember me</label></div>
            </div>
            <?php
                if ($fail) {
                    echo '<p style="color: red;">Failed to log in</p>';
                }
            ?>
            <button type="submit" class="btn">LOG IN</button>
            <p id="p">New to BilGrocer? <a href="register.php">Join now</a></p>
        </form>
    </div>
</body>
</html>