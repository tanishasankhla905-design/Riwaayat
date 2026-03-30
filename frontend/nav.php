

<header class="riwaayat-header">



   <div class="top-barr">
    
    <div class="left-group">
        <div class="logo-section">
            <a href="index.php" class="logo">RIWAAYAT</a>
            <span class="tagline">Traditional Elegance</span>
        </div>

        <div class="highlight-box">
           
            <span> Rajputi Saree</span>
        </div>
    </div>

<div class="search-wrapper">
    <form class="search-bar" action="collection.php" method="GET">
        <button type="submit" class="search-btn">
           
        </button>

        <input 
            type="text" 
            name="search"
            placeholder="Search for saree, bridal, lehariya..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        >
    </form>
</div>

    <div class="nav-icons">
        <a href="login.php"><i class="fa-regular fa-user"></i> Login</a>
        <a href="wishlist.php"><i class="fa-regular fa-heart"></i> Wishlist</a>
        <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i> Cart</a>
    </div>

</div>
    <div class="category-bar">
        <a href="index.php" class="active">For You</a>
        <a href="collection.php">Rajputi Poshak</a>
        <a href="bridal.php">Bridal</a>
        <a href="festive.php">Festive</a>
        <a href="saree.php">Saree</a>
        
        <a href="jewellery.php">Jewellery</a>
        <a href="about.php">About</a>
    </div>
</header>