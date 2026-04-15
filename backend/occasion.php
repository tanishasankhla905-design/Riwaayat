<?php
include 'admin-check.php';
include '../db.php';

$query = "SELECT * FROM occasion ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Occasion</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .occasion-wrapper{
            width:100%;
        }

        .occasion-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            flex-wrap:wrap;
            margin-bottom:25px;
        }

        .occasion-header h1{
            font-size:32px;
            color:#4e2e24;
            font-family: Georgia, serif;
        }

        .add-btn{
            display:inline-block;
            padding:12px 22px;
            background:#9b5a43;
            color:#fff;
            text-decoration:none;
            border-radius:10px;
            font-weight:600;
            transition:0.3s ease;
        }

        .add-btn:hover{
            background:#7f4634;
        }

        .table-box{
            width:100%;
            background:#fff;
            border-radius:20px;
            padding:20px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:850px;
        }

        table th,
        table td{
            padding:16px 14px;
            text-align:left;
            border-bottom:1px solid #eee;
            vertical-align:middle;
        }

        table th{
            background:#f3ebe4;
            color:#4e2e24;
            font-size:16px;
            font-weight:700;
        }

        table td{
            font-size:15px;
            color:#2f2a24;
        }

        table img{
            width:75px;
            height:75px;
            object-fit:cover;
            border-radius:12px;
            border:1px solid #eee;
            display:block;
            background:#f6efe6;
        }

        .status{
            display:inline-block;
            padding:7px 14px;
            border-radius:20px;
            font-size:13px;
            font-weight:600;
        }

        .active{
            background:#d9efe0;
            color:#1d7d3e;
        }

        .inactive{
            background:#f7dddd;
            color:#a33b3b;
        }

        .action-btn{
            display:inline-block;
            padding:9px 14px;
            margin-right:8px;
            text-decoration:none;
            border-radius:8px;
            font-size:14px;
            font-weight:600;
        }

        .edit-btn{
            background:#dcc8b2;
            color:#4e2e24;
        }

        .delete-btn{
            background:#9b5a43;
            color:#fff;
        }

        .edit-btn:hover{
            background:#ccb296;
        }

        .delete-btn:hover{
            background:#7f4634;
        }

        @media (max-width: 768px){
            .page-content{
                padding:18px;
            }

            .occasion-header h1{
                font-size:26px;
            }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="page-content">
        <?php include 'includes/header.php'; ?>

        <div class="occasion-wrapper">
            <div class="occasion-header">
                <h1>Manage Occasions</h1>
                <a href="add-occasion.php" class="add-btn">+ Add Occasion</a>
            </div>

            <div class="table-box">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>

                    <?php if(mysqli_num_rows($result) > 0) { ?>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                                </td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td>
                                    <span class="status <?php echo ($row['status'] == 1) ? 'active' : 'inactive'; ?>">
                                        <?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <a href="edit-occasion.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a>
                                    <a href="delete.php?table=occasion&id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this occasion?')">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">No occasions found.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

</div>



    <?php include('includes/footer.php'); ?>
</body>
</html>