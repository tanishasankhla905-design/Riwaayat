<?php
include '../db.php';

$message = "";

if (isset($_POST['submit'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category    = mysqli_real_escape_string($conn, $_POST['category']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $color       = mysqli_real_escape_string($conn, $_POST['color']);
    $fabric      = mysqli_real_escape_string($conn, $_POST['fabric']);
    $pattern     = mysqli_real_escape_string($conn, $_POST['pattern']);
    $work_type   = mysqli_real_escape_string($conn, $_POST['work_type']);

    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $folder = "../images/" . $image;

   if (true) {
   
        $query = "INSERT INTO products 
(title, price, image, category, type, color, fabric, pattern, work_type, description)
VALUES 
('$title', '$price', '$image', '$category', '$type', '$color', '$fabric', '$pattern', '$work_type', '$description')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            move_uploaded_file($tmp_name, $folder);
            $message = "Product added successfully!";
        } else {
            $message = "Query Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Please fill required fields.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f8f5f0;
        margin: 0;
        padding: 30px;
    }

    .container {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #b89232;
    }

    .upload-area {
        border: 2px dashed #d4b06a;
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        background: #fffaf3;
        cursor: pointer;
        transition: 0.3s;
        position: relative;
    }

    .upload-area:hover,
    .upload-area.dragover {
        border-color: #b89232;
        background: #fff7e8;
    }

    .upload-area .upload-icon {
        font-size: 42px;
        margin-bottom: 10px;
    }

    .upload-area p {
        color: #888;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .upload-area span {
        font-size: 12px;
        color: #aaa;
    }

    /* hidden real input */
    #imageInput {
        display: none;
    }

    /* PREVIEW BOX */
    .preview-box {
        display: none;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #e0c97a;
        background: #fffaf3;
    }

    .preview-box img {
        width: 100%;
        max-height: 320px;
        object-fit: cover;
        display: block;
        border-radius: 14px;
    }

    .preview-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .preview-actions button {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .change-btn {
        background: #f5ede3;
        color: #7a5a11;
    }

    .change-btn:hover {
        background: #e9ddd0;
    }

    .remove-btn {
        background: #ffe5e5;
        color: #c0392b;
    }

    .remove-btn:hover {
        background: #ffd0d0;
    }


    .message {
        text-align: center;
        margin-bottom: 15px;
        color: green;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 15px;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn {
        background: #b89232;
        color: #fff;
        border: none;
        padding: 14px 25px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    .btn:hover {
        background: #9a7725;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media(max-width:768px) {
        .row {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>Add Product</h2>
        <div style="margin-bottom: 20px;">
            <a href="products.php" style="
        display: inline-block;
        padding: 10px 18px;
        background: #f5ede3;
        color: #7a5a11;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
    " onmouseover="this.style.background='#e9ddd0'" onmouseout="this.style.background='#f5ede3'">
                ← Back to Products
            </a>
        </div>
        <?php if(!empty($message)) { ?>
        <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" placeholder="Enter product title">
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="price" placeholder="Enter price">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">Select Category</option>
                        <option value="poshak">Poshak</option>
                        <option value="saree">Sarees</option>
                        <option value="lehariya">Lehariya</option>
                        <option value="fagun">Fagun</option>
                        <option value="bridal">Bridal</option>
                        <option value="jewellery">Jewellery</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="">Select Type</option>
                    <option value="poshaks">Poshaks</option>
                    <option value="sarees">Sarees</option>
                    <option value="jewellery">Jewellery</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Enter product description"></textarea>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" name="color" placeholder="Enter color">
                </div>

                <div class="form-group">
                    <label>Fabric</label>
                    <input type="text" name="fabric" placeholder="Enter fabric">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Pattern</label>
                    <input type="text" name="pattern" placeholder="Enter pattern">
                </div>

                <div class="form-group">
                    <label>Work Type</label>
                    <input type="text" name="work_type" placeholder="Enter work type">
                </div>
            </div>

            <div class="form-group">
                <label>Product Image</label>

                <input type="file" name="image" id="imageInput" accept="image/*">

                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">🖼️</div>
                    <p><strong>Click to upload</strong> or drag & drop image here</p>
                    <span>PNG, JPG, JPEG, WEBP supported</span>
                </div>

                <div class="preview-box" id="previewBox">
                    <img id="previewImg" src="" alt="Preview">
                    <div class="preview-actions">
                        <button type="button" class="change-btn" id="changeBtn">🔄 Change Image</button>
                        <button type="button" class="remove-btn" id="removeBtn">✕ Remove</button>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <button type="submit" name="submit" class="btn">Add Product</button>
            </div>

        </form>
    </div>
    <script>
    const imageInput = document.getElementById('imageInput');
    const uploadArea = document.getElementById('uploadArea');
    const previewBox = document.getElementById('previewBox');
    const previewImg = document.getElementById('previewImg');
    const changeBtn = document.getElementById('changeBtn');
    const removeBtn = document.getElementById('removeBtn');

    // Click on upload area → open file picker
    uploadArea.addEventListener('click', () => imageInput.click());

    // Change or remove buttons also trigger file picker
    changeBtn.addEventListener('click', () => imageInput.click());

    // When file is selected
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) showPreview(file);
    });

    // Remove image
    removeBtn.addEventListener('click', function() {
        imageInput.value = '';
        previewImg.src = '';
        previewBox.style.display = 'none';
        uploadArea.style.display = 'block';
    });

    // Drag & Drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            // assign dropped file to input
            const dt = new DataTransfer();
            dt.items.add(file);
            imageInput.files = dt.files;
            showPreview(file);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadArea.style.display = 'none';
            previewBox.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
    </script>
</body>

</html>