<?php
include 'db.php';

$hero = $conn->query("SELECT * FROM hero LIMIT 1");
$heroData = $hero->fetch_assoc();

$heritage = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM heritage_section LIMIT 1"));

$result = mysqli_query($conn, "SELECT * FROM categories WHERE status=1 ORDER BY id ASC LIMIT 3");
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$productQuery = "SELECT * FROM products LIMIT 5";
$productResult = mysqli_query($conn, $productQuery);

$fagunResult = mysqli_query($conn, "SELECT * FROM fagun_products");

$leharResult = mysqli_query($conn, "SELECT * FROM lehariya_products");

$testresult = mysqli_query($conn, "SELECT * FROM testimonials");

?>
<?php include "header.php"; ?>

<body>
    <?php include "nav.php"; ?>
    <!-- hero -->
    <section class="hero">
        <div class="hero-glow"></div>

        <div class="hero-content">
            <h1 class="title-part top"><?php echo $heroData['title'];?></h1>

            <div class="info-block">

                <a href="collection.php" class="btn-explore"><?php echo $heroData['button_text'];?></a>
            </div>

            <h1 class="title-part bottom"><?php echo $heroData['bottom'];?></h1>
        </div>
    </section><br><br><br><br>

    <section class="offers">

        <div class="grid">
            <div class="col"><i class="fa-solid fa-truck-fast"></i><br>
                <p>24 hours<br> fast delivery</p>
            </div>
            <div class="col"><i class="fa-solid fa-arrow-rotate-left"></i><br>
                <p>Easy Returns</p>
            </div>
            <div class="col"><i class="fa-solid fa-mobile-vibrate"></i><br>
                <p>Fast<br>Response</p>
            </div>
            <div class="col"><i class="fa-solid fa-scissors"></i><br>
                <p>Handicraft <br> Products</p>
            </div>
        </div>

    </section>


    <!--category-->


    <section class="category-section">

        <h1>Our Signature Poshaks</h1>

        <div class="cards">

            <?php foreach($categories as $category): ?>
            <div class="card">

                <!-- MODEL IMAGE -->
                <img src="../images/<?php echo $category['image']; ?>" class="card-img" alt="">

                <!-- FRAME IMAGE (STATIC FILE) -->


                <!-- TEXT -->
                <div class="overlayy">
                    <span>COLLECTION</span>
                    <h3><?php echo $category['name']; ?></h3>
                </div>

            </div>
            <?php endforeach; ?>

        </div>
    </section>


    <!--bestseller-->
 <section class="best">
     <h1>Shop Our Bestsellers Poshaks </h1><br>
    <div class="product-grid">

    
    <?php while($product = mysqli_fetch_assoc($productResult)) { ?>
        <div class="product-card">
            
            <!-- Wishlist -->
            <button class="wishlist">♡</button>

            <!-- Image -->
            <div class="product-image">
                <img src="../images/<?php echo $product['image']; ?>">
            </div>

            <!-- Details -->
            <div class="product-info">
                <h3><?php echo $product['title']; ?></h3>
                <p class="price">₹<?php echo $product['price']; ?></p>

                <!-- Buttons -->
                <div class="buttons">
                    <button class="cart">Add to Cart</button>
                    <button class="buy">Buy Now</button>
                </div>
            </div>

        </div>
    <?php } ?>
</div>
</section>
<!---festivals--->
<?php
$query = "SELECT * FROM festivals WHERE name='fagun' LIMIT 1";
$result = mysqli_query($conn, $query);
$fagun = mysqli_fetch_assoc($result);
?>

<div class="festival-banner">

    <!-- IMAGE -->
    <img src="../images/<?php echo $fagun['banner']; ?>" alt="">

    <!-- TEXT CONTENT -->
    <div class="festival-content">
        <h2><?php echo $fagun['title']; ?></h2>
        

        <a href="collection.php?festival=<?php echo $fagun['button_link']; ?>" class="btn fest">
            <?php echo $fagun['button_text']; ?>
        </a>
    </div>

</div>

<div class="fagun-cards">

<?php while($fagunQuery = mysqli_fetch_assoc($fagunResult)) { ?>
    
    <div class="fagun-card">
         <button class="wishlist">♡</button>
        <!-- IMAGE -->
        <div class="fagun-img">
            <img src="../images/<?php echo $fagunQuery['image']; ?>" alt="">
        </div>

        <!-- INFO -->
        <div class="fagun-info">
            <h3><?php echo $fagunQuery['name']; ?></h3>
            <p class="price">₹<?php echo $fagunQuery['price']; ?></p>

            <!-- BUTTONS -->
            <div class="fagun-btns">
                <button class="add-cart">Add to Cart</button>
                <button class="buy-now">Buy Now</button>
            </div>
        </div>

    </div>

<?php } ?>

</div>

<?php
$query = "SELECT * FROM festivals WHERE name='lehariya' LIMIT 1";
$result = mysqli_query($conn, $query);
$lehariya = mysqli_fetch_assoc($result);
?>

<div class="festival-banner">

    <!-- IMAGE -->
    <img src="../images/<?php echo $lehariya['banner']; ?>" alt="">

    <!-- TEXT CONTENT -->
    <div class="lehariya-content">
        <h2><?php echo $lehariya['title']; ?></h2>
        

        <a href="collection.php?festival=<?php echo $lehariya['button_link']; ?>" class="btn fest">
            <?php echo $lehariya['button_text']; ?>
        </a>
    </div>

</div>

<!---lehariya-->
<div class="fagun-cards">

<?php while($leharQuery = mysqli_fetch_assoc($leharResult)) { ?>
    
    <div class="fagun-card">
         <button class="wishlist">♡</button>
        <!-- IMAGE -->
        <div class="fagun-img">
            
            <img src="../images/<?php echo $leharQuery['image']; ?>" alt="">
        </div>

        <!-- INFO -->
        <div class="fagun-info">
            <h3><?php echo $leharQuery['title']; ?></h3>
            <p class="price">₹<?php echo $leharQuery['price']; ?></p>

            <!-- BUTTONS -->
            <div class="fagun-btns">
                <button class="add-cart">Add to Cart</button>
                <button class="buy-now">Buy Now</button>
            </div>
        </div>

    </div>

<?php } ?>

</div>
    <!-- heritage -->

    <section class="heritage-section">
        <div class="container">
            <div class="heritage-content">
                <div class="text-column">
                    <h2><?= $heritage['title'] ?></h2>
                    <p><?= $heritage['content'] ?></p>
                    <?php if (!empty($heritage['tagline'])): ?>
                    <span class="tagline"><?= $heritage['tagline'] ?></span>
                    <?php endif; ?>
                    <?php if (!empty($heritage['button_text']) && !empty($heritage['button_link'])): ?>
                    <a href="<?= $heritage['button_link'] ?>" class="cta-button"><?= $heritage['button_text'] ?></a>
                    <?php endif; ?>
                </div>
                <div class="image-column">
                    <img src="../images/<?= $heritage['image_url'] ?>" alt="<?= $heritage['title'] ?>">
                </div>
            </div>
        </div>
    </section><br>
<section class="call-banner">
    <div class="overlay">
        <div class="call-content">
            <h1>The Ultimate In-Store Experience</h1>
            <h2>Via 24x7 Video Shopping</h2>
            <p>Our stylists can speak: English, Hindi, Gujarati & Marathi</p>
            
            <a href="#" class="call-btn">START CALL NOW</a>
        </div>
    </div>
</section>
    <div class="testimonial-container">
    <h2>TESTIMONIALS</h2>

    <div class="slider">
        <?php while($test = mysqli_fetch_assoc($testresult)) { ?>
            <div class="slide">
                <p class="message"><?php echo $test['message']; ?></p>
                <h4><?php echo $test['name']; ?> - <?php echo $test['city']; ?></h4>
            </div>
        <?php } ?>
    </div>

    <div class="dots"></div>
</div>
<footer class="footer">
    <div class="footer-container">

        <!-- Brand -->
        <div class="footer-col">
            <h2>Riwaayat</h2>
            <p>Traditional Rajputi Poshak & Bridal Collection with modern elegance.</p>
        </div>

        <!-- Links -->
        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">festive</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h3>Contact</h3>
            <p>📍 Udaipur, Rajasthan</p>
            <p>📞 +91 9876543210</p>
            <p>✉️ info@riwaayat.com</p>
        </div>

        <!-- Social -->
        <div class="footer-col">
            <h3>Follow Us</h3>
            <div class="social">
                <a href="#">🌐</a>
                <a href="#">📘</a>
                <a href="#">📸</a>
                <a href="#">▶️</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© 2026 Riwaayat. All Rights Reserved.</p>
    </div>
</footer>
   <script src="script.js"></script>
</body>

</html>