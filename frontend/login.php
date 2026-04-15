<?php
session_start();
include '../db.php';

$message = "";
$activeForm = "login";

/* redirect preserve */
$redirect = "";
if (isset($_GET['redirect'])) {
    $redirect = trim($_GET['redirect']);
}
if (isset($_POST['redirect'])) {
    $redirect = trim($_POST['redirect']);
}

/* if already logged in */
if (isset($_SESSION['user_id']) && !isset($_POST['login']) && !isset($_POST['register'])) {
    if (!empty($redirect)) {
        header("Location: " . $redirect);
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}

/* LOGIN */
if (isset($_POST['login'])) {
    $activeForm = "login";

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            $message = "Query Error: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                if (!empty($redirect)) {
                    header("Location: " . $redirect);
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $message = "Wrong password.";
            }
        } else {
            $message = "Email not found.";
        }
    } else {
        $message = "Please fill all login fields.";
    }
}

/* REGISTER */
if (isset($_POST['register'])) {
    $activeForm = "register";

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['reg_email']));
    $password = trim($_POST['reg_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirmPassword)) {

        if ($password !== $confirmPassword) {
            $message = "Passwords do not match.";
        } else {
            $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

            if (!$check) {
                $message = "Query Error: " . mysqli_error($conn);
            } elseif (mysqli_num_rows($check) > 0) {
                $message = "Email already registered.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = "INSERT INTO users (name, email, password) 
                           VALUES ('$name', '$email', '$hashedPassword')";

                if (mysqli_query($conn, $insert)) {
                    $message = "Account created successfully. Please login.";
                    $activeForm = "login";
                } else {
                    $message = "Something went wrong: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $message = "Please fill all register fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | RIWAAYAT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    body{
        font-family:'Poppins',sans-serif;
        min-height:100vh;
        background:
            linear-gradient(rgba(20,14,10,0.50), rgba(20,14,10,0.50)),
            url('../images/login-bg.jpg') center/cover no-repeat;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:24px;
    }

    .auth-wrapper{
        width:100%;
        max-width:1160px;
        min-height:700px;
        display:grid;
        grid-template-columns:1fr 1fr;
        border-radius:28px;
        overflow:hidden;
        background:rgba(255,255,255,0.08);
        backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.12);
        box-shadow:0 20px 60px rgba(0,0,0,0.22);
    }

    .auth-left{
        position:relative;
        background:
            linear-gradient(to bottom, rgba(0,0,0,0.15), rgba(0,0,0,0.45)),
            url('../images/bridal.jpeg') center/cover no-repeat;
        display:flex;
        align-items:flex-end;
        padding:55px;
    }

    .auth-left::before{
        content:"";
        position:absolute;
        inset:0;
        background:
            radial-gradient(circle at top left, rgba(184,146,50,0.25), transparent 35%),
            radial-gradient(circle at bottom right, rgba(255,255,255,0.08), transparent 30%);
    }

    .brand-box{
        position:relative;
        z-index:2;
        color:#fff;
        max-width:460px;
    }

    .brand-logo{
        font-family:'Cinzel',serif;
        font-size:44px;
        letter-spacing:4px;
        color:#e4c16a;
        margin-bottom:18px;
    }

    .brand-box h1{
        font-family:'Cinzel',serif;
        font-size:40px;
        line-height:1.2;
        margin-bottom:18px;
    }

    .brand-box p{
        font-size:15px;
        line-height:1.8;
        color:rgba(255,255,255,0.88);
    }

    .auth-right{
        background:linear-gradient(180deg,#fffaf5 0%,#f7efe5 100%);
        display:flex;
        align-items:center;
        justify-content:center;
        padding:50px 40px;
    }

    .auth-box{
        width:100%;
        max-width:430px;
    }

    .top-text{
        color:#a07a1f;
        font-size:13px;
        text-transform:uppercase;
        letter-spacing:1.4px;
        font-weight:600;
        margin-bottom:10px;
    }

    .auth-box h2{
        font-family:'Cinzel',serif;
        font-size:34px;
        color:#2e2318;
        margin-bottom:10px;
    }

    .subtext{
        font-size:14px;
        color:#6e6255;
        line-height:1.7;
        margin-bottom:24px;
    }

    .switch-tabs{
        display:flex;
        background:#efe5d7;
        border-radius:16px;
        padding:6px;
        margin-bottom:22px;
    }

    .tab-btn{
        flex:1;
        border:none;
        background:transparent;
        height:46px;
        border-radius:12px;
        cursor:pointer;
        font-weight:600;
        font-size:14px;
        color:#6f5f4e;
        transition:0.3s;
    }

    .tab-btn.active{
        background:linear-gradient(135deg,#8b6a11,#b89232,#d2ab47);
        color:#fff;
        box-shadow:0 8px 18px rgba(184,146,50,0.22);
    }

    .message{
        background:#fff4f4;
        border:1px solid #efc4c4;
        color:#b04545;
        padding:12px 14px;
        border-radius:12px;
        font-size:14px;
        margin-bottom:18px;
    }

    .success{
        background:#f2fbf3;
        border-color:#bde0c1;
        color:#2c7a3f;
    }

    .form-panel{
        display:none;
    }

    .form-panel.active{
        display:block;
    }

    .input-group{
        margin-bottom:16px;
    }

    .input-group label{
        display:block;
        margin-bottom:8px;
        font-size:14px;
        font-weight:500;
        color:#3d3328;
    }

    .input-group input{
        width:100%;
        height:52px;
        border:1px solid #ddd0be;
        border-radius:14px;
        background:#fff;
        padding:0 16px;
        font-size:14px;
        outline:none;
        transition:0.3s;
    }

    .input-group input:focus{
        border-color:#b89232;
        box-shadow:0 0 0 4px rgba(184,146,50,0.10);
    }

    .form-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
        flex-wrap:wrap;
    }

    .remember{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#5e5347;
    }

    .remember input{
        accent-color:#b89232;
        width:auto;
        height:auto;
    }

    .forgot-link{
        font-size:13px;
        text-decoration:none;
        color:#9b7920;
        font-weight:500;
    }

    .forgot-link:hover{
        text-decoration:underline;
    }

    .auth-btn{
        width:100%;
        height:54px;
        border:none;
        border-radius:16px;
        background:linear-gradient(135deg,#8b6a11,#b89232,#d2ab47);
        color:#fff;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 12px 24px rgba(184,146,50,0.22);
        transition:0.3s;
    }

    .auth-btn:hover{
        transform:translateY(-2px);
    }

    .divider{
        display:flex;
        align-items:center;
        gap:12px;
        color:#867461;
        font-size:13px;
        margin:22px 0;
    }

    .divider::before,
    .divider::after{
        content:"";
        flex:1;
        height:1px;
        background:#ddd0be;
    }

    .social-login{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .social-btn{
        height:48px;
        border:1px solid #ddd0be;
        border-radius:14px;
        background:#fff;
        color:#3d3328;
        font-size:14px;
        font-weight:500;
        cursor:pointer;
    }

    @media (max-width:991px){
        .auth-wrapper{
            grid-template-columns:1fr;
        }

        .auth-left{
            min-height:300px;
            padding:30px;
        }

        .brand-box h1{
            font-size:30px;
        }

        .auth-right{
            padding:36px 20px;
        }
    }

    @media (max-width:575px){
        body{
            padding:12px;
        }

        .auth-wrapper{
            border-radius:20px;
        }

        .auth-left{
            min-height:250px;
            padding:22px;
        }

        .brand-logo{
            font-size:32px;
        }

        .brand-box h1{
            font-size:24px;
        }

        .auth-box h2{
            font-size:28px;
        }

        .social-login{
            grid-template-columns:1fr;
        }
    }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-left">
            <div class="brand-box">
                <div class="brand-logo">RIWAAYAT</div>
                <h1>Step into timeless elegance.</h1>
                <p>
                    Sign in to explore bridal poshaks, festive collections, saved favourites,
                    and your shopping journey with RIWAAYAT.
                </p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-box">
                <div class="top-text">Welcome</div>
                <h2>Your Account</h2>
                <p class="subtext">Login or create a new account to continue shopping.</p>

                <div class="switch-tabs">
                    <button class="tab-btn <?php echo $activeForm == 'login' ? 'active' : ''; ?>" type="button" onclick="showForm('login')">Login</button>
                    <button class="tab-btn <?php echo $activeForm == 'register' ? 'active' : ''; ?>" type="button" onclick="showForm('register')">Create Account</button>
                </div>

                <?php if($message != ""){ ?>
                    <div class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : ''; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>

                <div id="loginForm" class="form-panel <?php echo $activeForm == 'login' ? 'active' : ''; ?>">
                    <form method="POST" action="">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>

                        <div class="form-row">
                            <label class="remember">
                                <input type="checkbox" name="remember">
                                Remember me
                            </label>

                            <a href="#" class="forgot-link">Forgot password?</a>
                        </div>

                        <button type="submit" name="login" class="auth-btn">Login</button>
                    </form>

                    <div class="divider">or continue with</div>

                    <div class="social-login">
                        <button type="button" class="social-btn">Google</button>
                        <button type="button" class="social-btn">Facebook</button>
                    </div>
                </div>

                <div id="registerForm" class="form-panel <?php echo $activeForm == 'register' ? 'active' : ''; ?>">
                    <form method="POST" action="">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" name="name" placeholder="Enter your full name" required>
                        </div>

                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="reg_email" placeholder="Enter your email" required>
                        </div>

                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" name="reg_password" placeholder="Create password" required>
                        </div>

                        <div class="input-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm password" required>
                        </div>

                        <button type="submit" name="register" class="auth-btn">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showForm(type) {
        const loginForm = document.getElementById("loginForm");
        const registerForm = document.getElementById("registerForm");
        const buttons = document.querySelectorAll(".tab-btn");

        buttons.forEach(btn => btn.classList.remove("active"));

        if (type === "login") {
            loginForm.classList.add("active");
            registerForm.classList.remove("active");
            buttons[0].classList.add("active");
        } else {
            registerForm.classList.add("active");
            loginForm.classList.remove("active");
            buttons[1].classList.add("active");
        }
    }
    </script>

</body>
</html>