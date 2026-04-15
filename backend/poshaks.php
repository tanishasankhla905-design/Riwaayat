<?php
include 'admin-check.php';
include '../db.php';

$query = "SELECT * FROM products Where type='poshaks' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Poshaks - Riwaayat Admin</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background: linear-gradient(135deg, #fdf8f3, #f6efe6);
        color: #2f2a24;
    }

    /* MAIN LAYOUT */
    .admin-layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    /* SIDEBAR */
    .sidebar {
        width: 290px;
        flex-shrink: 0;
        background: linear-gradient(180deg, #1f1a15, #2c241d);
        color: #fff;
        padding: 28px 20px;
        position: sticky;
        top: 0;
        height: 100vh;
        border-right: 1px solid rgba(255, 255, 255, 0.06);
    }

    .brand {
        text-align: center;
        margin-bottom: 35px;
        padding-bottom: 22px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .brand h2 {
        color: #d2a63b;
        font-size: 28px;
        letter-spacing: 1px;
        margin-bottom: 8px;
        font-family: Georgia, serif;
    }

    .brand p {
        color: #c9b89a;
        font-size: 13px;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .menu a {
        text-decoration: none;
        color: #f4eee7;
        padding: 14px 16px;
        border-radius: 14px;
        transition: 0.3s ease;
        font-size: 15px;
        background: rgba(255, 255, 255, 0.03);
    }

    .menu a:hover,
    .menu a.active {
        background: linear-gradient(135deg, #b89232, #8e6b13);
        color: #fff;
        transform: translateX(4px);
    }

    /* MAIN CONTENT */
    .main {
        flex: 1;
        width: 100%;
        max-width: 100%;
        padding: 35px 40px;
    }

    .page {
        width: 100%;
        max-width: 100%;
        padding: 0;
    }

    /* TOP BAR */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
        flex-wrap: wrap;
        width: 100%;
    }

    .top-bar h1 {
        color: #7a5a11;
        font-size: 32px;
        font-weight: 700;
    }

    .add-btn {
        text-decoration: none;
        background: linear-gradient(135deg, #b89232, #8e6b13);
        color: #fff;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        display: inline-block;
        transition: 0.3s ease;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(184, 146, 50, 0.25);
    }

    /* TABLE BOX */
    .table-box {
        width: 100%;
        max-width: 100%;
        background: #fff;
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(120, 90, 30, 0.08);
        overflow-x: auto;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    th,
    td {
        padding: 14px 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        white-space: nowrap;
    }

    th {
        background: #fcf8f3;
        color: #7a5a11;
        font-size: 15px;
        font-weight: 700;
    }

    td {
        color: #4d4339;
        font-size: 14px;
    }

    /* IMAGE */
    .product-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    /* CATEGORY TAG */
    .tag {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: #f7efe4;
        color: #7a5a11;
        font-size: 12px;
        font-weight: 600;
    }

    /* ACTION BUTTONS */
    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
        transition: 0.3s ease;
    }

    .edit-btn {
        background: #f5ede3;
        color: #7a5a11;
    }

    .delete-btn {
        background: #ffe5e5;
        color: #c0392b;
    }

    .edit-btn:hover,
    .delete-btn:hover {
        transform: translateY(-2px);
    }

    /* EMPTY ROW */
    .empty {
        text-align: center;
        padding: 25px;
        color: #777;
    }

    /* BACK BUTTON */
    .back-btn {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #7a5a11;
        font-weight: 600;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .main {
            padding: 25px;
        }
    }

    @media (max-width: 900px) {
        .admin-layout {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .main {
            width: 100%;
            padding: 20px;
        }

        .top-bar {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 600px) {
        .menu {
            grid-template-columns: 1fr;
        }

        .top-bar h1 {
            font-size: 26px;
        }

        .add-btn {
            width: 100%;
            text-align: center;
        }

        .main {
            padding: 15px;
        }

        .table-box {
            padding: 14px;
        }
    }
    </style>
</head>

<body>

    <?php include('includes/header.php'); ?>

    <div class="admin-layout">

        <?php include('includes/sidebar.php'); ?>

        <main class="main">

            <div class="page">
                <div class="top-bar">
                    <h1>Manage Poshaks</h1>
                    <a href="add.php" class="add-btn">+ Add New Poshak</a>
                </div>

                <div class="table-box">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Color</th>
                            <th>Fabric</th>
                            <th>Pattern</th>
                            <th>Actions</th>
                        </tr>

                        <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" class="product-img"
                                    alt="">
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                            <td><span class="tag"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td><span class="tag"><?php echo htmlspecialchars($row['type']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['color']); ?></td>
                            <td><?php echo htmlspecialchars($row['fabric']); ?></td>
                            <td><?php echo htmlspecialchars($row['pattern']); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>&from=poshaks.php"
                                        class="btn edit-btn">Edit</a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>&from=poshaks.php"
                                        class="btn delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td colspan="9" class="empty">No poshak products found.</td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>


        </main>

    </div>

    <?php include('includes/footer.php'); ?>

</html>