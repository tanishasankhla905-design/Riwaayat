
<style>/* ================= HEADER ================= */

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --gold: #b89232;
  --gold-dark: #7a5a11;
  --gold-mid: #9e7e2a;
  --bg: #f8f5f0;
  --cream: #fdf6f0;
  --text: #333;
  --maroon: #6d213c;
}

body {
  font-family: Arial, sans-serif;
  background: var(--bg);
  overflow-x: hidden;
}

.riwaayat-header {
  width: 100%;
  background: #fffaf5;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}

.top-barr {
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 20px;
  padding: 18px 40px;
  width: 100%;
  max-width: 1503px;
  border-bottom: 1px solid #eadfce;
}

.left-group {
  display: flex;
  align-items: center;
  gap: 14px;
  min-width: max-content;
}

.logo-section {
  display: flex;
  flex-direction: column;
}

.logo {
  text-decoration: none;
  font-size: 30px;
  font-weight: 700;
  letter-spacing: 2px;
  color: var(--maroon);
  font-family: Georgia, serif;
}

.tagline {
  font-size: 12px;
  color: #9a7b4f;
  margin-top: 2px;
}

.highlight-box {
  background: #f3ede4;
  padding: 12px 22px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
  color: #5f4b32;
  cursor: pointer;
  transition: 0.3s;
}

.highlight-box:hover {
  background: var(--maroon);
  color: #fff;
}

.highlight-box:hover i {
  color: #fff;
}

.search-wrapper {
  width: 100%;
  min-width: 0;
}

.search-bar {
  width: 100%;
  min-width: 0;
  height: 48px;
  background: #fff;
  border: 1.5px solid #d8c3a5;
  border-radius: 30px;
  display: flex;
  align-items: center;
  padding: 0 18px;
}

.search-bar i {
  color: #8b6f47;
  margin-right: 12px;
  font-size: 16px;
}

.search-bar input {
  flex: 1;
  min-width: 0;
  width: 100%;
  border: none;
  outline: none;
  background: transparent;
  font-size: 15px;
}

.nav-icons {
  display: flex;
  align-items: center;
  gap: 22px;
  min-width: max-content;
}

.nav-icons a {
  text-decoration: none;
  color: #4a2c2a;
  font-size: 15px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
}

.category-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 30px;
  padding: 14px 20px;
  overflow-x: auto;
  white-space: nowrap;
  background: #fffaf5;
}

.category-bar::-webkit-scrollbar {
  display: none;
}

.category-bar a {
  text-decoration: none;
  color: #5f4b32;
  font-size: 15px;
  font-weight: 500;
  position: relative;
  padding-bottom: 6px;
}

.category-bar a.active,
.category-bar a:hover {
  color: var(--maroon);
}

.category-bar a.active::after,
.category-bar a:hover::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 2px;
  background: var(--maroon);

}
/* ================= HEADER / NAV RESPONSIVE ================= */

@media (max-width: 992px) {
  .top-barr {
    grid-template-columns: 1fr;
    gap: 15px;
    padding: 16px 20px;
  }

  .left-group {
    justify-content: center;
    min-width: unset;
    flex-wrap: wrap;
    text-align: center;
  }

  .logo-section {
    align-items: center;
  }

  .search-wrapper {
    width: 100%;
  }

  .nav-icons {
    justify-content: center;
    flex-wrap: wrap;
    min-width: unset;
    gap: 16px;
  }

  .category-bar {
    justify-content: flex-start;
    padding: 12px 15px;
    gap: 20px;
  }
}

@media (max-width: 576px) {
  .top-barr {
    padding: 14px 15px;
  }

  .logo {
    font-size: 24px;
    letter-spacing: 1px;
  }

  .tagline {
    font-size: 11px;
    text-align: center;
  }

  .highlight-box {
    width: 100%;
    justify-content: center;
    padding: 10px 14px;
  }

  .search-bar {
    height: 42px;
    padding: 0 14px;
  }

  .search-bar input {
    font-size: 14px;
  }

  .nav-icons {
    gap: 12px;
  }

  .nav-icons a {
    font-size: 14px;
    gap: 6px;
  }

  .category-bar {
    gap: 16px;
    padding: 10px 12px;
  }

  .category-bar a {
    font-size: 14px;
  }
}
</style>
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
        <a href="index.php">For You</a>
        <a href="collection.php">Rajputi Poshak</a>
        <a href="bridal.php">Bridal</a>
        <a href="saree.php">Saree</a>
        
        <a href="jewellery.php">Jewellery</a>
        <a href="about.php">About</a>
    </div>
</header>