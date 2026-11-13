<?php
session_start();
$host = 'localhost';
$db = 'event_management';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    die('Database connection failed. Start MySQL and import database/event_management.sql.');
}
function e($v)
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
function go($u)
{
    header("Location: $u");
    exit;
}
function logged()
{
    return isset($_SESSION['user']);
}
function login_required()
{
    if (!logged())
        go('../login.php');
}
function role_required($r)
{
    login_required();
    if ($_SESSION['user']['role'] !== $r)
        go('../index.html');
}
function uid()
{
    return (int) $_SESSION['user']['id'];
}
function notify($pdo, $uid, $title, $message)
{
    $s = $pdo->prepare('INSERT INTO notifications(user_id,title,message) VALUES(?,?,?)');
    $s->execute([$uid, $title, $message]);
}
function header_page($title, $base = '../')
{
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . e($title) . ' | Evently</title><link rel="stylesheet" href="' . $base . 'css/style.css"></head><body><header class="nav"><a class="logo" href="' . $base . 'index.html"><b>E</b> Evently</a><nav><a href="' . $base . 'index.html">Home</a>';
    if (logged()) {
        echo '<a href="' . $base . strtolower($_SESSION['user']['role']) . '/dashboard.php">Dashboard</a>';
    }
    echo '</nav><div>';
    if (logged())
        echo '<span>' . e($_SESSION['user']['name']) . '</span> <a class="btn outline" href="' . $base . 'logout.php">Logout</a>';
    else
        echo '<a class="btn outline" href="' . $base . 'login.php">Login</a> <a class="btn" href="' . $base . 'register.php">Register</a>';
    echo '</div></header><main class="wrap">';
}
function footer_page($base = '../')
{
    echo '</main><footer> Evently - The Event Management System  © 2026 </footer><script src="' . $base . 'js/script.js"></script></body></html>';
}
?>