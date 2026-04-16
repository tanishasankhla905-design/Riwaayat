<?php
session_start();
include '../db.php';

$currentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($currentId <= 0) {
    die("Invalid product.");
}

$productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = '$currentId' LIMIT 1");

if (!$productQuery) {
    die("Query Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($productQuery) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($productQuery);

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$wishlistAdded = false;

if ($user_id > 0) {
    $checkWishlistQuery = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = '$user_id' AND product_id = '$currentId' LIMIT 1");

    if ($checkWishlistQuery && mysqli_num_rows($checkWishlistQuery) > 0) {
        $wishlistAdded = true;
    }
}
/* similar products */
$poshakCategory = mysqli_real_escape_string($conn, $product['category']);

if (!empty($poshakCategory)) {
    $similarQuery = "SELECT * FROM products 
                     WHERE category = '$poshakCategory'
                     AND id != '$currentId'
                     ORDER BY id DESC
                     LIMIT 4";
} else {
    $similarQuery = "SELECT * FROM products 
                     WHERE id != '$currentId'
                     ORDER BY id DESC
                     LIMIT 4";
}

$similarResult = mysqli_query($conn, $similarQuery);

if (!$similarResult) {
    die("Similar Query Error: " . mysqli_error($conn));
}

/* reviews */
$reviews = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 3");

if (!$reviews) {
    die("Review Query Error: " . mysqli_error($conn));
}
?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>
<style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #fdf6f0;
    color: #2b1e12;
}

a {
    text-decoration: none;
    color: inherit;
}

.product-page {
    width: 100%;
    padding: 40px 20px 60px;
}

.product-top {
    max-width: 1200px;
    margin: 0 auto 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: start;
}

.product-left {
    position: relative;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.main-img {
    width: 100%;
    height: 650px;
    object-fit: cover;
    object-position: top;
    display: block;
}

.wishlist-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 42px;
    height: 42px;
    border: none;
    background: #fff;
    color: #222;
    font-size: 24px;
    line-height: 42px;
    text-align: center;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 6px 14px rgba(0,0,0,0.14);
    transition: 0.3s ease;
}

.wishlist-btn:hover {
    transform: scale(1.08);
}

.wishlist-btn.active {
    color: red;
}

.product-right {
    background: #fff;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
}

.product-right h2 {
    font-size: 34px;
    line-height: 1.3;
    margin-bottom: 14px;
    font-family: 'Playfair Display', serif;
    color: #2b1e12;
}

.product-right .price {
    font-size: 30px;
    font-weight: 700;
    color: #b89232;
    margin-bottom: 18px;
}

.product-right p {
    font-size: 15px;
    line-height: 1.8;
    color: #5c4a37;
    margin-bottom: 12px;
}

.product-right .tax {
    font-size: 14px;
    color: #7b6854;
    margin-top: 10px;
    margin-bottom: 18px;
}

.product-right hr {
    border: none;
    border-top: 1px solid #eadfce;
    margin: 22px 0;
}

.product-right b {
    color: #2f2418;
}

.offer {
    margin-top: 18px;
    background: #fff8ea;
    color: #8a6411 !important;
    border: 1px solid #ecd59d;
    padding: 12px 14px;
    border-radius: 10px;
    font-weight: 600;
}

.action-btns {
    display: flex;
    gap: 14px;
    margin-top: 24px;
    margin-bottom: 16px;
}

.action-btns button {
    flex: 1;
    height: 48px;
    border: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    transition: 0.3s ease;
}

.cart {
    background: transparent;
    border: 1px solid #000 !important;
    color: #000;
}

.cart:hover {
    background: #000;
    color: #fff;
}

.buy {
    background: linear-gradient(45deg, #b89232, #d4af37);
    color: #fff;
}

.buy:hover {
    opacity: 0.9;
}

.payment {
    font-size: 14px;
    color: #7a6754;
    margin-bottom: 0 !important;
}

.reviews,
.similar {
    max-width: 1200px;
    margin: 0 auto 55px;
    background: #fff;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

.reviews h3,
.similar h3 {
    font-size: 28px;
    margin-bottom: 22px;
    font-family: 'Playfair Display', serif;
    color: #2b1e12;
    text-align: center;
}

.review-card {
    background: #faf6f0;
    border: 1px solid #eee1d3;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 16px;
}

.review-card h4 {
    font-size: 17px;
    margin-bottom: 8px;
    color: #2f2418;
}

.review-card p {
    font-size: 15px;
    line-height: 1.7;
    color: #5d4d3d;
}

.similar-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.similar-link {
    display: block;
}

.card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
    transition: 0.3s ease;
    height: 100%;
}

.card:hover {
    transform: translateY(-6px);
}

.card img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    object-position: top;
    display: block;
}

.card h4 {
    font-size: 16px;
    padding: 14px 14px 6px;
    color: #2f2418;
    text-align: center;
}

.card p {
    font-size: 16px;
    font-weight: 600;
    color: #b89232;
    text-align: center;
    padding: 0 14px 16px;
}

@media (max-width: 1100px) {
    .product-top {
        grid-template-columns: 1fr;
    }

    .main-img {
        height: 560px;
    }

    .similar-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .product-page {
        padding: 25px 15px 40px;
    }

    .product-top {
        gap: 25px;
        margin-bottom: 35px;
    }

    .product-right {
        padding: 24px 18px;
    }

    .product-right h2 {
        font-size: 28px;
    }

    .product-right .price {
        font-size: 25px;
    }

    .main-img {
        height: 430px;
    }

    .reviews,
    .similar {
        padding: 22px 16px;
        margin-bottom: 35px;
    }

    .similar-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .card img {
        height: 220px;
    }
}

@media (max-width: 576px) {
    .action-btns {
        flex-direction: column;
    }

    .product-right h2 {
        font-size: 24px;
    }

    .main-img {
        height: 340px;
    }

    .reviews h3,
    .similar h3 {
        font-size: 24px;
    }

    .similar-grid {
        grid-template-columns: 1fr;
    }
}</style>
<div class="product-page">

    <!-- TOP SECTION -->
    <div class="product-top">

        <!-- LEFT IMAGE -->
        <div class="product-left">
            <button type="button" class="wishlist-btn <?php echo $wishlistAdded ? 'active' : ''; ?>"
                data-id="<?php echo $product['id']; ?>">
                <?php echo $wishlistAdded ? '♥' : '♡'; ?>
            </button>

            <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" class="main-img"
                alt="<?php echo htmlspecialchars($product['title']); ?>">
        </div>

        <!-- RIGHT DETAILS -->
        <div class="product-right">

            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>

            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <p class="tax">Inclusive of all taxes • Shipping calculated at checkout</p>

            <hr>

            <p><b>Color:</b> <?php echo htmlspecialchars($product['color']); ?></p>
            <p><b>Fabric:</b> <?php echo htmlspecialchars($product['fabric']); ?></p>
            <p><b>Pattern:</b> <?php echo htmlspecialchars($product['pattern']); ?></p>
            <p><b>Work:</b> <?php echo htmlspecialchars($product['work_type']); ?></p>

            <p><b>Inclusions:</b> 1 Odhna, 1 Kurti, 1 Kanchali, 1 Lehenga</p>

            <p class="offer">🔥 FLAT ₹500 OFF on first order</p>

            <!-- BUTTONS -->
            <div class="action-btns">
                <button class="cart">Add to Cart</button>
                <button class="buy">Buy Now</button>


            </div>

            <p class="payment">All payment modes & COD available</p>

        </div>
    </div>

    <!-- REVIEWS -->
    <div class="reviews">
        <h3>Customer Reviews</h3>

        <?php if (mysqli_num_rows($reviews) > 0) { ?>
        <?php while ($r = mysqli_fetch_assoc($reviews)) { ?>
        <div class="review-card">
            <h4><?php echo htmlspecialchars($r['name']); ?></h4>
            <p><?php echo htmlspecialchars($r['message']); ?></p>
        </div>
        <?php } ?>
        <?php } else { ?>
        <p>No reviews found.</p>
        <?php } ?>
    </div>

   <div class="similar">
    <h3>You may also like</h3>

    <div class="similar-grid">
        <?php if ($similarResult && mysqli_num_rows($similarResult) > 0) { ?>
            <?php while ($s = mysqli_fetch_assoc($similarResult)) { ?>
                
                <a href="product.php?id=<?php echo $s['id']; ?>" class="similar-link">
                    <div class="card">
                        <img src="../images/<?php echo htmlspecialchars($s['image']); ?>"
                             alt="<?php echo htmlspecialchars($s['title']); ?>">
                        <h4><?php echo htmlspecialchars($s['title']); ?></h4>
                        <p>₹<?php echo htmlspecialchars($s['price']); ?></p>
                    </div>
                </a>

            <?php } ?>
        <?php } else { ?>
            <p>No similar products found.</p>
        <?php } ?>
    </div>

</div>
<?php include "footer.php"; ?>