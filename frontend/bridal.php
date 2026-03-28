
<?php
include 'db.php';

$hero = $conn->query("SELECT * FROM hero WHERE id = 2");
$heroData = $hero->fetch_assoc();

$bridalResult = mysqli_query($conn, "SELECT * FROM bridal_products");

$productQuery = "SELECT * FROM products";
$productResult = mysqli_query($conn, $productQuery);
?>
<?php include "header.php"; ?>

<body>

<?php include "nav.php"; ?>

   


  <section class="bridal bridal-hero">

    <div class="bridal-hero-left">
        <h1 class="bridal-title"><?php echo $heroData['title'];?></h1>

        <div class="bridal-info-block">
            <a href="bridal.php" class="btn bride">
                <?php echo $heroData['button_text'];?>
            </a>
        </div>
    </div>

    <div class="bridal-hero-right">
        <img src="../images/<?php echo $heroData['image']; ?>" 
             alt="Bride" 
             class="bridal-img">
    </div>

</section>
   <section class="category-section bridal-section">
    <h1>Real Brides of Riwaayat</h1>

    <div class="bridal-cards">
        <?php
        $result = $conn->query("SELECT * FROM bridal_looks LIMIT 4");

        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="bridal-card">
                <img src="../images/<?php echo $row['image']; ?>" class="card-img" alt="">
                
                <div class="overlayy">
                    <span>BRIDAL</span>
                    <h3><?php echo $row['title']; ?></h3>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

    </div>
</section>
<section class="bridal-journey">
    <div class="container">

        <h2 class="section-title">A Bride’s Journey with Riwaayat</h2><br>

        <div class="journey-grid">

            <div class="journey-step">
                <span>01</span>
                <h3>Choose Your Style</h3>
                <p>Explore our traditional Rajputi bridal collections crafted with heritage designs.</p>
            </div>

            <div class="journey-step">
                <span>02</span>
                <h3>Customize Your Look</h3>
                <p>Select colors, embroidery, and styles that match your wedding vision.</p>
            </div>

            <div class="journey-step">
                <span>03</span>
                <h3>Perfect Fit</h3>
                <p>Every bridal outfit is tailored for elegance and comfort.</p>
            </div>

            <div class="journey-step">
                <span>04</span>
                <h3>Walk Like Royalty</h3>
                <p>Step into your wedding day wearing a look inspired by royal tradition.</p>
            </div>

        </div>

    </div>
</section>
<section class="best collection-best">
    <h1>Our Collections</h1>

    <div class="red-grid">
        <?php
        $result = $conn->query("SELECT * FROM collections LIMIT 8");

        while($row = $result->fetch_assoc()){
        ?>
            <div class="red-card">

                <button class="wishlist">♡</button>

                <div class="red-image">
                    <img src="../images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                </div>

                <div class="red-info">
                    <h3><?php echo $row['title']; ?></h3>
                    <p class="price">₹<?php echo number_format($row['price'], 2); ?></p>

                    <div class="buttons">
                        <button class="cart">Add to Cart</button>
                        <button class="buy">Buy Now</button>
                    </div>
                </div>

            </div>
        <?php } ?>
    </div>
</section>

<section class="bridal-guide">
    <div class="container">

        <h2 class="section-title">Bridal Styling Guide</h2><br>

        <div class="guide-grid">

            <div class="guide-item">
                <h3>Choosing the Right Color</h3>
                <p>Traditional bridal colors like deep red, maroon, and royal tones bring out the elegance of Rajputi bridal wear.</p>
            </div>

            <div class="guide-item">
                <h3>Jewelry Pairing</h3>
                <p>Statement jewelry enhances the royal feel of bridal poshak and completes the traditional look.</p>
            </div>

            <div class="guide-item">
                <h3>Fabric Selection</h3>
                <p>Silk, georgette, and embroidered fabrics create the perfect bridal appearance.</p>
            </div>

            <div class="guide-item">
                <h3>Perfect Draping</h3>
                <p>The way a poshak is styled defines the elegance of the entire bridal outfit.</p>
            </div>

        </div>

    </div>
</section>
<section class="craftsmanship">
    <div class="craftsmanship-container">
        <h2>Handcrafted Rajputi Heritage</h2>
        <p>
            Our bridal poshaks are crafted using traditional Gota Patti,
            Zari embroidery, and detailed handwork inspired by royal
            Rajputana culture.
        </p>

        <a href="about.php" class="craft-btn">Discover Our Craft</a>
    </div>
</section>
</body>
</html>