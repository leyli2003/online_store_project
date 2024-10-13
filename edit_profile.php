<?php
require_once "db.php";
require_once "mail.php";
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['user']['user_id'];
$user = getUserById($userID);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $marketname = $_POST['marketname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    var_dump($password);
    $password = password_hash($password, PASSWORD_BCRYPT);
    var_dump($hashed_password);
    $city = $_POST['city'];
    $district = $_POST['district'];
    $address = $_POST['address'];

    $stmt = $db->prepare('UPDATE users SET first_name = ?, last_name = ?, market_name = ?,user_password = ?, user_city = ?, user_district = ?, user_address = ? WHERE user_id = ?');
    $stmt->execute([$fname, $lname, $marketname, $password, $city, $district, $address, $userID]);

    if ($user['user_email'] != $email) {
        // $stmt = $db->prepare('UPDATE users SET user_email = ? WHERE user_id = ?');
        // $stmt->execute([$email, $userID]);

        $new_user = [
            'id' => $userID,
            'email' => $email];
        $_SESSION['code'] = rand(100000, 999999);
        $_SESSION['new_user'] = $new_user;
        $_SESSION['edit'] = 1;
        Mail::send($email, "Email Verification", "Please verify your email. Your code is: " .  $_SESSION['code']);
        header('Location: confirm_code.php');
        exit();
    } else {
       
        $user = getUserById($userID);
        $_SESSION['user'] = $user; 

        $type_of_user = empty($user['market_name']) ? 0 : 1;

        if($type_of_user == 0){
            header('Location: index.php');
        }
        else {
            header('Location: seller.php');
        }
        exit();
    }
}

    $fname = $user['first_name'];
    $lname = $user['last_name'];
    $marketname = $user['market_name'];
    $email = $user['user_email'];
    $password = $user['user_password'];
    $city = $user['user_city'];
    $district = $user['user_district'];
    $address = $user['user_address'];
    $type_of_user = empty($marketname) ? 0 : 1;
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <h1 class="title">Edit your Account</h1>
    <div id="registerdiv" class="box">
        <form method="post">
            <input type="text" class="input" name="fname" id="fname" placeholder="First Name" style="display: <?= $type_of_user == 0 ? 'block' : 'none'; ?>" value="<?= htmlspecialchars($fname); ?>">
            <input type="text" class="input" name="lname" id="lname" placeholder="Last Name" style="display: <?= $type_of_user == 0 ? 'block' : 'none'; ?>" value="<?= htmlspecialchars($lname); ?>">
            <input type="text" class="input" name="marketname" id="marketname" placeholder="Market Name" style="display: <?= $type_of_user == 0 ? 'none' : 'block'; ?>" value="<?= htmlspecialchars($marketname); ?>">
            <input type="text" class="input" name="email" id="email" placeholder="E-mail" value="<?= htmlspecialchars($email); ?>">
            <input type="password" class="input" name="password" id="password" placeholder="Password" value="">
            <div id="city">
                <input type="text" class="input2" name="city" placeholder="City" value="<?= htmlspecialchars($city); ?>">
                <input type="text" class="input2" name="district" placeholder="District" value="<?= htmlspecialchars($district); ?>">
            </div>
            <input type="text" class="input" name="address" id="address" placeholder="Address" value="<?= htmlspecialchars($address); ?>">
            <button type="submit" class="btn">Save changes</button>
        </form>
    </div>
</body>
</html>
