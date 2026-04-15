<?php
include '../db.php';

$occasion = isset($_GET['occasion']) ? trim($_GET['occasion']) : '';
$occasionLower = strtolower($occasion);

$query = "";

if ($occasionLower == 'haldi') {
    $query = "SELECT * FROM products 
              WHERE color LIKE '%yellow%' 
                 OR color LIKE '%mustard%' 
                 OR color LIKE '%golden%'
                               AND type = 'poshaks'

              ORDER BY id DESC";

} elseif ($occasionLower == 'mehendi' || $occasionLower == 'mehndi') {
    $query = "SELECT * FROM products 
              WHERE color LIKE '%green%' 
                            AND type = 'poshaks'

              ORDER BY id DESC";

} elseif ($occasionLower == 'wedding' || $occasionLower == 'bridal') {
    $query = "SELECT * FROM products 
              WHERE (color LIKE '%red%' 
                 OR color LIKE '%maroon%' 
                 OR color LIKE '%ruby%')
              AND type = 'poshaks'
              ORDER BY id DESC";

} elseif ($occasionLower == 'reception') {
    $query = "SELECT * FROM products 
              WHERE color LIKE '%gold%' 
                 OR color LIKE '%beige%' 
                 OR color LIKE '%cream%' 
                 OR color LIKE '%silver%'
                 OR color LIKE '%pink%'
                 OR color LIKE '%lavender%'
                AND type = 'poshaks'
              ORDER BY id DESC";

} elseif ($occasionLower == 'sangeet') {
    $query = "SELECT * FROM products 
              WHERE color LIKE '%blue%' 
                 OR color LIKE '%pink%' 
                 OR color LIKE '%purple%' 
                 OR color LIKE '%green%' 
                   OR color LIKE '%peacock%' 
                    OR color LIKE '%lavender%' 
                 OR color LIKE '%gold%'
                   AND type = 'poshaks'
              ORDER BY id ASC";

} 

 else {
    $query = "SELECT * FROM products ORDER BY id DESC";
}

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
    <title><?php echo htmlspecialchars($occasion); ?> Collection</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fffaf6;
    }

    .occasion-products-page {
        width: 100%;
        padding: 50px 6%;
    }

    .occasion-page-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .occasion-page-header h1 {
        font-size: 48px;
        color: #4e2e24;
        font-family: "Playfair Display", serif;
        margin-bottom: 10px;
    }

    .occasion-page-header p {
        font-size: 16px;
        color: #7a6153;
        margin: 0;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    .product-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        transition: 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-6px);
    }

    .product-card img {
        width: 100%;
        height: 340px;
        object-fit: cover;
        object-position: top;
        display: block;
    }

    .product-info {
        padding: 16px;
    }

    .product-info h3 {
        font-size: 20px;
        color: #4e2e24;
        margin: 0 0 8px;
        font-family: "Playfair Display", serif;
    }

    .product-info p {
        margin: 4px 0;
        color: #6c5145;
        font-size: 15px;
    }

    .no-products {
        text-align: center;
        font-size: 20px;
        color: #7a5b4b;
        padding: 40px 0;
    }

    @media (max-width: 1100px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .occasion-page-header h1 {
            font-size: 34px;
        }

        .product-card img {
            height: 260px;
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>

    <section class="occasion-products-page">
        <div class="occasion-page-header">
            <h1><?php echo htmlspecialchars($occasion); ?> Collection</h1>
            <p>Explore styles curated for your special celebration.</p>
        </div>

        <?php if (mysqli_num_rows($result) > 0) { ?>
        <div class="product-grid">
            <?php while($product = mysqli_fetch_assoc($result)) { ?>
            <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
                <img src="/meera/images/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['title']); ?>">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p>₹<?php echo htmlspecialchars($product['price']); ?></p>
                    <p>Color: <?php echo htmlspecialchars($product['color']); ?></p>
                </div>
            </a>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="no-products">No products found for this occasion.</div>
        <?php } ?>
    </section>

</body>

</html>