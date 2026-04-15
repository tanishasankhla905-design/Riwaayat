<?php
include 'admin-check.php';
include '../db.php';

$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$allowed_tables = ['occasion'];

if (!in_array($table, $allowed_tables) || $id <= 0) {
    die("Invalid request");
}

$query = "SELECT * FROM $table WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Record not found");
}

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $status = (int) $_POST['status'];
    $oldImage = $row['image'];
    $newImage = $oldImage;

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        if (move_uploaded_file($tmpName, "../images/" . $imageName)) {
            if (!empty($oldImage) && file_exists("../images/" . $oldImage)) {
                unlink("../images/" . $oldImage);
            }
            $newImage = $imageName;
        }
    }

    $updateQuery = "UPDATE $table SET 
                    title='$title',
                    image='$newImage',
                    status='$status'
                    WHERE id=$id";

    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        header("Location: occasion.php");
        exit;
    } else {
        die("Update Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Occasion</title>
    <style>
        .form-wrapper{
            padding:30px;
            background:#f8f4ef;
            min-height:100vh;
        }
        .form-box{
            max-width:700px;
            background:#fff;
            margin:auto;
            padding:30px;
            border-radius:18px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }
        .form-box h2{
            margin-bottom:20px;
            color:#4e2e24;
            font-family:"Playfair Display", serif;
        }
        .form-group{
            margin-bottom:18px;
        }
        .form-group label{
            display:block;
            margin-bottom:8px;
            font-weight:600;
            color:#4e2e24;
        }
        .form-group input,
        .form-group select{
            width:100%;
            padding:12px 14px;
            border:1px solid #ddd;
            border-radius:10px;
            outline:none;
        }
        .form-group img{
            width:100px;
            height:100px;
            object-fit:cover;
            border-radius:10px;
            margin-top:10px;
        }
        .submit-btn{
            background:#8f5242;
            color:#fff;
            border:none;
            padding:12px 22px;
            border-radius:10px;
            cursor:pointer;
            font-size:16px;
            font-weight:600;
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <?php include 'includes/header.php'; ?>

    <div class="form-wrapper">
        <div class="form-box">
            <h2>Edit Occasion</h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Current Image</label>
                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                </div>

                <div class="form-group">
                    <label>New Image</label>
                    <input type="file" name="image">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="1" <?php if($row['status'] == 1) echo 'selected'; ?>>Active</option>
                        <option value="0" <?php if($row['status'] == 0) echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" name="update" class="submit-btn">Update Occasion</button>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

</body>
</html>