<?php
include '../db.php';
include "header.php";
include "nav.php";

/* hero products */
$heroQuery = mysqli_query($conn, "SELECT * FROM products WHERE category='jewellery' ORDER BY id DESC LIMIT 4");
$heroProducts = [];
if ($heroQuery) {
    while ($row = mysqli_fetch_assoc($heroQuery)) {
        $heroProducts[] = $row;
    }
}

/* featured jewellery */
$featuredQuery = mysqli_query($conn, "SELECT * FROM products WHERE category='jewellery' ORDER BY id DESC LIMIT 4");

/* category items */
$catQuery = mysqli_query($conn, "SELECT * FROM products WHERE category='jewellery' ORDER BY id DESC LIMIT 4");
$catProducts = [];
if ($catQuery) {
    while ($row = mysqli_fetch_assoc($catQuery)) {
        $catProducts[] = $row;
    }
}
?>

<style>.jewelry-hero {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
  gap: 50px;
  padding: 70px 8%;
  background: #fdf8f3;
}

.hero-left h1 {
  font-size: 52px;
  color: #7a5a11;
  margin-bottom: 15px;
  font-family: Georgia, serif;
}

.hero-left p {
  font-size: 18px;
  color: #555;
  margin-bottom: 30px;
  line-height: 1.6;
}

.hero-thumbs {
  display: flex;
  gap: 15px;
  margin-bottom: 30px;
}

.hero-thumbs img {
  width: 110px;
  height: 130px;
  object-fit: cover;
  border-radius: 14px;
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
}

.btn {
  display: inline-block;
  padding: 14px 32px;
  background: linear-gradient(45deg, #b89232, #d4af37);
  color: #fff;
  text-decoration: none;
  border-radius: 30px;
  font-weight: 600;
  transition: 0.3s;
}

.btn:hover {
  transform: translateY(-2px);
  opacity: 0.9;
}

.hero-right img {
  width: 100%;
  max-height: 620px;
  object-fit: cover;
  border-radius: 24px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

.category {
  padding: 80px 8%;
  text-align: center;
  background: #fff;
}

.category h2 {
  font-size: 40px;
  color: #7a5a11;
  margin-bottom: 40px;
  font-family: Georgia, serif;
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
}

.cat {
  background: #f8f3ee;
  border-radius: 20px;
  overflow: hidden;
  padding-bottom: 18px;
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
  transition: 0.3s;
}

.cat:hover {
  transform: translateY(-6px);
}

.cat img {
  width: 100%;
  height: 280px;
  object-fit: cover;
  display: block;
}

.cat p {
  font-size: 20px;
  color: #7a5a11;
  margin-top: 14px;
  font-weight: 600;
}

.featured-jewellery {
  padding: 80px 8%;
  background: #fdf6f0;
}

.section-head {
  text-align: center;
  margin-bottom: 40px;
}

.section-head h2 {
  font-size: 42px;
  color: #7a5a11;
  margin-bottom: 10px;
  font-family: Georgia, serif;
}

.section-head p {
  color: #666;
  font-size: 17px;
}

.jewellery-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
}

.j-card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  transition: 0.3s;
}

.j-card:hover {
  transform: translateY(-6px);
}

.j-img {
  position: relative;
  height: 320px;
  overflow: hidden;
}

.j-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: 0.4s;
}

.j-card:hover .j-img img {
  transform: scale(1.05);
}

.wish {
  position: absolute;
  top: 14px;
  right: 14px;
  width: 38px;
  height: 38px;
  background: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #b89232;
  font-size: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.j-info {
  padding: 18px;
  text-align: center;
}

.j-info h3 {
  font-size: 20px;
  color: #7a5a11;
  margin-bottom: 8px;
}

.j-info .price {
  color: #b89232;
  font-weight: 700;
  margin-bottom: 14px;
}

.view-btn {
  display: inline-block;
  padding: 11px 22px;
  background: #111;
  color: #fff;
  text-decoration: none;
  border-radius: 25px;
  transition: 0.3s;
}

.view-btn:hover {
  background: #b89232;
}

@media (max-width: 992px) {
  .jewelry-hero {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .hero-thumbs {
    justify-content: center;
  }

  .category-grid,
  .jewellery-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .hero-left h1 {
    font-size: 34px;
  }

  .hero-left p {
    font-size: 15px;
  }

  .hero-thumbs img {
    width: 85px;
    height: 100px;
  }

  .category h2,
  .section-head h2 {
    font-size: 30px;
  }

  .category-grid,
  .jewellery-grid {
    grid-template-columns: 1fr;
  }

  .cat img,
  .j-img {
    height: 260px;
  }
}</style>
<section class="jewelry-hero">
  <div class="hero-left">
    <h1>Find Your Perfect Piece</h1>
    <p>Discover timeless elegance with our jewellery collection</p>

    <div class="hero-thumbs">
      <?php for ($i = 0; $i < min(3, count($heroProducts)); $i++) { ?>
        <img src="../images/<?php echo htmlspecialchars($heroProducts[$i]['image']); ?>"
             alt="<?php echo htmlspecialchars($heroProducts[$i]['title']); ?>">
      <?php } ?>
    </div>

    <a href="collection.php?category=jewellery" class="btn">Shop Now</a>
  </div>

  <div class="hero-right">
    <?php if (!empty($heroProducts[3]['image'])) { ?>
      <img src="../images/<?php echo htmlspecialchars($heroProducts[3]['image']); ?>"
           alt="<?php echo htmlspecialchars($heroProducts[3]['title']); ?>">
    <?php } elseif (!empty($heroProducts[0]['image'])) { ?>
      <img src="../images/<?php echo htmlspecialchars($heroProducts[0]['image']); ?>"
           alt="<?php echo htmlspecialchars($heroProducts[0]['title']); ?>">
    <?php } ?>
  </div>
</section>

<section class="category">
  <h2>Shop by Category</h2>

  <div class="category-grid">
    <?php foreach ($catProducts as $row) { ?>
      <div class="cat">
        <img src="../images/<?php echo htmlspecialchars($row['image']); ?>"
             alt="<?php echo htmlspecialchars($row['title']); ?>">
        <p><?php echo htmlspecialchars($row['title']); ?></p>
      </div>
    <?php } ?>
  </div>
</section>

<section class="featured-jewellery">
  <div class="section-head">
    <h2>Featured Jewellery</h2>
    <p>Handpicked royal pieces for every occasion</p>
  </div>

  <div class="jewellery-grid">
    <?php while ($row = mysqli_fetch_assoc($featuredQuery)) { ?>
      <div class="j-card">
        <div class="j-img">
          <img src="../images/<?php echo htmlspecialchars($row['image']); ?>"
               alt="<?php echo htmlspecialchars($row['title']); ?>">
          <span class="wish">♡</span>
        </div>
        <div class="j-info">
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="price">₹<?php echo htmlspecialchars($row['price']); ?></p>
          <a href="product.php?id=<?php echo (int)$row['id']; ?>" class="view-btn">View Details</a>
        </div>
      </div>
    <?php } ?>
  </div>
</section>