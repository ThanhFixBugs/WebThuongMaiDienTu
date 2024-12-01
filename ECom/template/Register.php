<script>
    function validatePassword() {
        var password = document.getElementById("password").value;
        var repassword = document.getElementById("repassword").value;
        var message = document.getElementById("error-message");

        if (password === repassword) {
            message.textContent = ""; // Xóa thông báo lỗi
            return true; // Cho phép gửi biểu mẫu
        } else {
            message.textContent = "Mật khẩu không khớp!"; // Hiển thị thông báo lỗi
            return false; // Ngăn gửi biểu mẫu
        }
    }
</script>

<?php
session_start();
include('conn/connect.php');
include('template/header.php');
include('template/nav.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    $e = $_POST['email'];

    // Sử dụng prepared statements để ngăn chặn SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $u, $e);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $mess = "Tên người dùng hoặc email đã tồn tại";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, type) VALUES (?, ?, ?, 'adr')");
        $stmt->bind_param("sss", $u, $p, $e);
        if ($stmt->execute()) {
            $mess = "Đăng ký thành công";
        } else {
            $mess = "Đăng ký thất bại";
        }
    }

    $stmt->close();
}
?>

<div class="container">
    <div style="width: 50%; margin: 0 auto;">
        <h1 class="text-warning text-center">Đăng ký</h1>
        <form action="" method="POST" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" class="form-control" placeholder="Nhập tên người dùng" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" placeholder="Nhập email" name="email" required>
            </div>
            <div class="form-group">
                <label for="pwd">Mật khẩu:</label>
                <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password" id="password"
                    required>
            </div>
            <div class="form-group">
                <label for="pwd">Nhập lại mật khẩu:</label>
                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="repassword"
                    id="repassword" required>
                <span id="error-message" class="error" style="color: red;"></span><br><br>

                <?php if (isset($mess) && $mess != ""): ?>
                    <div class="alert alert-danger"></div>
                    <strong>Thông báo</strong> <?php echo $mess; ?>.
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin: 0 auto;">
                <button type="submit" class="btn btn-warning">Đăng ký</button>
                <a href="login.php" class="btn btn-outline-warning">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

<?php include('template/footer.php'); ?>