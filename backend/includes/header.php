<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwaayat Admin</title>

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-layout">