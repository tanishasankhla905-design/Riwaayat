<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT products.* 
    FROM wishlist 
    INNER JOIN products ON wishlist.product_id = products.id
    WHERE wishlist.user_id = '$user_id'
    ORDER BY wishlist.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#fdf6f0;
        }
        .wishlist-section{
            width:90%;
            max-width:1200px;
            margin:50px auto;
        }
        .wishlist-section h2{
            text-align:center;
            margin-bottom:30px;
            color:#b89232;
            font-size:36px;
        }
        .wishlist-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
            gap:25px;
        }
        .product-card{
            background:#fff;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 8px 24px rgba(0,0,0,0.08);
            position:relative;
            transition:0.3s;
        }
        .product-card:hover{
            transform:translateY(-5px);
        }
        .product-card img{
            width:100%;
            height:320px;
            object-fit:cover;
            display:block;
        }
        .product-info{
            padding:16px;
            text-align:center;
        }
        .product-info h3{
            margin:0 0 10px;
            font-size:18px;
        }
        .product-info p{
            margin:0;
            color:#444;
            font-weight:bold;
        }
        .wishlist-btn{
            position:absolute;
            top:12px;
            right:12px;
            width:42px;
            height:42px;
            border:none;
            border-radius:50%;
            background:#b89232;
            color:#fff;
            font-size:22px;
            cursor:pointer;
        }
        .empty-msg{
            text-align:center;
            font-size:20px;
            color:#555;
            padding:50px 0;
        }
    </style>
</head>
<body>

<div class="wishlist-section">
    <h2>My Wishlist</h2>

    <?php if(mysqli_num_rows($query) > 0) { ?>
        <div class="wishlist-grid">
            <?php while($product = mysqli_fetch_assoc($query)) { ?>
                <div class="product-card">
                    <button class="wishlist-btn active" data-id="<?php echo $product['id']; ?>">♥</button>

                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" style="text-decoration:none; color:inherit;">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p>₹<?php echo htmlspecialchars($product['price']); ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="empty-msg">Your wishlist is empty.</div>
    <?php } ?>
</div>

<script>
document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const productId = this.getAttribute('data-id');
        const currentBtn = this;
        const productCard = this.closest('.product-card');

        fetch('add-wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(productId)
        })
        .then(response => response.text())
        .then(data => {
            data = data.trim();

            if (data === 'removed') {
                productCard.remove();

                if (document.querySelectorAll('.product-card').length === 0) {
                    document.querySelector('.wishlist-grid').innerHTML = '';
                    document.querySelector('.wishlist-section').innerHTML += '<div class="empty-msg">Your wishlist is empty.</div>';
                }
            } 
            else if (data === 'login') {
                window.location.href = 'login.php';
            }
        });
    });
});
</script>

</body>
</html>