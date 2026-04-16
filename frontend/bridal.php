<?php
session_start();

include '../db.php';

$heroQuery = "SELECT id, title, image, color, price 
              FROM products 
              WHERE color='red' 
              ORDER BY id ASC 
              LIMIT 3";
$heroResult = mysqli_query($conn, $heroQuery);

$heroProducts = [];
if ($heroResult && mysqli_num_rows($heroResult) > 0) {
    while ($row = mysqli_fetch_assoc($heroResult)) {
        $heroProducts[] = $row;
    }
}

$occasionQuery = "SELECT * FROM occasion ORDER BY id ASC";
$occasionResult = mysqli_query($conn, $occasionQuery);

if (!$occasionResult) {
    die("Query Error: " . mysqli_error($conn));
}
$bridalProductsQuery = mysqli_query($conn, "SELECT * FROM products WHERE color='red' ORDER BY id DESC LIMIT 8");

$testimonialQuery = mysqli_query($conn, "SELECT * FROM testimonials LIMIT 3");

$faqItems = [
    [
        "q" => "Can I customize the bridal poshak?",
        "a" => "Yes, selected bridal designs can be customized in color, work and styling based on availability."
    ],
    [
        "q" => "Do you provide stitching support?",
        "a" => "Yes, stitching guidance and support can be provided depending on the bridal outfit selected."
    ],
    [
        "q" => "Is jewellery included with the bridal outfit?",
        "a" => "Jewellery is generally separate unless clearly mentioned in the product details."
    ],
    [
        "q" => "How early should I book my bridal look?",
        "a" => "It is best to finalize your bridal outfit well in advance so fittings and styling can be managed smoothly."
    ]
];
?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<section class="bridal-hero-section">
    <div class="bridal-hero-inner">

        <!-- Left Content -->
        <div class="bridal-hero-content">
            <p class="bridal-small-title">RIWAAYAT RAJPUTI BRIDAL</p>
            <h1>Deluxe Bridal Elegance</h1>
            <p class="bridal-desc">
                Discover regal red Rajputi bridal poshaks designed for your big day.
                Explore royal silhouettes, rich embroidery, matching odhnis and timeless wedding style.
            </p>

            <a href="bridal.php" class="bridal-hero-btn">Explore Collection</a>
        </div>

        <!-- Right Images -->
        <div class="bridal-hero-gallery">

            <?php if (!empty($heroProducts)) { ?>

                <!-- left small card -->
                <?php if (isset($heroProducts[1])) { ?>
                    <a href="product.php?id=<?php echo $heroProducts[1]['id']; ?>" class="hero-card hero-card-left">
                        <img src="../images/<?php echo htmlspecialchars($heroProducts[1]['image']); ?>" alt="<?php echo htmlspecialchars($heroProducts[1]['title']); ?>">
                    </a>
                <?php } ?>

                <!-- center big card -->
                <a href="product.php?id=<?php echo $heroProducts[0]['id']; ?>" class="hero-card hero-card-center">
                    <img src="../images/<?php echo htmlspecialchars($heroProducts[0]['image']); ?>" alt="<?php echo htmlspecialchars($heroProducts[0]['title']); ?>">
                </a>

                <!-- right small card -->
                <?php if (isset($heroProducts[2])) { ?>
                    <a href="product.php?id=<?php echo $heroProducts[2]['id']; ?>" class="hero-card hero-card-right">
                        <img src="../images/<?php echo htmlspecialchars($heroProducts[2]['image']); ?>" alt="<?php echo htmlspecialchars($heroProducts[2]['title']); ?>">
                    </a>
                <?php } ?>

            <?php } else { ?>
                <div class="no-hero-products">
                    No red bridal products found.
                </div>
            <?php } ?>

        </div>
    </div>
</section>

<section class="category-section bridal-section">
    <h1>Real Brides of Riwaayat</h1>

    <div class="bridal-cards">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM bridal_looks LIMIT 4");

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="bridal-card">
                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img" alt="<?php echo htmlspecialchars($row['title']); ?>">
                
                <div class="overlayy">
                    <span>BRIDAL</span>
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<section class="bridal-membership">
    <div class="bridal-wrapper">

        <div class="bridal-left">
            <h2>Unlock Bridal Elegance Worth ₹5000+</h2>

            <div class="bridal-benefit active">
                <div class="icon">✦</div>
                <div class="text">
                    <h3>Exclusive Bridal Collection</h3>
                    <p>Discover handpicked Rajputi bridal poshaks for your special day.</p>
                </div>
            </div>

            <div class="bridal-benefit">
                <div class="icon">✦</div>
                <div class="text">
                    <h3>Personal Styling Support</h3>
                    <p>Get assistance in selecting colors, fabrics, and complete bridal looks.</p>
                </div>
            </div>

            <div class="bridal-benefit">
                <div class="icon">✦</div>
                <div class="text">
                    <h3>Special Wedding Offers</h3>
                    <p>Enjoy curated offers on bridal poshaks, jewellery, and festive essentials.</p>
                </div>
            </div>
        </div>

        <div class="bridal-right">
            <div class="bridall-card">
                <h3>Riwaayat</h3>
                <h1>Bridal</h1>
                <p class="tagline">ROYAL LOOK. TIMELESS TRADITION.</p>

                <div class="price-box">
                    <span class="old-price">₹12,999</span>
                    <span class="new-price">₹8,499</span>
                </div>

                <p class="tax">Inclusive of all taxes</p>
                <span class="validity">Bridal Special Collection</span>
            </div>
        </div>

    </div>
</section>

<section class="occasion-section">
    <div class="occasion-heading">
        <h2>Shop by Occasion</h2>
        <p>Find the perfect Rajputi look for every celebration.</p>
    </div>

    <div class="occasion-grid">
        <?php
        $count = 1;
        while ($row = mysqli_fetch_assoc($occasionResult)) {
            $class = '';

            if ($count == 1) {
                $class = 'large-card';
            } elseif ($count == 2 || $count == 3) {
                $class = 'medium-card';
            } else {
                $class = 'small-card';
            }
        ?>
            <a href="occasion-products.php?occasion=<?php echo urlencode($row['title']); ?>" class="occasion-card <?php echo $class; ?>">
                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                <div class="occasion-overlay">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                </div>
            </a>
        <?php
            $count++;
        }
        ?>
    </div>
</section>

<section class="bridal-promo">
    <div class="bridal-promo-left">
        <div class="promo-card-stack">
            <div class="promo-mini-card card-back"></div>
            <div class="promo-mini-card card-front">
                <span>RIWAAYAT</span>
                <p>BRIDAL</p>
            </div>
        </div>

        <div class="bridal-promo-text">
            <h2>Celebrate Your Bridal Look With Royal Rajputi Grace</h2>
            <p>Explore bridal poshaks, odhnis, jewellery and wedding essentials designed for your special moments.</p>
        </div>
    </div>

    <div class="bridal-promo-right">
        <a href="bridal.php" class="bridal-promo-btn">SHOP NOW</a>
    </div>
</section>


<section class="bridal-collection section-space">
    <div class="section-heading center">
        <p class="section-label">ROYAL SELECTION</p>
        <h2>Bridal Collection</h2>
        <p>Explore signature red bridal looks designed for timeless Rajputi elegance.</p>
    </div>

    <div class="bridal-product-grid">
        <?php if ($bridalProductsQuery && mysqli_num_rows($bridalProductsQuery) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($bridalProductsQuery)) { ?>
                <div class="bridal-product-card">
                    <div class="bridal-product-image">
                        <a href="product.php?id=<?php echo (int)$row['id']; ?>">
                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </a>
                    </div>

                    <div class="bridal-product-info">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="bridal-product-price">₹<?php echo htmlspecialchars($row['price']); ?></p>

                        <div class="bridal-product-tags">
                            <?php if (!empty($row['color'])) { ?>
                                <span><?php echo htmlspecialchars($row['color']); ?></span>
                            <?php } ?>
                            <?php if (!empty($row['work_type'])) { ?>
                                <span><?php echo htmlspecialchars($row['work_type']); ?></span>
                            <?php } ?>
                        </div>

                        <a href="product.php?id=<?php echo (int)$row['id']; ?>" class="bridal-product-btn">View Details</a>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="empty-text">No bridal products found.</p>
        <?php } ?>
    </div>
</section>

<section class="why-riwaayat section-space">
    <div class="section-heading center">
        <p class="section-label">WHY CHOOSE US</p>
        <h2>Why Brides Choose Riwaayat</h2>
        <p>We design bridal looks with tradition, detail and a deep understanding of Rajputi elegance.</p>
    </div>

    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon">✦</div>
            <h3>Traditional Handwork</h3>
            <p>Detailed gota, zari and handcrafted bridal finish inspired by heritage styles.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">✦</div>
            <h3>Royal Bridal Styling</h3>
            <p>Looks curated for brides who want regal silhouettes and timeless wedding elegance.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">✦</div>
            <h3>Bridal Assistance</h3>
            <p>Support in selecting color, work, odhni pairing and complete occasion-ready styling.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">✦</div>
            <h3>Premium Presentation</h3>
            <p>Every bridal design is created to feel luxurious, graceful and celebration-ready.</p>
        </div>
    </div>
</section>



<section class="bridal-faq section-space">
    <div class="section-heading center">
        <p class="section-label">FAQ</p>
        <h2>Frequently Asked Questions</h2>
        <p>Helpful answers for planning your bridal look with ease.</p>
    </div>

    <div class="faq-wrap">
        <?php foreach ($faqItems as $item) { ?>
            <div class="faq-item">
                <h3><?php echo htmlspecialchars($item['q']); ?></h3>
                <p><?php echo htmlspecialchars($item['a']); ?></p>
            </div>
        <?php } ?>
    </div>
</section>

<section class="bridal-final-cta">
    <div class="bridal-final-cta-inner">
        <p class="section-label light">RIWAAYAT BRIDAL</p>
        <h2>Begin Your Bridal Journey With Timeless Rajputi Grace</h2>
        <p>Find your perfect bridal look crafted for elegance, celebration and unforgettable moments.</p>
        <a href="bridal.php" class="bridal-final-btn">Explore Bridal Collection</a>
    </div>
</section>
<?php include "footer.php"; ?>

</body>
</html>