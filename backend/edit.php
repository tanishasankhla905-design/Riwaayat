<?php
include 'admin-check.php';
include '../db.php';

$message = "";

// allowed return pages
$allowedPages = ['products.php', 'poshaks.php', 'sarees.php', 'jewellery.php'];
$from = 'products.php';

if (isset($_GET['from']) && in_array($_GET['from'], $allowedPages)) {
    $from = $_GET['from'];
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: $from");
    exit;
}

$id = (int) $_GET['id'];

$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);

if (isset($_POST['update_product'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $fabric = mysqli_real_escape_string($conn, $_POST['fabric']);
    $pattern = mysqli_real_escape_string($conn, $_POST['pattern']);
    $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $newImage = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];

    if (!empty($newImage)) {
        move_uploaded_file($tmpName, "../images/" . $newImage);
        $imagePart = ", image='$newImage'";
    } else {
        $imagePart = "";
    }

    $updateQuery = "UPDATE products SET
        title='$title',
        price='$price',
        category='$category',
        type='$type',
        color='$color',
        fabric='$fabric',
        pattern='$pattern',
        work_type='$work_type',
        description='$description'
        $imagePart
        WHERE id=$id";

    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        header("Location: $from?updated=1");
        exit;
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Riwaayat Admin</title>
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

        .page {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .form-box {
            background: #fff;
            border-radius: 26px;
            padding: 35px;
            box-shadow: 0 14px 35px rgba(120, 90, 30, 0.10);
            border: 1px solid #f1e5d4;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .top-bar h1 {
            color: #7a5a11;
            font-size: 32px;
        }

        .top-bar p {
            color: #7b6d5e;
            margin-top: 6px;
            font-size: 14px;
        }

        .message {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 12px;
            background: #ffe8e8;
            color: #c0392b;
            font-weight: 600;
            border: 1px solid #f2c9c9;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #5e5348;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #ddd7cf;
            border-radius: 14px;
            font-size: 14px;
            outline: none;
            background: #fffdfb;
            transition: 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #b89232;
            box-shadow: 0 0 0 4px rgba(184, 146, 50, 0.10);
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .upload-box {
            border: 1.5px dashed #d4b06a;
            border-radius: 18px;
            padding: 20px;
            background: #fffaf3;
        }

        .upload-box input[type="file"] {
            background: transparent;
            border: none;
            padding: 0;
            box-shadow: none;
        }

        .hint {
            font-size: 12px;
            color: #8a7e70;
            margin-top: 8px;
        }

        .current-img {
            margin-top: 12px;
        }

        .current-img p {
            margin-bottom: 10px;
            font-size: 13px;
            color: #7b6d5e;
            font-weight: 600;
        }

        .current-img img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid #eadfce;
            display: block;
        }

        .btn-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 13px 22px;
            border: none;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s ease;
        }

        .update-btn {
            background: linear-gradient(135deg, #b89232, #8e6b13);
            color: #fff;
            box-shadow: 0 10px 24px rgba(184, 146, 50, 0.22);
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(184, 146, 50, 0.28);
        }

        .back-btn {
            background: #f5ede3;
            color: #7a5a11;
        }

        .back-btn:hover {
            background: #efe3d4;
        }

        .section-title {
            font-size: 18px;
            color: #7a5a11;
            margin-bottom: 14px;
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            .form-box {
                padding: 22px;
            }

            .top-bar h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="form-box">

        <div class="top-bar">
            <div>
                <h1>Edit Product</h1>
                <p>Update your Riwaayat product details.</p>
            </div>
            <a href="<?php echo $from; ?>" class="btn back-btn">← Back to Products</a>
        </div>

        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="section-title">Basic Details</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Product Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="poshak" <?php if(($product['category'] ?? '') == 'poshak') echo 'selected'; ?>>Poshak</option>
                        <option value="saree" <?php if(($product['category'] ?? '') == 'saree') echo 'selected'; ?>>Saree</option>
                        <option value="jewellery" <?php if(($product['category'] ?? '') == 'jewellery') echo 'selected'; ?>>Jewellery</option>
                        <option value="bridal" <?php if(($product['category'] ?? '') == 'bridal') echo 'selected'; ?>>Bridal</option>
                        <option value="fagun" <?php if(($product['category'] ?? '') == 'fagun') echo 'selected'; ?>>Fagun</option>
                        <option value="lehariya" <?php if(($product['category'] ?? '') == 'lehariya') echo 'selected'; ?>>Lehariya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type" required>
                        <option value="poshaks" <?php if(($product['type'] ?? '') == 'poshaks') echo 'selected'; ?>>Poshaks</option>
                        <option value="sarees" <?php if(($product['type'] ?? '') == 'sarees') echo 'selected'; ?>>Sarees</option>
                        <option value="jewellery" <?php if(($product['type'] ?? '') == 'jewellery') echo 'selected'; ?>>Jewellery</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Color</label>
                    <input type="text" name="color" value="<?php echo htmlspecialchars($product['color'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Fabric</label>
                    <input type="text" name="fabric" value="<?php echo htmlspecialchars($product['fabric'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Pattern</label>
                    <input type="text" name="pattern" value="<?php echo htmlspecialchars($product['pattern'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Work Type</label>
                    <input type="text" name="work_type" value="<?php echo htmlspecialchars($product['work_type'] ?? ''); ?>">
                </div>

                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group full">
                    <label>Upload New Image</label>
                    <div class="upload-box">
                        <input type="file" name="image">
                        <p class="hint">Leave empty if you don’t want to change the image.</p>

                        <div class="current-img">
                            <p>Current Image:</p>
                            <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" name="update_product" class="btn update-btn">Update Product</button>
                <a href="<?php echo $from; ?>" class="btn back-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>