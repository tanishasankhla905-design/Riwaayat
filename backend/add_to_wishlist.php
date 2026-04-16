<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "login";
    exit;
}

$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$user_id = (int) $_SESSION['user_id'];

if ($product_id <= 0) {
    echo "error";
    exit;
}

$checkQuery = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id' LIMIT 1");

if (!$checkQuery) {
    echo "error";
    exit;
}

if (mysqli_num_rows($checkQuery) > 0) {
    $deleteQuery = mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");

    if ($deleteQuery) {
        echo "removed";
    } else {
        echo "error";
    }
} else {
    $insertQuery = mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')");

    if ($insertQuery) {
        echo "added";
    } else {
        echo "error";
    }
}
?>