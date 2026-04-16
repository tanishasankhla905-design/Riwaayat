<?php
session_start();

include '../db.php';

/* -----------------------------
   SECTION 1 - NEW / FEATURED
----------------------------- */
$featuredQuery = "SELECT * FROM products WHERE type='saree' ORDER BY id DESC LIMIT 4";
$featuredResult = mysqli_query($conn, $featuredQuery);

/* -----------------------------
   SECTION 2 - SPOTLIGHT
----------------------------- */
$spotlightQuery = "SELECT * FROM products WHERE type='saree' ORDER BY CAST(price AS UNSIGNED) DESC LIMIT 4";
$spotlightResult = mysqli_query($conn, $spotlightQuery);

/* -----------------------------
   CURATED CATEGORIES
----------------------------- */
$categoryQuery = "SELECT DISTINCT category FROM products WHERE type='saree' AND category != '' LIMIT 4";
$categoryResult = mysqli_query($conn, $categoryQuery);

/* -----------------------------
   ALL SAREES GRID
----------------------------- */
$allQuery = "SELECT * FROM products WHERE type='saree' ORDER BY id DESC";
$allResult = mysqli_query($conn, $allQuery);

if (!$featuredResult || !$spotlightResult || !$categoryResult || !$allResult) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<?php include "header.php"; ?>
<?php include "nav.php"; ?>

<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    body{
        font-family:'Inter', sans-serif;
        background:#fbf7f2;
        color:#2f2418;
    }

    a{
        text-decoration:none;
        color:inherit;
    }

    img{
        max-width:100%;
        display:block;
    }

  

    /* HERO */
    .hero0{
        max-width:1400px;
        margin:30px auto 0;
        padding:0 20px;
    }

    .hero0-box{
        min-height:420px;
        border-radius:28px;
        overflow:hidden;
        display:grid;
        grid-template-columns:1.1fr 1fr;
        background:linear-gradient(135deg, #f7e8da, #f3ede6);
        border:1px solid #eadbcc;
    }

    .hero0-left{
        padding:70px 60px;
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    .hero0-left h1{
        font-family:'Playfair Display', serif;
        font-size:56px;
        line-height:1.1;
        margin-bottom:18px;
        color:#2b1d11;
    }

    .hero0-left p{
        font-size:16px;
        line-height:1.8;
        color:#6b5c4d;
        margin-bottom:28px;
        max-width:520px;
    }

    .hero0-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:160px;
        height:48px;
        background:#b89232;
        color:#fff;
        border-radius:999px;
        font-weight:600;
    }

    .hero0-right{
        background:#efe4d8;
        height:100%;
    }

    .hero0-right img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    /* SECTION COMMON */
    .section{
        max-width:1400px;
        margin:70px auto 0;
        padding:0 20px;
    }

    .section-head{
        text-align:center;
        margin-bottom:28px;
    }

    .section-head h2{
        font-family:'Playfair Display', serif;
        font-size:38px;
        color:#2b1d11;
        margin-bottom:10px;
    }

    .section-head p{
        font-size:15px;
        color:#7a6b5d;
        margin-bottom:14px;
    }

    .view-all{
        color:#b89232;
        font-weight:600;
        font-size:14px;
        text-transform:uppercase;
        letter-spacing:1px;
    }

    /* PRODUCT GRID */
    .product-grid{
        display:grid;
        grid-template-columns:repeat(4, 1fr);
        gap:24px;
    }

    .product-card{
        background:#fff;
        border-radius:22px;
        overflow:hidden;
        border:1px solid #eadbcc;
        transition:0.35s ease;
    }

    .product-card:hover{
        transform:translateY(-8px);
        box-shadow:0 14px 34px rgba(0,0,0,0.08);
    }

    .product-image{
        height:360px;
        background:#f5eee6;
        overflow:hidden;
        position:relative;
    }

    .product-image img{
        width:100%;
        height:100%;
        object-fit:cover;
        transition:0.45s ease;
    }

    .product-card:hover .product-image img{
        transform:scale(1.05);
    }

    .tag{
        position:absolute;
        top:14px;
        left:14px;
        background:#b89232;
        color:#fff;
        font-size:12px;
        padding:6px 12px;
        border-radius:20px;
        font-weight:600;
        z-index:2;
    }

    .product-info{
        padding:18px;
    }

    .product-info h3{
        font-size:17px;
        line-height:1.5;
        min-height:50px;
        margin-bottom:10px;
        color:#2f2418;
    }

    .highlights{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-bottom:12px;
    }

    .highlights span{
        background:#f7f0e8;
        color:#6a5338;
        font-size:12px;
        padding:6px 10px;
        border-radius:20px;
    }

    .price{
        font-size:22px;
        font-weight:700;
        color:#111;
        margin-bottom:14px;
    }

    .btn-row{
        display:flex;
        gap:10px;
    }

    .btn-row a{
        flex:1;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:12px;
        font-size:14px;
        font-weight:600;
    }

    .dark-btn{
        background:#1f1a15;
        color:#fff;
    }

    .gold-btn{
        background:#b89232;
        color:#fff;
    }

   

    /* ALL PRODUCTS */
    .all-products{
        margin-bottom:70px;
    }

    .empty{
        text-align:center;
        background:#fff;
        border:1px solid #eadbcc;
        border-radius:20px;
        padding:40px 20px;
        color:#7a6b5d;
    }

    @media(max-width:1200px){
        .product-grid{
            grid-template-columns:repeat(3, 1fr);
        }
    }

    @media(max-width:992px){
        .hero0-box{
            grid-template-columns:1fr;
        }

        .hero0-left{
            padding:50px 30px;
        }

        .hero0-left h1{
            font-size:42px;
        }

        .product-grid{
            grid-template-columns:repeat(2, 1fr);
        }
    }

    @media(max-width:576px){
        .header-inner{
            flex-direction:column;
        }

        .product-grid{
            grid-template-columns:1fr;
        }

        .hero0-left h1{
            font-size:34px;
        }

        .section-head h2{
            font-size:30px;
        }

        .product-image{
            height:320px;
        }
    }
</style>



<!-- HERO -->
<section class="hero0">
    <div class="hero0-box">
        <div class="hero0-left">
            <h1>Grace in Every Drape</h1>
            <p>
                Discover timeless sarees inspired by heritage, festive elegance,
                and classic Indian craftsmanship.
            </p>
            <a href="#all-products" class="hero0-btn">Shop Now</a>
        </div>

        <div class="hero0-right">
            <img src="../images/saree.jpg" alt="Saree Collection">
        </div>
    </div>
</section>

<!-- FEATURED -->
<section class="section">
    <div class="section-head">
        <h2>Grace in Bloom</h2>
        <p>Fresh saree arrivals from our newest collection</p>
        <a href="#all-products" class="view-all">View All</a>
    </div>

    <div class="product-grid">
        <?php while($row = mysqli_fetch_assoc($featuredResult)) { ?>
            <div class="product-card">
                <div class="product-image">
                    <span class="tag">New</span>
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </a>
                </div>

                <div class="product-info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                    <div class="highlights">
                        <?php if(!empty($row['fabric'])) { ?>
                            <span><?php echo htmlspecialchars($row['fabric']); ?></span>
                        <?php } ?>
                        <?php if(!empty($row['color'])) { ?>
                            <span><?php echo htmlspecialchars($row['color']); ?></span>
                        <?php } ?>
                        <?php if(!empty($row['work_type'])) { ?>
                            <span><?php echo htmlspecialchars($row['work_type']); ?></span>
                        <?php } ?>
                    </div>

                    <div class="price">₹<?php echo htmlspecialchars($row['price']); ?></div>

                    <div class="btn-row">
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="dark-btn">View</a>
                        <a href="#" class="gold-btn">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<!-- SPOTLIGHT -->
<section class="section">
    <div class="section-head">
        <h2>In the Spotlight</h2>
        <p>Statement sarees chosen from our premium selection</p>
        <a href="#all-products" class="view-all">View All</a>
    </div>

    <div class="product-grid">
        <?php while($row = mysqli_fetch_assoc($spotlightResult)) { ?>
            <div class="product-card">
                <div class="product-image">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </a>
                </div>

                <div class="product-info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                    <div class="highlights">
                        <?php if(!empty($row['category'])) { ?>
                            <span><?php echo htmlspecialchars($row['category']); ?></span>
                        <?php } ?>
                        <?php if(!empty($row['fabric'])) { ?>
                            <span><?php echo htmlspecialchars($row['fabric']); ?></span>
                        <?php } ?>
                        <?php if(!empty($row['pattern'])) { ?>
                            <span><?php echo htmlspecialchars($row['pattern']); ?></span>
                        <?php } ?>
                    </div>

                    <div class="price">₹<?php echo htmlspecialchars($row['price']); ?></div>

                    <div class="btn-row">
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="dark-btn">View</a>
                        <a href="#" class="gold-btn">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>



<!-- ALL PRODUCTS -->
<section class="section all-products" id="all-products">
    <div class="section-head">
        <h2>All Sarees</h2>
        <p>Browse the complete saree collection</p>
    </div>

    <?php if(mysqli_num_rows($allResult) > 0) { ?>
        <div class="product-grid">
            <?php while($row = mysqli_fetch_assoc($allResult)) { ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="product.php?id=<?php echo $row['id']; ?>">
                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </a>
                    </div>

                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                        <div class="highlights">
                            <?php if(!empty($row['color'])) { ?>
                                <span><?php echo htmlspecialchars($row['color']); ?></span>
                            <?php } ?>
                            <?php if(!empty($row['work_type'])) { ?>
                                <span><?php echo htmlspecialchars($row['work_type']); ?></span>
                            <?php } ?>
                            <?php if(!empty($row['fabric'])) { ?>
                                <span><?php echo htmlspecialchars($row['fabric']); ?></span>
                            <?php } ?>
                        </div>

                        <div class="price">₹<?php echo htmlspecialchars($row['price']); ?></div>

                        <div class="btn-row">
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="dark-btn">View</a>
                            <a href="#" class="gold-btn">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="empty">No sarees found.</div>
    <?php } ?>
</section>
<?php include "footer.php"; ?>

</body>
</html>