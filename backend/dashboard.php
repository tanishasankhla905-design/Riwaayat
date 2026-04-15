<?php

include 'admin-check.php';

include '../db.php';

$poshakCount = 0;
$sareeCount = 0;
$jewelleryCount = 0;
$totalCount = 0;


$poshakQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category='poshak'");
if ($poshakQuery) {
    $poshakData = mysqli_fetch_assoc($poshakQuery);
    $poshakCount = $poshakData['total'];
}

$sareeQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category='saree'");
if ($sareeQuery) {
    $sareeData = mysqli_fetch_assoc($sareeQuery);
    $sareeCount = $sareeData['total'];
}

$jewelleryQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category='jewellery'");
if ($jewelleryQuery) {
    $jewelleryData = mysqli_fetch_assoc($jewelleryQuery);
    $jewelleryCount = $jewelleryData['total'];
}

$recentQuery = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Premium Dashboard - Riwaayat</title>
  <style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: Arial, sans-serif;
    }

    body{
        background: linear-gradient(135deg, #fdf8f3, #f6efe6);
        color:#2f2a24;
    }

    .admin-layout{
        display:flex;
        min-height:100vh;
    }

    .sidebar{
        width:270px;
        background: linear-gradient(180deg, #1f1a15, #2c241d);
        color:#fff;
        padding:28px 20px;
        position:sticky;
        top:0;
        height:100vh;
        border-right:1px solid rgba(255,255,255,0.06);
    }

    .brand{
        text-align:center;
        margin-bottom:35px;
        padding-bottom:22px;
        border-bottom:1px solid rgba(255,255,255,0.08);
    }

    .brand h2{
        color:#d2a63b;
        font-size:28px;
        letter-spacing:1px;
        margin-bottom:8px;
        font-family: Georgia, serif;
    }

    .brand p{
        color:#c9b89a;
        font-size:13px;
    }

    .menu{
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .menu a{
        text-decoration:none;
        color:#f4eee7;
        padding:14px 16px;
        border-radius:14px;
        transition:0.3s ease;
        font-size:15px;
        background:rgba(255,255,255,0.03);
    }

    .menu a:hover,
    .menu a.active{
        background: linear-gradient(135deg, #b89232, #8e6b13);
        color:#fff;
        transform:translateX(4px);
    }

    .main{
        flex:1;
        padding:28px;
    }

    .topbar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:28px;
        flex-wrap:wrap;
    }

    .welcome-box{
        background:#fff;
        border-radius:22px;
        padding:24px 26px;
        box-shadow:0 10px 28px rgba(120, 90, 30, 0.08);
        flex:1;
        min-width:280px;
        position:relative;
        overflow:hidden;
    }

    .welcome-box::before{
        content:"";
        position:absolute;
        inset:0;
        background:
            radial-gradient(circle at top right, rgba(184,146,50,0.18), transparent 28%),
            radial-gradient(circle at bottom left, rgba(184,146,50,0.10), transparent 24%);
        pointer-events:none;
    }

    .welcome-box h1{
        position:relative;
        z-index:1;
        color:#7a5a11;
        font-size:30px;
        margin-bottom:8px;
    }

    .welcome-box p{
        position:relative;
        z-index:1;
        color:#6e6257;
        font-size:15px;
    }

    .top-actions{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }

    .top-btn{
        text-decoration:none;
        padding:13px 20px;
        border-radius:14px;
        font-weight:600;
        font-size:14px;
        transition:0.3s ease;
    }

    .top-btn.gold{
        background: linear-gradient(135deg, #b89232, #8e6b13);
        color:#fff;
        box-shadow:0 8px 18px rgba(184,146,50,0.25);
    }

    .top-btn.dark{
        background:#231d18;
        color:#fff;
    }

    .top-btn:hover{
        transform:translateY(-2px);
    }

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:20px;
        margin-bottom:30px;
    }

    .stat-card{
        background:#fff;
        border-radius:22px;
        padding:24px 22px;
        box-shadow:0 10px 25px rgba(120, 90, 30, 0.08);
        position:relative;
        overflow:hidden;
    }

    .stat-card::after{
        content:"";
        position:absolute;
        top:0;
        right:0;
        width:90px;
        height:90px;
        background:radial-gradient(circle, rgba(184,146,50,0.18), transparent 70%);
    }

    .stat-card h3{
        color:#75695b;
        font-size:15px;
        margin-bottom:12px;
        font-weight:600;
    }

    .stat-card .number{
        font-size:34px;
        color:#7a5a11;
        font-weight:700;
        margin-bottom:8px;
    }

    .stat-card span{
        font-size:13px;
        color:#9a8a76;
    }

    .manage-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:22px;
        margin-bottom:30px;
    }

    .manage-card{
        background:#fff;
        border-radius:24px;
        padding:28px 24px;
        box-shadow:0 12px 30px rgba(120, 90, 30, 0.08);
        transition:0.35s ease;
        position:relative;
        overflow:hidden;
    }

    .manage-card::before{
        content:"";
        position:absolute;
        inset:0;
        background:
            radial-gradient(circle at right top, rgba(184,146,50,0.14), transparent 28%);
        pointer-events:none;
    }

    .manage-card:hover{
        transform:translateY(-8px);
    }

    .manage-card .icon{
        width:64px;
        height:64px;
        border-radius:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:28px;
        margin-bottom:18px;
        background:linear-gradient(135deg, #b89232, #8e6b13);
        color:#fff;
        box-shadow:0 10px 22px rgba(184,146,50,0.25);
    }

    .manage-card h3{
        color:#7a5a11;
        font-size:24px;
        margin-bottom:10px;
        font-family: Georgia, serif;
    }

    .manage-card p{
        color:#665b51;
        font-size:15px;
        line-height:1.6;
        margin-bottom:20px;
    }

    .manage-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .card-btn{
        text-decoration:none;
        padding:11px 16px;
        border-radius:12px;
        font-size:14px;
        font-weight:600;
        transition:0.3s ease;
    }

    .card-btn.primary{
        background:linear-gradient(135deg, #b89232, #8e6b13);
        color:#fff;
    }

    .card-btn.secondary{
        background:#f5ede3;
        color:#7a5a11;
    }

    .card-btn:hover{
        transform:translateY(-2px);
    }

    .bottom-grid{
        display:grid;
        grid-template-columns:1.3fr 0.7fr;
        gap:22px;
    }

    .panel{
        background:#fff;
        border-radius:24px;
        padding:24px;
        box-shadow:0 12px 30px rgba(120, 90, 30, 0.08);
    }

    .panel h2{
        color:#7a5a11;
        margin-bottom:18px;
        font-size:24px;
        font-family: Georgia, serif;
    }

    .recent-table{
        width:100%;
        border-collapse:collapse;
    }

    .recent-table th,
    .recent-table td{
        text-align:left;
        padding:14px 10px;
        border-bottom:1px solid #efe6da;
        font-size:14px;
    }

    .recent-table th{
        color:#8b7b69;
        font-weight:600;
        background:#fcf8f3;
    }

    .recent-table td{
        color:#4d4339;
    }

    .tag{
        display:inline-block;
        padding:6px 10px;
        border-radius:20px;
        font-size:12px;
        background:#f7efe4;
        color:#7a5a11;
        font-weight:600;
    }

    .quick-links{
        display:flex;
        flex-direction:column;
        gap:14px;
    }

    .quick-links a{
        text-decoration:none;
        background:#fcf7f1;
        color:#5f513f;
        padding:15px 16px;
        border-radius:16px;
        transition:0.3s ease;
        border:1px solid #f1e6d8;
        font-weight:600;
    }

    .quick-links a:hover{
        background:linear-gradient(135deg, #b89232, #8e6b13);
        color:#fff;
        border-color:transparent;
    }

    @media (max-width: 1200px){
        .stats-grid{
            grid-template-columns:repeat(3, 1fr);
        }

        .manage-grid{
            grid-template-columns:1fr;
        }

        .bottom-grid{
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 900px){
        .admin-layout{
            flex-direction:column;
        }

        .sidebar{
            width:100%;
            height:auto;
            position:relative;
        }

        .menu{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
        }
    }

    @media (max-width: 600px){
        .main{
            padding:16px;
        }

        .stats-grid{
            grid-template-columns:1fr;
        }

        .menu{
            grid-template-columns:1fr;
        }

        .welcome-box h1{
            font-size:24px;
        }

        .topbar{
            flex-direction:column;
            align-items:stretch;
        }
    }
  </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="admin-layout">

   <?php include('includes/sidebar.php'); ?>

    <main class="main">
<?php include('includes/topbar.php'); ?>
       

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Poshaks</h3>
                <div class="number"><?php echo $poshakCount; ?></div>
                <span>Rajputi poshak collection</span>
            </div>

            <div class="stat-card">
                <h3>Sarees</h3>
                <div class="number"><?php echo $sareeCount; ?></div>
                <span>All saree products</span>
            </div>

            <div class="stat-card">
                <h3>Jewellery</h3>
                <div class="number"><?php echo $jewelleryCount; ?></div>
                <span>Jewellery collection</span>
            </div>
        </div>

        <div class="manage-grid">
            <div class="manage-card">
                <div class="icon">⭐</div>
                <h3>Poshaks</h3>
                <p>Manage bridal, festive, and signature Rajputi poshak collections from one place.</p>
                <div class="manage-actions">
                    <a href="poshaks.php" class="card-btn primary">Manage</a>
                    <a href="add.php" class="card-btn secondary">Add New</a>
                </div>
            </div>

            <div class="manage-card">
                <div class="icon">🥻</div>
                <h3>Sarees</h3>
                <p>Add designer drapes, bridal sarees, festive edits, and silk collections easily.</p>
                <div class="manage-actions">
                    <a href="sarees.php" class="card-btn primary">Manage</a>
                    <a href="add.php" class="card-btn secondary">Add New</a>
                </div>
            </div>

            <div class="manage-card">
                <div class="icon">💎</div>
                <h3>Jewellery</h3>
                <p>Manage kundan, polki, bridal sets, bangles, borla, and statement jewellery pieces.</p>
                <div class="manage-actions">
                    <a href="jewellery.php" class="card-btn primary">Manage</a>
                    <a href="add.php" class="card-btn secondary">Add New</a>
                </div>
            </div>
        </div>

        <div class="bottom-grid">
            <div class="panel">
                <h2>Recent Products</h2>

                <table class="recent-table">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                    </tr>

                    <?php if ($recentQuery && mysqli_num_rows($recentQuery) > 0) { ?>
                        <?php while($row = mysqli_fetch_assoc($recentQuery)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><span class="tag"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No recent products found.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="panel">
                <h2>Quick Actions</h2>
                <div class="quick-links">
                    <a href="products.php">Open All Products</a>
                    <a href="poshaks.php">Open Poshaks</a>
                    <a href="sarees.php">Open Sarees</a>
                    <a href="jewellery.php">Open Jewellery</a>
                    <a href="add.php">Add New Product</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

    </main>

</div>
<?php include('includes/footer.php'); ?>
</body>
</html>