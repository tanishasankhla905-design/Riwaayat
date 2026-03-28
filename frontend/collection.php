<?php
include 'db.php';

$work = $_GET['work'] ?? 'all';

$search = $_GET['search'] ?? '';

$sort = $_GET['sort'] ?? 'latest';

$hero = $conn->query("SELECT * FROM hero WHERE id = 3");
$heroData = $hero->fetch_assoc();

$query = "SELECT * FROM products WHERE 1";

// category filter
if ($work != 'all') {
  $query .= " AND work_type='$work'";
}

// search filter
if (!empty($search)) {
  $query .= " AND title LIKE '%$search%'";
}

if ($sort == 'low') {
  $query .= " ORDER BY CAST(price AS UNSIGNED) ASC";
} elseif ($sort == 'high') {
  $query .= " ORDER BY CAST(price AS UNSIGNED) DESC";
} else {
  $query .= " ORDER BY id ASC";
}
$result = mysqli_query($conn, $query);


?>
<?php include "header.php"; ?>

<body>
    <?php include "nav.php"; ?>
    <section class="collection" style="background-image: url('../images/<?php echo $heroData['image']; ?>');">


        <div class="collection-content">
            <h1 class="collection-title"><?php echo $heroData['title'];?></h1>


            <a href="collection.php" class="btn collect"><?php echo $heroData['button_text'];?></a>



        </div>

    </section>


    <div class="filters">
        <a href="?work=all" class="pill <?= $work=='all' ? 'active' : '' ?>">All</a>

        <a href="?work=zari" class="pill <?= $work=='zari' ? 'active' : '' ?>">Zari Work</a>

        <a href="?work=kalkati" class="pill <?= $work=='kalkati' ? 'active' : '' ?>">Kalkati Work</a>

        <a href="?work=silver" class="pill <?= $work=='silver' ? 'active' : '' ?>">Silver Work</a>

        <a href="?work=gold" class="pill <?= $work=='gold' ? 'active' : '' ?>">Gold Work</a>
    </div>

    <div class="top-bar-wrapper">
        <form method="GET" class="top-bar">

            <!-- SEARCH -->
            <input type="text" name="search" placeholder="Search products..." value="<?= $search ?>">

            <!-- SORT (RIGHT SIDE) -->
            <select name="sort" onchange="this.form.submit()">
                <option value="latest" <?= $sort=='latest' ? 'selected' : '' ?>>Latest</option>
                <option value="low" <?= $sort=='low' ? 'selected' : '' ?>>Price Low → High</option>
                <option value="high" <?= $sort=='high' ? 'selected' : '' ?>>Price High → Low</option>
            </select>

            <!-- KEEP CATEGORY -->
            <input type="hidden" name="category" value="<?= $category ?>">

        </form>
    </div>
    <?php if(mysqli_num_rows($result) > 0) { ?>

    <div class="collection-wrapper">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <a href="product.php?id=<?php echo $row['id']; ?>" class="card-link">

            <div class="collection-card">
                <div class="img-box">
                    <img src="../images/<?php echo $row['image']; ?>" alt="">
                    <div class="wishlist">♡</div>
                </div>

                <h3><?php echo $row['title']; ?></h3>
                <p>₹<?php echo $row['price']; ?></p>

                <div class="btn-group">
                    <button class="cart">Add to Cart</button>
                    <button class="buy">Buy Now</button>
                </div>
            </div>

        </a>


        <?php } ?>
    </div>

    <?php } else { ?>

    <div class="no-product">
        <h2>No Products Found 😔</h2>
        <p>Try searching something else</p>
    </div>

    <?php } ?>
    </div>
</body>

</html>