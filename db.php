<?php
const DSN = "mysql:host=localhost;dbname=UsersDB;charset=utf8mb4";
const USER = "root";
const DBPASSWORD = "";

try {
    $db = new PDO(DSN, USER, DBPASSWORD);
} catch(PDOException $e) {
    echo "Set username and password in 'db.php' appropriately";
    exit;
}

function markExpiredProducts() {
    global $db;
    $sql = "UPDATE products SET status = 'expired' WHERE expiration_date < NOW()";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}

// function checkUser($email, $password, &$user) {
//     global $db;
//     $stmt = $db->prepare("SELECT * FROM users WHERE user_email = ?");
//     $stmt->execute([$email]);
//     $user = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($user && password_verify($password, $user['user_password'])) {
//         return $user;
//     }
//     return false;
// }

function checkUser($email, $pass, &$user) {
    global $db;

    $stmt = $db->prepare("select * from users where user_email = :user_email");
    $stmt->execute(['user_email'=>$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $user ) {
         return password_verify($pass, $user["user_password"]);
    }
    return false ;
}
function password_verify1($pass1, $pass2) {
    return $pass1 == $pass2;
}

function setTokenByEmail($email, $token) {
    global $db;
    $stmt = $db->prepare("UPDATE users SET user_session_token = :user_session_token WHERE user_email = :user_email");
    $stmt->execute(['user_session_token'=>$token, 'user_email'=>$email]);
}

function getUserByToken($token) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE user_session_token = :user_session_token");
    $stmt->execute(['user_session_token'=>$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function isAuthenticated() {
    return isset($_SESSION["user"]);
}
function getProducts($city, $search, $offset = 0, $limit = 4) {
    global $db;
    if ($city != "") {
        $stmt = $db->prepare("SELECT p.*, u.user_district, expiration_date
                              FROM products p
                              JOIN users u ON p.user_id = u.user_id
                              WHERE u.user_city = ? AND p.title LIKE ?
                              LIMIT ?, ?");
        $stmt->bindValue(1, $city);
        $stmt->bindValue(2, "%$search%");
        $stmt->bindValue(3, (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(4, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $stmt = $db->prepare("SELECT p.*, u.user_district, expiration_date
                              FROM products p
                              JOIN users u ON p.user_id = u.user_id
                              WHERE p.title LIKE ?
                              LIMIT ?, ?");
        $stmt->bindValue(1, "%$search%");
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(3, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getUserById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id'=>$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addUser($user) {
    global $db;
    $stmt = $db->prepare("INSERT INTO users (
                          first_name, last_name, market_name, user_email, user_password, user_city, user_district, user_address, type_of_user
                          ) VALUES (:first_name, :last_name, :market_name, :user_email, :user_password, :user_city, :user_district, :user_address, :type_of_user)");
    $stmt->execute([
        'first_name'=>$user['fname'],
        'last_name'=>$user['lname'],
        'market_name'=>$user['marketname'],
        'user_email'=>$user['email'],
        'user_password'=>$user['password'],
        'user_city'=>$user['city'],
        'user_district'=>$user['district'],
        'user_address'=>$user['address'],
        'type_of_user'=>$user['type_of_user'],
    ]);
}

function addProduct($product) {
    global $db;
    $stmt = $db->prepare("INSERT INTO products (
                          title, stock, normal_price, discounted_price, expiration_date, image_url, category, user_id
                          ) VALUES (:title, :stock, :normal_price, :discounted_price, :expiration_date, :image_url, :category, :user_id)");
    $stmt->execute([
        'title'=>$product['title'],
        'stock'=>$product['stock'],
        'normal_price'=>$product['normal_price'],
        'discounted_price'=>$product['discounted_price'],
        'expiration_date'=>$product['expiration_date'],
        'image_url'=>$product['image_url'],
        'category'=>$product['category'],
        'user_id'=>$product['user_id'],
    ]);
}

function getProductById($product_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function asd($id) {
    global $db;
    $hashed_password = password_hash("123", PASSWORD_BCRYPT);
    $stmt = $db->prepare('UPDATE users SET user_password = ? WHERE user_id = ?');
    $stmt->execute([$hashed_password, $id]);
    // if () {
    //    // echo "User registered successfully.";
    // } else {
    //     //echo "Failed to register user.";
    // }
}
function getTotal($city, $search) {
    global $db;
    if($city != "") { 
        $stmt = $db->prepare("SELECT COUNT(*) as total
                              FROM products p
                              JOIN users u ON p.user_id = u.user_id
                              WHERE u.user_city = ? AND p.title LIKE ?");
        $stmt->execute([$city, "%$search%"]);
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) as total
                              FROM products p
                              JOIN users u ON p.user_id = u.user_id
                              WHERE p.title LIKE ?");
        $stmt->execute(["%$search%"]);
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}
