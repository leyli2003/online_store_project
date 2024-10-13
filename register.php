<script>
    var type_of_user = 0;
</script>
<?php
     session_start();
     require_once "mail.php";
 
     $type_of_user = 0;
     if (isset($_GET["op"])) $type_of_user = $_GET["op"];
 
     $error_message = ""; // Initialize error message variable
 
     if (!empty($_POST)) {
         extract($_POST);
         
         // Check for empty fields
        if (($type_of_user == 0) && (empty($fname) || empty($lname)|| empty($email) || empty($password) || empty($city) || empty($district) || empty($address))){
                $error_message = "Please fill in all fields.";
        }
         
        else if(($type_of_user == 1) && (empty($marketname) || empty($email) || empty($password) || empty($city) || empty($district) || empty($address))){
                $error_message = "Please fill in all fields.";
        }
         
        else {
 
             // Process form submission
             if (1 || ($fname == "TEST" || $marketname == "TEST")) {
                 // Hash the password
                 $hashed_password = password_hash($password, PASSWORD_BCRYPT);
 
                 $new_user = [
                     'fname' => $fname,
                     'lname' => $lname,
                     'marketname' => $marketname,
                     'email' => $email,
                     'password' => $hashed_password, // Store hashed password
                     'city' => $city,
                     'district' => $district,
                     'address' => $address,
                     'type_of_user' => $type_of_user
                 ];
                 $_SESSION['code'] = rand(100000, 999999);
                 $_SESSION['new_user'] = $new_user;
                 Mail::send($email, "TEST", $_SESSION["code"]);
                 header("Location: confirm_code.php");
                 exit;
             } else {
                 $error_message = 'Make the name = "TEST" to go to the confirmation page';
             }
         }
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="register.css">
    <style>
        .error{
            color:red;
            margin: auto;
            padding: 0;
        }
    </style>

</head>
<body>
    <h1 class="title">Create an Account</h1>
    <div id="registerdiv" class="box">
        <?php if (!empty($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post"  onsubmit="return validateForm()">
            <div id="opt">
                <a href="register.php?op=0" id="consumer" class="<?= $type_of_user == 0 ? 'chosen' : 'normal'; ?>">Consumer</a>
                <a href="register.php?op=1" id="market" class="<?= $type_of_user == 1 ? 'chosen' : 'normal'; ?>">Market</a>
            </div>
            <input type="text" class="input" name="fname" id="fname" placeholder="First Name" style="display: <?= $type_of_user == 0 ? 'block' : 'none'; ?>" value="<?= isset($_POST['fname']) ? $_POST['fname'] : ''; ?>">
            <input type="text" class="input" name="lname" id="lname" placeholder="Last Name" style="display: <?= $type_of_user == 0 ? 'block' : 'none'; ?>" value="<?= isset($_POST['lname']) ? $_POST['lname'] : ''; ?>">
            <input type="text" class="input" name="marketname" id="marketname" placeholder="Market Name" style="display: <?= $type_of_user == 0 ? 'none' : 'block'; ?>" value="<?= isset($_POST['marketname']) ? $_POST['marketname'] : ''; ?>">
            <input type="text" class="input" name="email" id="email" placeholder="E-mail" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            <input type="password" class="input" name="password" id="password" placeholder="Password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>">
            <div id="city">
                <input type="text" class="input2" name="city" id="" placeholder="City" value="<?= isset($_POST['city']) ? $_POST['city'] : ''; ?>">
                <input type="text" class="input2" name="district" id="" placeholder="District" value="<?= isset($_POST['district']) ? $_POST['district'] : ''; ?>">
            </div>
            <input type="text" class="input" name="address" id="address" placeholder="Address" value="<?= isset($_POST['address']) ? $_POST['address'] : ''; ?>">
            <button type="submit" class="btn">Create Account</button>
            <p id="p">Already have an account? <a href="login.php"> Sign in</a></p>
        </form>
     </div>
</body>
</html>
