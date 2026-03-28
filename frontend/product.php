<?php
include 'db.php';

$id = intval($_GET['id']);

$query = "SELECT * FROM products WHERE id=$id";
$result = mysqli_query($conn, $query);

if (!$result) {
  die("Query Error: " . mysqli_error($conn));
}

$product = mysqli_fetch_assoc($result);

// similar products
$work = $product['work_type'];
$similar = mysqli_query($conn, 
  "SELECT * FROM products WHERE id != $id ORDER BY id DESC LIMIT 4");

 $reviews = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 1");

if (!$reviews) {
    die("Query Error: " . mysqli_error($conn));
}

?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<div class="product-page">

    <!-- TOP SECTION -->
    <div class="product-top">

        <!-- LEFT IMAGE -->
        <div class="product-left">
            <img src="../images/<?php echo $product['image']; ?>" class="main-img">
        </div>

        <!-- RIGHT DETAILS -->
        <div class="product-right">

            <h2><?php echo $product['title']; ?></h2>
            <p class="price">₹<?php echo $product['price']; ?></p>

            <p><?php echo $product['description']; ?></p>

            <p class="tax">Inclusive of all taxes • Shipping calculated at checkout</p>

            <hr>

            <p><b>Color:</b> <?php echo $product['color'];?></p>
            <p><b>Fabric:</b> <?php echo $product['fabric'];  ?></p>
            <p><b>Pattern:</b> <?php echo $product['pattern']; ?></p>
            <p><b>Work:</b> <?php echo $product['work_type'];?></p>

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

    <?php if(mysqli_num_rows($reviews) > 0) { ?>
        <?php while($r = mysqli_fetch_assoc($reviews)) { ?>
            <div class="review-card">
                <h4><?php echo $r['name']; ?></h4>
                <p><?php echo $r['message']; ?></p>
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
        <?php while($s = mysqli_fetch_assoc($similar)) { ?>
            
            <a href="product.php?id=<?php echo $s['id']; ?>" class="similar-link">
                <div class="card">
                    <img src="../images/<?php echo $s['image']; ?>">
                    <h4><?php echo $s['title']; ?></h4>
                    <p>₹<?php echo $s['price']; ?></p>
                </div>
            </a>

        <?php } ?>
    </div>
</div>

</div>

</body>
</html>