<?php
include 'admin-check.php';
include '../db.php';

// allowed return pages
$allowedPages = ['products.php', 'poshaks.php', 'sarees.php', 'jewellery.php'];
$from = 'products.php';

// validate return page
if (isset($_GET['from']) && in_array($_GET['from'], $allowedPages)) {
    $from = $_GET['from'];
}

// check id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: $from");
    exit;
}

$id = (int) $_GET['id'];

// OPTIONAL: image delete (best practice)
$getImage = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");
if ($getImage && mysqli_num_rows($getImage) > 0) {
    $imgData = mysqli_fetch_assoc($getImage);
    $imgPath = "../images/" . $imgData['image'];

    if (!empty($imgData['image']) && file_exists($imgPath)) {
        unlink($imgPath); // delete file
    }
}

// delete query
$deleteQuery = "DELETE FROM products WHERE id = $id";
$result = mysqli_query($conn, $deleteQuery);

if ($result) {
    header("Location: $from?deleted=1");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>