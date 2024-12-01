<?php
session_start();
include('conn/connect.php'); // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (isset($_SESSION['user'])) {
    // Kiểm tra nếu có tham số redirect trong URL
    if (isset($_GET['redirect'])) {
        $redirect_url = urldecode($_GET['redirect']);
        header("Location: $redirect_url"); // Chuyển hướng về URL đã mã hóa
        exit();
    } else {
        // Nếu không có tham số redirect, chuyển về trang chính
        header("Location: index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sử dụng prepared statements để ngăn chặn SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user'] = $user['username'];
            $_SESSION['user-id'] = $user['id'];

            // Kiểm tra nếu có tham số redirect trong URL
            if (isset($_GET['redirect'])) {
                $redirect_url = urldecode($_GET['redirect']);
                header("Location: $redirect_url");
            } else {
                header("Location: index.php"); // Chuyển về trang chính
            }
            exit();
        } else {
            $error = "Mật khẩu không đúng.";
        }
    } else {
        $error = "Tên người dùng không tồn tại.";
    }

    $stmt->close();
}

include('template/header.php'); // Tiêu đề của trang
include('template/nav.php'); // Thanh điều hướng
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thanh Sports</title>
    <link rel="icon" type="image" href="images/icon.webp">
    <meta name="description" content="Created a login form with an SVG animated avatar that responds to the input in the email field and password field with hand over eyes.">
    <meta name="keywords" content="animated login,svg animated login,avatar login,animated svg login form">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        /* Overlay làm mờ ảnh nền */
        .bg {
            position: relative;
            width: 50%;
            height: 400px;
            border-radius: 0 10px 10px 0;
            overflow: hidden;
        }
        .bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/logo1.png');
            background-size: cover;
            background-position: center;
            filter: blur(0.5px);
            z-index: 1;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2;
        }
        .content {
            position: relative;
            z-index: 3;
            text-align: center;
        }
        .icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            margin-top: 80px;
        }
        .text {
            color: yellow;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .btn1 {
            background-color: #ff5722;
            color: black;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #e64a19;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.4);
        }
        .button-87 {
            margin: 10px;
            padding: 15px 30px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            border-radius: 10px;
            display: block;
            border: 0px;
            font-weight: 700;
            box-shadow: 0px 0px 14px -7px #f09819;
            background-image: linear-gradient(45deg, #FF512F 0%, #F09819  51%, #FF512F  100%);
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }
        .button-87:hover {
            background-position: right center;
            color: #fff;
            text-decoration: none;
        }
        .button-87:active {
            transform: scale(0.95);
        }
        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .input-group .icon {
            position: absolute;
            left: 10px;
            top: 10%;
            transform: translateY(-50%);
            color: #666;
            font-size: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 0.8rem 0.8rem 0.8rem 2.5rem;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }
        .input-group input:focus {
            border-color: #7b42f6;
        }
    </style>
</head>
<body>
    <div class="container d-flex my-5">
        <div class="w-50 p-5 border border-darkblue">
            <h2 class="text-warmblue mb-3">Đăng nhập</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <strong>Thông báo:</strong> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="input-group">
                    <label for="username">
                        <i class="icon">👤</i>
                        Username
                    </label>
                    <input type="text" id="username" placeholder="Type your username" name="username">
                </div>
                <div class="input-group">
                    <label for="password">
                        <i class="icon">🔒</i>
                        Password
                    </label>
                    <input type="password" id="password" placeholder="Type your password" name="password">
                </div>
                <div class="text-center">
                    <button class="button-87" role="button">Đăng Nhập</button>
                </div>
            </form>
        </div>

        <div class="bg">
            <div class="overlay"></div>
            <div class="content text-center">
                <img class="icon" src="images/logoo.png" alt="Logo">
                <h4 class="text">Chào mừng bạn đến Thanh Sports</h4>
                <p class="text">Chưa có tài khoản?</p>
                <a href="Register.php" class="btn1">Đăng ký</a>
            </div>
        </div>
    </div>
    <?php include('template/footer.php'); ?>
</body>
</html>
