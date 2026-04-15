<?php
include 'admin-check.php';
include '../db.php';

$query = "SELECT * FROM products WHERE type='sarees' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$totalSarees = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarees | Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{
            background:linear-gradient(135deg, #fdf8f3, #f6efe6);
            color:#2f2a24;
        }

        .admin-layout{
            display:flex;
            min-height:100vh;
        }

        .main-content{
            flex:1;
            padding:30px;
            display:flex;
            flex-direction:column;
            gap:25px;
            overflow:hidden;
        }

        .page-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            flex-wrap:wrap;
        }

        .page-title h1{
            font-family:'Playfair Display', serif;
            font-size:46px;
            color:#2b1f16;
            margin-bottom:8px;
            line-height:1.1;
        }

        .page-title p{
            font-size:15px;
            color:#7b6c5d;
        }

        .add-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:14px 24px;
            background:linear-gradient(135deg, #b89232, #8e6d1f);
            color:#fff;
            text-decoration:none;
            border-radius:14px;
            font-size:15px;
            font-weight:600;
            box-shadow:0 10px 24px rgba(184,146,50,0.25);
            transition:0.3s ease;
        }

        .add-btn:hover{
            transform:translateY(-2px);
        }

        .dashboard-grid{
            display:grid;
            grid-template-columns:260px 1fr;
            gap:25px;
            align-items:start;
        }

        .stats-card{
            background:#fff;
            border-radius:22px;
            padding:24px;
            box-shadow:0 10px 30px rgba(0,0,0,0.06);
            border:1px solid rgba(184,146,50,0.15);
        }

        .stats-card h3{
            font-size:16px;
            color:#8a6b2f;
            margin-bottom:12px;
            font-weight:600;
        }

        .stats-card .number{
            font-size:42px;
            font-weight:700;
            color:#2d241b;
            line-height:1;
        }

        .table-card{
            background:#fff;
            border-radius:22px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.06);
            border:1px solid rgba(184,146,50,0.15);
        }

        .table-header{
            padding:22px 24px;
            border-bottom:1px solid #f0e6da;
            background:linear-gradient(135deg, #fffaf5, #f9f2e8);
        }

        .table-header h2{
            font-size:22px;
            color:#2b1f16;
            font-weight:600;
        }

        .table-responsive{
            width:100%;
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:1200px;
        }

        table thead{
            background:#1f1a15;
            color:#fff;
        }

        table thead th{
            padding:16px 14px;
            text-align:left;
            font-size:14px;
            font-weight:600;
            white-space:nowrap;
        }

        table tbody tr{
            border-bottom:1px solid #f3ebe1;
            transition:0.3s;
        }

        table tbody tr:hover{
            background:#fcf8f3;
        }

        table tbody td{
            padding:16px 14px;
            font-size:14px;
            color:#4a4036;
            vertical-align:middle;
        }

        .product-img{
            width:75px;
            height:90px;
            border-radius:12px;
            overflow:hidden;
            background:#f4ede5;
            border:1px solid #eee3d6;
        }

        .product-img img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .title-col{
            min-width:220px;
            font-weight:600;
            color:#2f2418;
            line-height:1.5;
        }

        .price{
            font-weight:700;
            color:#111;
            white-space:nowrap;
        }

        .badge{
            display:inline-block;
            padding:6px 12px;
            border-radius:30px;
            font-size:12px;
            font-weight:600;
            text-transform:capitalize;
            white-space:nowrap;
        }

        .badge-type{
            background:#f8edd7;
            color:#8a6720;
        }

        .badge-color{
            background:#efe7ff;
            color:#6d47b3;
        }

        .badge-work{
            background:#e7f7ef;
            color:#2d8a57;
        }

        .action-buttons{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .edit-btn,
        .delete-btn{
            text-decoration:none;
            padding:9px 14px;
            border-radius:10px;
            font-size:13px;
            font-weight:600;
            transition:0.3s;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .edit-btn{
            background:#1f1a15;
            color:#fff;
        }

        .edit-btn:hover{
            background:#000;
        }

        .delete-btn{
            background:#b23a3a;
            color:#fff;
        }

        .delete-btn:hover{
            background:#922f2f;
        }

        .empty-box{
            padding:60px 25px;
            text-align:center;
        }

        .empty-box h3{
            font-size:34px;
            color:#2d241b;
            margin-bottom:12px;
            font-family:'Playfair Display', serif;
        }

        .empty-box p{
            color:#7b6c5d;
            margin-bottom:22px;
            font-size:15px;
        }

        @media(max-width:1100px){
            .dashboard-grid{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:768px){
            .main-content{
                padding:20px 15px;
            }

            .page-title h1{
                font-size:36px;
            }

            .page-header{
                align-items:flex-start;
                flex-direction:column;
            }

            .add-btn{
                width:100%;
            }
        }
    </style>
</head>
<body>

<div class="admin-layout">

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">

        <?php include 'includes/header.php'; ?>

        <div class="page-header">
            <div class="page-title">
                <h1>Saree Products</h1>
                <p>Manage all saree products from your products table</p>
            </div>

            <a href="add.php?type=saree" class="add-btn">+ Add Saree</a>
        </div>

        <div class="dashboard-grid">

            <!-- LEFT STATS -->
            <div class="stats-card">
                <h3>Total Sarees</h3>
                <div class="number"><?php echo $totalSarees; ?></div>
            </div>

            <!-- RIGHT TABLE -->
            <div class="table-card">
                <div class="table-header">
                    <h2>All Saree Products</h2>
                </div>

                <?php if(mysqli_num_rows($result) > 0) { ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Color</th>
                                    <th>Fabric</th>
                                    <th>Pattern</th>
                                    <th>Work Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>

                                        <td>
                                            <div class="product-img">
                                                <?php if(!empty($row['image'])) { ?>
                                                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                                <?php } else { ?>
                                                    <img src="../images/no-image.png" alt="No image">
                                                <?php } ?>
                                            </div>
                                        </td>

                                        <td class="title-col">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </td>

                                        <td class="price">₹<?php echo htmlspecialchars($row['price']); ?></td>

                                        <td>
                                            <?php echo !empty($row['category']) ? htmlspecialchars($row['category']) : '-'; ?>
                                        </td>

                                        <td>
                                            <?php if(!empty($row['type'])) { ?>
                                                <span class="badge badge-type"><?php echo htmlspecialchars($row['type']); ?></span>
                                            <?php } else { echo '-'; } ?>
                                        </td>

                                        <td>
                                            <?php if(!empty($row['color'])) { ?>
                                                <span class="badge badge-color"><?php echo htmlspecialchars($row['color']); ?></span>
                                            <?php } else { echo '-'; } ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($row['fabric']) ? htmlspecialchars($row['fabric']) : '-'; ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($row['pattern']) ? htmlspecialchars($row['pattern']) : '-'; ?>
                                        </td>

                                        <td>
                                            <?php if(!empty($row['work_type'])) { ?>
                                                <span class="badge badge-work"><?php echo htmlspecialchars($row['work_type']); ?></span>
                                            <?php } else { echo '-'; } ?>
                                        </td>

                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this saree product?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="empty-box">
                        <h3>No Saree Products Found</h3>
                        <p>You have not added any saree products yet.</p>
                        <a href="add.php?type=saree" class="add-btn">Add First Saree</a>
                    </div>
                <?php } ?>
            </div>

        </div>

        <?php include 'includes/footer.php'; ?>

    </div>
</div>

</body>
</html>