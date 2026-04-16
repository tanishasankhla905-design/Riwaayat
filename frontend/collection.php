<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
include '../db.php';

$search   = isset($_GET['search'])    ? trim($_GET['search'])   : '';
$work     = isset($_GET['work'])      ? trim($_GET['work'])     : '';
$category = isset($_GET['category'])  ? trim($_GET['category']) : '';
$color    = isset($_GET['color'])     ? trim($_GET['color'])    : '';
$minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;
$sort     = isset($_GET['sort'])      ? trim($_GET['sort'])     : 'latest';

$query = "SELECT products.*, wishlist.id AS wish_id
          FROM products
          LEFT JOIN wishlist 
          ON products.id = wishlist.product_id 
          AND wishlist.user_id = '$user_id'
          WHERE type='poshaks' AND 1=1";
if ($search !== '') {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND title LIKE '%$search%'";
}
if ($work !== '') {
    $work = mysqli_real_escape_string($conn, $work);
    $query .= " AND work_type = '$work'";
}
if ($category !== '') {
    $category = mysqli_real_escape_string($conn, $category);
    $query .= " AND category = '$category'";
}
if ($color !== '') {
    $color = mysqli_real_escape_string($conn, $color);
    $query .= " AND color = '$color'";
}
if ($minPrice > 0) {
    $query .= " AND CAST(price AS UNSIGNED) >= $minPrice";
}
if ($maxPrice > 0) {
    $query .= " AND CAST(price AS UNSIGNED) <= $maxPrice";
}

if ($sort == 'low') {
    $query .= " ORDER BY CAST(price AS UNSIGNED) ASC";
} elseif ($sort == 'high') {
    $query .= " ORDER BY CAST(price AS UNSIGNED) DESC";
} else {
    $query .= " ORDER BY id DESC";
}

$result     = mysqli_query($conn, $query);
$workTypes  = mysqli_query($conn, "SELECT DISTINCT work_type FROM products WHERE work_type != '' AND work_type IS NOT NULL");
$categories = mysqli_query($conn, "SELECT DISTINCT category FROM products WHERE category != '' AND category NOT IN ('saree','poshak', 'jewellery')");
$colors     = mysqli_query($conn, "SELECT DISTINCT color FROM products WHERE color != '' AND color IS NOT NULL");

if (!$result || !$workTypes || !$categories || !$colors) {
    die("Query Error: " . mysqli_error($conn));
}


?>
<?php include "header.php"; ?>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f8f5f0;
    color: #2d2d2d;
}

a {
    text-decoration: none;
    color: inherit;
}

.collection-page {
    width: 100%;
    padding: 40px 30px;
}

.collection-header {
    text-align: center;
    margin-bottom: 30px;
}

.collection-header h1 {
    font-size: 38px;
    font-family: 'Playfair Display', serif;
    color: #2b1e12;
    margin-bottom: 8px;
}

.collection-header p {
    color: #7a6a58;
    font-size: 15px;
}

.collection-layout {
    display: grid;
    grid-template-columns: 270px 1fr;
    gap: 30px;
    align-items: start;
}

.filters {
    background: #fff;
    border: 1px solid #e8ded1;
    border-radius: 16px;
    overflow: hidden;
    position: sticky;
    top: 20px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.filter-title {
    padding: 18px 20px;
    font-size: 18px;
    font-weight: 600;
    border-bottom: 1px solid #eee3d6;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fcfaf7;
    flex-shrink: 0;
}

.filter-form {
    padding: 18px;
    overflow-y: auto;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.search-box input {
    width: 100%;
    height: 42px;
    border: 1px solid #d8cbbb;
    border-radius: 10px;
    padding: 0 14px;
    outline: none;
    font-size: 14px;
    margin-bottom: 14px;
}

.filter-group {
    border-top: 1px solid #efe4d9;
    padding: 14px 0;
}

.filter-group h3 {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    color: #6b5235;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 9px;
    max-height: 160px;
    overflow-y: auto;
}

.filter-options label {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 14px;
    cursor: pointer;
    color: #3d342b;
}

.filter-options input[type="radio"] {
    accent-color: #b89232;
    width: 15px;
    height: 15px;
}

.price-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.price-inputs input {
    width: 100%;
    height: 40px;
    border: 1px solid #d8cbbb;
    border-radius: 10px;
    padding: 0 12px;
    outline: none;
    font-size: 14px;
}

.filter-buttons {
    display: flex;
    gap: 10px;
    margin-top: auto;
    padding-top: 16px;
    padding-bottom: 4px;
    position: sticky;
    bottom: 0;
    background: #fff;
}

.apply-btn,
.reset-btn {
    flex: 1;
    height: 42px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: 0.3s;
}

.apply-btn {
    background: #b89232;
    color: #fff;
}

.apply-btn:hover {
    background: #9f7d27;
}

.reset-btn {
    background: #efe6d8;
    color: #4d3b26;
}

.reset-btn:hover {
    background: #e4d6c0;
}

.products-area {
    width: 100%;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 15px;
    flex-wrap: wrap;
}

.results-count {
    font-size: 15px;
    color: #6f6252;
}

.sort-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-box label {
    font-size: 14px;
    color: #5c4b38;
    font-weight: 600;
}

.sort-box select {
    height: 42px;
    padding: 0 14px;
    border: 1px solid #d8cbbb;
    border-radius: 10px;
    background: #fff;
    outline: none;
    cursor: pointer;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

.fagun-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    transition: 0.3s;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.fagun-card:hover {
    transform: translateY(-6px);
}

.fagun-img {
    width: 100%;
    height: 320px;
    overflow: hidden;
    position: relative;
}

.fagun-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
    transition: 0.4s;
}

.fagun-card:hover .fagun-img img {
    transform: scale(1.05);
}

.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 38px;
    height: 38px;
    border: none;
    background: #fff;
    color: #222;
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

.wishlist-btn.active {
    color: red;
}

.fagun-info {
    padding: 15px;
    text-align: center;
}

.fagun-info h3 {
    font-size: 16px;
    margin-bottom: 6px;
    color: #2f2418;
}

.fagun-info .price {
    color: #b89232;
    font-weight: 600;
    margin-bottom: 12px;
    font-size: 16px;
}

.fagun-btns {
    display: flex;
    gap: 10px;
}

.fagun-btns a {
    flex: 1;
    height: 42px;
    border-radius: 0;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-cart {
    background: transparent;
    border: 1px solid #000;
    color: #000;
}

.add-cart:hover {
    background: #000;
    color: #fff;
}

.buy-now {
    background: linear-gradient(45deg, #b89232, #d4af37);
    border: none;
    color: #fff;
}

.buy-now:hover {
    opacity: 0.85;
}

.no-products {
    background: #fff;
    border: 1px solid #e8ded1;
    border-radius: 16px;
    padding: 50px 20px;
    text-align: center;
    color: #6a5b4b;
    font-size: 17px;
}

@media(max-width:1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media(max-width:992px) {
    .collection-layout {
        grid-template-columns: 1fr;
    }

    .filters {
        position: relative;
        top: 0;
        max-height: none;
    }

    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:576px) {
    .collection-page {
        padding: 25px 15px;
    }

    .products-grid {
        grid-template-columns: 1fr;
    }

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<?php include "nav.php"; ?>

<section class="collection-page">

    <div class="collection-header">
        <h1>All Rajputi Poshaks</h1>
        <p>Explore Riwaayat's timeless Rajputi collection</p>
    </div>

    <div class="collection-layout">

        <aside class="filters">
            <div class="filter-title">
                <i class="fa-solid fa-sliders"></i> Filters
            </div>

            <form method="GET" class="filter-form" id="filterForm">

                <div class="search-box">
                    <input type="text" name="search" placeholder="Search products..."
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="filter-group">
                    <h3>Work Type</h3>
                    <div class="filter-options">
                        <label>
                            <input type="radio" name="work" value="" <?php if($work=='') echo 'checked'; ?>> All
                        </label>
                        <?php while($row = mysqli_fetch_assoc($workTypes)) { ?>
                        <label>
                            <input type="radio" name="work" value="<?php echo htmlspecialchars($row['work_type']); ?>"
                                <?php if($work == $row['work_type']) echo 'checked'; ?>>
                            <?php echo ucfirst($row['work_type']); ?>
                        </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Category</h3>
                    <div class="filter-options">
                        <label>
                            <input type="radio" name="category" value="" <?php if($category=='') echo 'checked'; ?>> All
                        </label>
                        <?php while($row = mysqli_fetch_assoc($categories)) { ?>
                        <label>
                            <input type="radio" name="category"
                                value="<?php echo htmlspecialchars($row['category']); ?>"
                                <?php if($category == $row['category']) echo 'checked'; ?>>
                            <?php echo ucfirst($row['category']); ?>
                        </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Color</h3>
                    <div class="filter-options">
                        <label>
                            <input type="radio" name="color" value="" <?php if($color=='') echo 'checked'; ?>> All
                        </label>
                        <?php while($row = mysqli_fetch_assoc($colors)) { ?>
                        <label>
                            <input type="radio" name="color" value="<?php echo htmlspecialchars($row['color']); ?>"
                                <?php if($color == $row['color']) echo 'checked'; ?>>
                            <?php echo ucfirst($row['color']); ?>
                        </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Price Range</h3>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min"
                            value="<?php echo $minPrice ? $minPrice : ''; ?>">
                        <input type="number" name="max_price" placeholder="Max"
                            value="<?php echo $maxPrice ? $maxPrice : ''; ?>">
                    </div>
                </div>

                <input type="hidden" name="sort" id="sortInput" value="<?php echo htmlspecialchars($sort); ?>">

                <div class="filter-buttons">
                    <button type="submit" class="apply-btn">Apply</button>
                    <a href="collection.php" class="reset-btn"
                        style="display:flex;align-items:center;justify-content:center;">Reset</a>
                </div>

            </form>
        </aside>

        <div class="products-area">

            <div class="top-bar">
                <div class="results-count"><?php echo mysqli_num_rows($result); ?> products found</div>
                <div class="sort-box">
                    <label>Sort By</label>
                    <select id="sortSelect">
                        <option value="latest" <?php if($sort=='latest') echo 'selected'; ?>>Latest</option>
                        <option value="low" <?php if($sort=='low') echo 'selected'; ?>>Price: Low to High</option>
                        <option value="high" <?php if($sort=='high') echo 'selected'; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

         <?php if(mysqli_num_rows($result) > 0) { ?>
<div class="products-grid">

    <?php while ($product = mysqli_fetch_assoc($result)) { 
        $productId = $product['id'];
        $isWishlisted = !empty($product['wish_id']);
    ?>

    <div class="fagun-card">
        <div class="fagun-img">

            <button type="button"
                class="wishlist-btn <?php echo $isWishlisted ? 'active' : ''; ?>"
                data-id="<?php echo $productId; ?>">
                <?php echo $isWishlisted ? '♥' : '♡'; ?>
            </button>

            <a href="product.php?id=<?php echo $productId; ?>">
                <img src="../images/<?php echo htmlspecialchars($product['image']); ?>"
                     alt="<?php echo htmlspecialchars($product['title']); ?>">
            </a>

        </div>

        <div class="fagun-info">
            <h3><?php echo htmlspecialchars($product['title']); ?></h3>
            <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>

            <div class="fagun-btns">
                <a href="cart.php?id=<?php echo $productId; ?>" class="add-cart">Add to Cart</a>
                <a href="checkout.php?id=<?php echo $productId; ?>" class="buy-now">Buy Now</a>
            </div>
        </div>
    </div>

    <?php } ?>

</div>
<?php } else { ?>
    <div class="no-products">No products found for selected filters.</div>
<?php } ?>
</section>

<?php include "footer.php"; ?>
