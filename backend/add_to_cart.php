<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "login";
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo "error";
    exit;
}

$check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id='$user_id' AND product_id='$product_id' LIMIT 1");

if (!$check) {
    echo "error";
    exit;
}

if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    $newQty = (int)$row['quantity'] + 1;

    $update = mysqli_query($conn, "UPDATE cart SET quantity='$newQty' WHERE id='".$row['id']."'");
    echo $update ? "updated" : "error";
} else {
    $insert = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)");
    echo $insert ? "added" : "error";
}
?>