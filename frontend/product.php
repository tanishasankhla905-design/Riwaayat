<?php
session_start();
include '../db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid product ID");
}

$query = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("Product not found");
}

$product = mysqli_fetch_assoc($result);

/* current product info */
$currentCategory = mysqli_real_escape_string($conn, $product['category']);
$currentId = (int)$product['id'];

/* similar products */
$similarQuery = "SELECT * FROM products 
                 WHERE category = '$currentCategory'
                 AND id != $currentId
                 ORDER BY id DESC
                 LIMIT 4";

$similarResult = mysqli_query($conn, $similarQuery);

if (!$similarResult) {
    die("Similar Query Error: " . mysqli_error($conn));
}

/* reviews */
$reviews = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 3");

if (!$reviews) {
    die("Review Query Error: " . mysqli_error($conn));
}

/* wishlist logic */
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$wishlistAdded = false;

if ($user_id > 0) {
    $checkWishlistQuery = "SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $currentId LIMIT 1";
    $checkWishlistResult = mysqli_query($conn, $checkWishlistQuery);

    if ($checkWishlistResult && mysqli_num_rows($checkWishlistResult) > 0) {
        $wishlistAdded = true;
    }
}
?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<div class="product-page">

    <!-- TOP SECTION -->
    <div class="product-top">

        <!-- LEFT IMAGE -->
        <div class="product-left">
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

                <?php if ($user_id > 0) { ?>
                <a href="../backend/add_to_wishlist.php?id=<?php echo $product['id']; ?>"
                    class="wishlist-btn <?php echo $wishlistAdded ? 'active' : ''; ?>">
                    <?php echo $wishlistAdded ? 'Wishlisted' : 'Add to Wishlist'; ?>
                </a>
                <?php } else { ?>
                <a href="login.php?redirect=<?php echo urlencode('../backend/add_to_wishlist.php?id=' . $product['id']); ?>"
                    class="wishlist-btn">
                    Add to Wishlist
                </a>
                <?php } ?>
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

    <!-- SIMILAR PRODUCTS -->
    <div class="similar">
        <h3>You may also like</h3>

        <div class="similar-grid">
            <?php if (mysqli_num_rows($similarResult) > 0) { ?>
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

</div>

</body>

</html>