<?php
session_start();// step1 session start

// Cache disable
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

include '../db.php';// step2 database connection

// step3 alerady login check
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
//step4 form submit check
if (isset($_POST['login'])) {
    //step5 input lena
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);
//step5query
    $query = "SELECT * FROM admins WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);
//step6 query error check
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }
//step7 email exit check
    if (mysqli_num_rows($result) == 1) {
        //step8 data fecth
        $user = mysqli_fetch_assoc($result);
// step9 password match
        if ($password === $user['password']) {
            // session strat
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['admin_email'] = $user['email'];
//redirect
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Riwaayat</title>
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #fdf6f0, #f6f1eb);
        }

        .login-box{
            width:100%;
            max-width:420px;
            background:#fff;
            padding:35px 30px;
            border-radius:22px;
            box-shadow:0 12px 30px rgba(0,0,0,0.08);
        }

        .login-box h2{
            text-align:center;
            margin-bottom:8px;
            color:#7a5a11;
            font-size:32px;
        }

        .login-box p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .form-group label{
            display:block;
            margin-bottom:8px;
            font-weight:600;
            color:#444;
        }

        .form-group input{
            width:100%;
            padding:13px 14px;
            border:1px solid #ddd;
            border-radius:10px;
            outline:none;
            font-size:15px;
        }

        .form-group input:focus{
            border-color:#b89232;
        }

        .login-btn{
            width:100%;
            border:none;
            background:linear-gradient(135deg, #b89232, #8e6b13);
            color:#fff;
            padding:14px;
            border-radius:12px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:0.3s;
        }

        .login-btn:hover{
            opacity:0.95;
        }

        .error{
            background:#ffe5e5;
            color:#c0392b;
            padding:10px 12px;
            border-radius:10px;
            margin-bottom:18px;
            text-align:center;
            font-size:14px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Riwaayat Admin</h2>
        <p>Login to manage your products</p>

        <?php if (!empty($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="login" class="login-btn">Login</button>
        </form>
    </div>
 <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>