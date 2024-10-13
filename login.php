<?php
     session_start();
     $fail = false;
     require_once "db.php" ;
     if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
     if ( !empty($_POST)) {
          extract($_POST) ;
          if ( checkUser($email, $password, $user) ) {

               if ( isset($remember)) {
                    $token = sha1(uniqid() . "Private Key is Here" . time() ) ; // generate a random text
                    setcookie("access_token", $token, time() + 60*60*24*365*10, "/", "", false, true) ; // for 10 years, httponly flag for security
                    setTokenByEmail($email, $token) ;
               }
               
               // login as $user
               $_SESSION["user"] = $user;
               
               if ($user["type_of_user"] == '1') {
                    header("Location: seller.php");
                } else {
                    header("Location: index.php");
                }
                exit;
          }
          else { 
               var_dump($qqq);
               var_dump($password);
               $fail = true  ; }
     }

     if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_COOKIE["access_token"])) {
          $user = getUserByToken($_COOKIE["access_token"]) ;
          if ( $user ) {
               $_SESSION["user"] = $user ; // auto login
               header("Location: index.php") ;
               exit ; 
          }
     }
 
  if ( $_SERVER["REQUEST_METHOD"] == "GET" && isAuthenticated()) {
      header("Location: seller.php") ; // auto login
      exit ;
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
          span{ color: #DC9D23;}
          .checkbox-container {
               display: flex;
               /* margin-top: 20px; */
               margin-left: 50px;
               width: 150px;
          }
          .checkbox-label {
               text-align: left;
               text-indent: 10px;
          }
          #p{
               margin: 20px 190px;
          }
     </style>
</head>
<body>
     <h1 class="title"><span>Bil</span>Grocer</h1>
     <div id="logindiv" class="box">
          <form method="post">
               <br>
               <input type="text" class="input" name="email" placeholder="E-MAIL" value="<?= isset($_POST['email'])? htmlspecialchars($_POST['email'], ENT_QUOTES):''?>"> 
               <input type="password" class="input" name="password" placeholder="PASSWORD" value="<?= isset($_POST['password'])? htmlspecialchars($_POST['password'], ENT_QUOTES):''?>"> 
               <div class="checkbox-container">
                    <div id="a">
                         <input type="checkbox" id="remember" name="remember" style="margin-right: 5px;"> 
                    </div>
                    <div class="checkbox-label"><label for="remember" >Remember me</label></div>
               </div>
               <?php
                    if ($fail) {
                         echo '<p style="color: red;">Failed to log in</p>';
                    }
               ?>
               <button type="submit" class="btn">LOG IN</button>
               <p id="p">New to BilGrocer? <a href="register.php">  Join now</a></p>
          </form>
     </div>
</body>
</html>
