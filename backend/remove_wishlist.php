<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$product_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($product_id <= 0) {
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../frontend/wishlist.php';
    header("Location: " . $redirect);
    exit;
}

$deleteQuery = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
$deleteResult = mysqli_query($conn, $deleteQuery);

if (!$deleteResult) {
    die("Delete Error: " . mysqli_error($conn));
}

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../frontend/wishlist.php';
header("Location: " . $redirect);
exit;
?>