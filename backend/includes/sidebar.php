<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <div class="brand">
        <h2>RIWAAYAT</h2>
        <p>Premium Admin Panel</p>
    </div>

    <nav class="menu">
        <a href="dashboard.php" class="<?php if($current_page=='dashboard.php') echo 'active'; ?>">Dashboard</a>
        <a href="products.php"class="<?php if($current_page=='products.php') echo 'active'; ?>">All Products</a>
        <a href="poshaks.php" class="<?php if($current_page=='poshaks.php') echo 'active'; ?>">Poshaks</a>
        <a href="sarees.php"class="<?php if($current_page=='sarees.php') echo 'active'; ?>">Sarees</a>
        <a href="jewellery.php"class="<?php if($current_page=='jewellery.php') echo 'active'; ?>">Jewellery</a>
        <a href="occasion.php"class="<?php if($current_page=='occasion.php') echo 'active'; ?>">occassion</a>
  
    </nav>
</aside>