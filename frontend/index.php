<?php
session_start();
include '../db.php';

/* -------------------- helpers -------------------- */
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function fetchOne($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return [];
}

function fetchAll($conn, $sql) {
    $data = [];
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function imagePath($file) {
    $file = trim((string)$file);
    return "../images/" . ($file !== '' ? e($file) : "default.jpg");
}

/* -------------------- data fetch -------------------- */
$heroData   = fetchOne($conn, "SELECT * FROM hero LIMIT 1");
$heritage   = fetchOne($conn, "SELECT * FROM heritage_section LIMIT 1");

$categories = fetchAll($conn, "SELECT * FROM categories WHERE type='category'");
$occasions  = fetchAll($conn, "SELECT * FROM categories WHERE type='occasion' LIMIT 4");

$fagunFestival    = fetchOne($conn, "SELECT * FROM festivals WHERE name='fagun' LIMIT 1");
$lehariyaFestival = fetchOne($conn, "SELECT * FROM festivals WHERE name='lehariya' LIMIT 1");
$sareeFestival    = fetchOne($conn, "SELECT * FROM festivals WHERE name='Sarees' LIMIT 1");

$fagunProducts    = fetchAll($conn, "SELECT * FROM products WHERE category='fagun' ORDER BY id DESC LIMIT 4");
$lehariyaProducts = fetchAll($conn, "SELECT * FROM products WHERE category='lehariya' ORDER BY id DESC LIMIT 4");
$sareeProducts    = fetchAll($conn, "SELECT * FROM products WHERE category='saree' ORDER BY id DESC LIMIT 4");
$jewelleryProducts = fetchAll($conn, "SELECT * FROM products WHERE category='jewellery' ORDER BY id DESC LIMIT 3");

$testimonials = fetchAll($conn, "SELECT * FROM testimonials");

/* -------------------- wishlist once -------------------- */
$wishlistItems = [];
if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $wishQuery = mysqli_query($conn, "SELECT product_id FROM wishlist WHERE user_id = $uid");
    if ($wishQuery) {
        while ($wish = mysqli_fetch_assoc($wishQuery)) {
            $wishlistItems[] = (int)$wish['product_id'];
        }
    }
}

/* -------------------- links -------------------- */
$links = [
    'poshak'    => 'collection.php',
    'saree'     => 'saree.php',
    'jewellery' => 'jewellery.php',
];
?>
<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<!-- hero -->
<section class="hero">
    <div class="hero-glow"></div>

    <div class="hero-content">
        <h1 class="title-part top">
            <?php echo !empty($heroData['title']) ? e($heroData['title']) : 'Riwaayat'; ?>
        </h1>

        <div class="info-block">
            <a href="collection.php" class="btn-explore">
                <?php echo !empty($heroData['button_text']) ? e($heroData['button_text']) : 'Explore'; ?>
            </a>
        </div>

        <h1 class="title-part bottom">
            <?php echo !empty($heroData['bottom']) ? e($heroData['bottom']) : ''; ?>
        </h1>
    </div>
</section>

<br><br><br><br>

<!-- category -->
<section class="category-section">
    <h1>Shop By Category</h1>

    <div class="cards">
        <?php foreach ($categories as $row): ?>
            <?php
                $slug = isset($row['slug']) ? $row['slug'] : '';
                $link = isset($links[$slug]) ? $links[$slug] : 'collection.php';
            ?>
            <a href="<?php echo e($link); ?>?category=<?php echo urlencode($slug); ?>" class="card">
                <img src="<?php echo imagePath($row['image'] ?? ''); ?>" class="card-img" alt="<?php echo e($row['name'] ?? 'Category'); ?>">
                <div class="overlayy">
                    <h3><?php echo e($row['name'] ?? ''); ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="riwaayat-offers">
    <div class="offers-wrap">
        <h2 class="offers-title">Unlock Savings Worth ₹1000+</h2>

        <div class="offers-grid">
            <div class="offer-card">
                <div class="offer-icon">🚚</div>
                <div class="offer-text">
                    <h3>Free Shipping</h3>
                    <p>On all orders above ₹19999</p>
                </div>
            </div>

            <div class="offer-card">
                <div class="offer-icon">🔄</div>
                <div class="offer-text">
                    <h3>Easy Returns</h3>
                    <p>7 days return policy</p>
                </div>
            </div>

            <div class="offer-card">
                <div class="offer-icon">⚡</div>
                <div class="offer-text">
                    <h3>Fast Response</h3>
                    <p>Quick support on WhatsApp</p>
                </div>
            </div>

            <div class="offer-card">
                <div class="offer-icon">✂️</div>
                <div class="offer-text">
                    <h3>Handcrafted</h3>
                    <p>Authentic Rajputi designs</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-section">
    <h1>Shop By Occasion</h1>

    <div class="cards">
        <?php foreach ($occasions as $row): ?>
            <a href="occasion-products.php?occasion=<?php echo urlencode($row['name'] ?? ''); ?>" class="card">
                <img src="<?php echo imagePath($row['image'] ?? ''); ?>" class="card-img" alt="<?php echo e($row['name'] ?? 'Occasion'); ?>">
                <div class="overlayy">
                    <h3><?php echo e($row['name'] ?? ''); ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- fagun festival -->
<?php if (!empty($fagunFestival)): ?>
<div class="festival-banner">
    <img src="<?php echo imagePath($fagunFestival['banner'] ?? ''); ?>" alt="<?php echo e($fagunFestival['title'] ?? 'Festival Banner'); ?>">

    <div class="festival-content">
        <h2><?php echo e($fagunFestival['title'] ?? ''); ?></h2>

        <a href="collection.php?festival=<?php echo urlencode($fagunFestival['button_link'] ?? ''); ?>" class="btn fest">
            <?php echo e($fagunFestival['button_text'] ?? 'Explore'); ?>
        </a>
    </div>
</div>
<?php endif; ?>

<div class="fagun-cards">
    <?php foreach ($fagunProducts as $row): ?>
        <?php
            $pid = (int)($row['id'] ?? 0);
            $isWishlisted = in_array($pid, $wishlistItems);
        ?>
        <div class="fagun-card">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../backend/add_to_wishlist.php?id=<?php echo $pid; ?>" class="wishlist <?php echo $isWishlisted ? 'active' : ''; ?>">
                    <?php echo $isWishlisted ? '♥' : '♡'; ?>
                </a>
            <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode('../backend/add_to_wishlist.php?id=' . $pid); ?>" class="wishlist">♡</a>
            <?php endif; ?>

            <div class="fagun-img">
                <a href="product.php?id=<?php echo $pid; ?>">
                    <img src="<?php echo imagePath($row['image'] ?? ''); ?>" alt="<?php echo e($row['title'] ?? 'Product'); ?>">
                </a>
            </div>

            <div class="fagun-info">
                <h3><?php echo e($row['title'] ?? ''); ?></h3>
                <p class="price">₹<?php echo e($row['price'] ?? ''); ?></p>

                <div class="fagun-btns">
                    <button class="add-cart">Add to Cart</button>
                    <button class="buy-now">Buy Now</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- lehariya festival -->
<?php if (!empty($lehariyaFestival)): ?>
<div class="festival-banner">
    <img src="<?php echo imagePath($lehariyaFestival['banner'] ?? ''); ?>" alt="<?php echo e($lehariyaFestival['title'] ?? 'Festival Banner'); ?>">

    <div class="lehariya-content">
        <h2><?php echo e($lehariyaFestival['title'] ?? ''); ?></h2>

        <a href="collection.php?festival=<?php echo urlencode($lehariyaFestival['button_link'] ?? ''); ?>" class="btn fest">
            <?php echo e($lehariyaFestival['button_text'] ?? 'Explore'); ?>
        </a>
    </div>
</div>
<?php endif; ?>

<div class="fagun-cards">
    <?php foreach ($lehariyaProducts as $row): ?>
        <?php
            $pid = (int)($row['id'] ?? 0);
            $isWishlisted = in_array($pid, $wishlistItems);
        ?>
        <div class="fagun-card">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../backend/add_to_wishlist.php?id=<?php echo $pid; ?>" class="wishlist <?php echo $isWishlisted ? 'active' : ''; ?>">
                    <?php echo $isWishlisted ? '♥' : '♡'; ?>
                </a>
            <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode('../backend/add_to_wishlist.php?id=' . $pid); ?>" class="wishlist">♡</a>
            <?php endif; ?>

            <div class="fagun-img">
                <a href="product.php?id=<?php echo $pid; ?>">
                    <img src="<?php echo imagePath($row['image'] ?? ''); ?>" alt="<?php echo e($row['title'] ?? 'Product'); ?>">
                </a>
            </div>

            <div class="fagun-info">
                <h3><?php echo e($row['title'] ?? ''); ?></h3>
                <p class="price">₹<?php echo e($row['price'] ?? ''); ?></p>

                <div class="fagun-btns">
                    <button class="add-cart">Add to Cart</button>
                    <button class="buy-now">Buy Now</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<section class="riwaayat-strip">
    <div class="strip-left">
        <span class="strip-small">NEW ARRIVALS</span>
        <h2>Rajputi Sarees</h2>
    </div>

    <div class="strip-center">
        <span class="highlight-tag">THIS WEEK</span>
    </div>

    <div class="strip-right">
        <p>Now Live</p>
        <span class="date-badge">Festive Edit</span>
    </div>
</section>

<!-- saree festival -->
<?php if (!empty($sareeFestival)): ?>
<div class="festival-banner">
    <img src="<?php echo imagePath($sareeFestival['banner'] ?? ''); ?>" alt="<?php echo e($sareeFestival['title'] ?? 'Saree Banner'); ?>">

    <div class="lehariya-content">
        <h2><?php echo e($sareeFestival['title'] ?? ''); ?></h2>

        <a href="collection.php?festival=<?php echo urlencode($sareeFestival['button_link'] ?? ''); ?>" class="btn fest">
            <?php echo e($sareeFestival['button_text'] ?? 'Explore'); ?>
        </a>
    </div>
</div>
<?php endif; ?>

<div class="fagun-cards">
    <?php foreach ($sareeProducts as $row): ?>
        <?php
            $pid = (int)($row['id'] ?? 0);
            $isWishlisted = in_array($pid, $wishlistItems);
        ?>
        <div class="fagun-card">
            <?php if (isset($_SESSION['user_id'])): ?>
               <button class="wishlist-btn <?php echo $isWishlisted ? 'active' : ''; ?>" data-id="<?php echo $product['id']; ?>">
    <?php echo $isWishlisted ? '♥' : '♡'; ?>
</button>
            <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode('../backend/add_to_wishlist.php?id=' . $pid); ?>" class="wishlist">♡</a>
            <?php endif; ?>

            <div class="fagun-img">
                <a href="product.php?id=<?php echo $pid; ?>">
                    <img src="<?php echo imagePath($row['image'] ?? ''); ?>" alt="<?php echo e($row['title'] ?? 'Product'); ?>">
                </a>
            </div>

            <div class="fagun-info">
                <h3><?php echo e($row['title'] ?? ''); ?></h3>
                <p class="price">₹<?php echo e($row['price'] ?? ''); ?></p>

                <div class="fagun-btns">
                    <button class="add-cart">Add to Cart</button>
                    <button class="buy-now">Buy Now</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<section class="promo-wrapper">
    <div class="riwaayat-promo jewellery-promo">
        <div class="promo-image">
            <img src="../images/aad.jpg" alt="Jewellery">
        </div>

        <div class="promo-content">
            <p class="promo-small">Luxury Collection</p>
            <h2>Jewellery</h2>
            <h3>Timeless Grace & Regal Shine</h3>
        </div>

        <div class="promo-btn-wrap">
            <a href="jewellery.php" class="promo-btn">Shop Now</a>
        </div>
    </div>

    <div class="riwaayat-promo saree-promo">
        <div class="promo-image">
            <img src="../images/saree.jpg" alt="Sarees">
        </div>

        <div class="promo-content">
            <p class="promo-small">Elegant Drapes</p>
            <h2>Sarees</h2>
            <h3>Soft Elegance for Every Occasion</h3>
        </div>

        <div class="promo-btn-wrap">
            <a href="sarees.php" class="promo-btn">Shop Now</a>
        </div>
    </div>
</section>

<section class="jewellery-cards">
    <?php foreach ($jewelleryProducts as $row): ?>
        <div class="j-card">
            <div class="j-card-img">
                <img src="<?php echo imagePath($row['image'] ?? ''); ?>" alt="<?php echo e($row['title'] ?? 'Jewellery'); ?>">
            </div>
            <div class="j-card-content">
                <h3><?php echo e($row['title'] ?? ''); ?></h3>
                <p>₹<?php echo e($row['price'] ?? ''); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- heritage -->
<section class="heritage-section">
    <div class="container">
        <div class="heritage-content">
            <div class="text-column">
                <h2><?php echo e($heritage['title'] ?? ''); ?></h2>
                <p><?php echo e($heritage['content'] ?? ''); ?></p>

                <?php if (!empty($heritage['tagline'])): ?>
                    <span class="tagline"><?php echo e($heritage['tagline']); ?></span>
                <?php endif; ?>

                <?php if (!empty($heritage['button_text']) && !empty($heritage['button_link'])): ?>
                    <a href="<?php echo e($heritage['button_link']); ?>" class="cta-button">
                        <?php echo e($heritage['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="image-column">
                <img src="<?php echo imagePath($heritage['image_url'] ?? ''); ?>" alt="<?php echo e($heritage['title'] ?? 'Heritage'); ?>">
            </div>
        </div>
    </div>
</section>

<br>

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

<section class="promo-strip">
    <div class="promo-left">
        <span class="promo-stars">★★★★★</span>
        <h3>Bestsellers</h3>
        <p>Shop our most-loved Rajputi poshaks</p>
    </div>

    <div class="promo-center">
        <h2>UPTO <span>30%</span> OFF</h2>
    </div>

    <div class="promo-right">
        <a href="collection.php" class="promo-btn">Shop Now</a>
    </div>
</section>

<div class="testimonial-container">
    <h2>TESTIMONIALS</h2>

    <div class="slider">
        <?php if (!empty($testimonials)): ?>
            <?php $first = true; ?>
            <?php foreach ($testimonials as $test): ?>
                <div class="slide <?php if ($first) { echo 'active'; $first = false; } ?>">
                    <p class="message"><?php echo e($test['message'] ?? ''); ?></p>
                    <h4><?php echo e($test['name'] ?? ''); ?> - <?php echo e($test['city'] ?? ''); ?></h4>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="slide active">
                <p class="message">No testimonials available yet.</p>
                <h4>Riwaayat</h4>
            </div>
        <?php endif; ?>
    </div>

    <div class="dots"></div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <h2>Riwaayat</h2>
            <p>Traditional Rajputi Poshak & Bridal Collection with modern elegance.</p>
        </div>

        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="collection.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Contact</h3>
            <p>📍 Udaipur, Rajasthan</p>
            <p>📞 +91 9876543210</p>
            <p>✉️ info@riwaayat.com</p>
        </div>

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