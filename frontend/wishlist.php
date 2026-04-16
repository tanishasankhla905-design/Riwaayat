<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT products.*
    FROM wishlist
    INNER JOIN products ON wishlist.product_id = products.id
    WHERE wishlist.user_id = '$user_id'
    ORDER BY wishlist.id DESC
");

if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #fdf6f0;
}

.wishlist-section {
    width: 90%;
    max-width: 1200px;
    margin: 50px auto;
}

.wishlist-section h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #b89232;
    font-size: 36px;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(4, 250px);
    gap: 25px;
    justify-content: center;
}

.wishlist-card {
    position: relative;
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  transition: 0.3s;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.wishlist-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.wishlist-card:hover {
    transform: translateY(-5px);
}

.wishlist-card img {
    width: 100%;
    height: 320px;
    object-fit: cover;
    object-position:top;
    display: block;
}

.wishlist-info {
    padding: 16px;
    text-align: center;
}

.wishlist-info h3 {
    margin: 0 0 10px;
    font-size: 18px;
}

.wishlist-info p {
    margin: 0;
    color: #444;
    font-weight: bold;
}

.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 38px;
    height: 38px;
    border: none;
    background: #fff;
    color: red;
    font-size: 22px;
    line-height: 38px;
    text-align: center;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transition: 0.3s ease;
    z-index: 10;
}

.wishlist-btn:hover {
    transform: scale(1.08);
}

.empty-msg {
    text-align: center;
    font-size: 20px;
    color: #555;
    padding: 50px 0;
    grid-column: 1 / -1;
}
</style>

<div class="wishlist-section">
    <h2>My Wishlist</h2>

    <div class="wishlist-grid">
        <?php if (mysqli_num_rows($query) > 0) { ?>
        <?php while($product = mysqli_fetch_assoc($query)) { ?>
        <div class="wishlist-card">

            <!-- FULL CLICKABLE AREA -->
            <a href="product.php?id=<?php echo $product['id']; ?>" class="wishlist-link">
                <img src="../images/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['title']); ?>">

                <div class="wishlist-info">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p>₹<?php echo htmlspecialchars($product['price']); ?></p>
                </div>
            </a>

            <!-- HEART BUTTON (separate) -->
            <button type="button" class="wishlist-btn active" data-id="<?php echo $product['id']; ?>">
                ♥
            </button>

        </div>
        <?php } ?>
        <?php } else { ?>
        <div class="empty-msg">Your wishlist is empty.</div>
        <?php } ?>
    </div>
</div>

<?php include "footer.php"; ?>
</body>

</html>