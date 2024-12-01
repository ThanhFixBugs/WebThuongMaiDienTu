<?php
session_start();
include('conn/connect.php');

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
    $sql = "SELECT * FROM users WHERE id = " . $_SESSION['user-id'];
    $res = mysqli_query($conn, $sql);
    $user = $res->fetch_assoc();
} else {
    header('Location: login.php');
    exit();
}

$mess = "";
$flag = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu (trừ người dùng hiện tại)
    $sql = "SELECT id FROM users WHERE email = '$email' AND id != " . $user['id'];
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        // Nếu email đã tồn tại trong hệ thống
        $mess = "Email đã được sử dụng bởi người dùng khác!";
    } else {
        // Email không trùng, kiểm tra mật khẩu cũ
        if (!empty($old_password) && password_verify($old_password, $user['password'])) {
            // Nếu người dùng có nhập mật khẩu mới, thì cập nhật cả email và mật khẩu
            if (!empty($new_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET email = '$email', password = '$new_hashed_password' WHERE id = " . $user['id'];
            } else {
                // Nếu không nhập mật khẩu mới, chỉ cập nhật email
                $sql = "UPDATE users SET email = '$email' WHERE id = " . $user['id'];
            }

            if (mysqli_query($conn, $sql)) {
                $mess = "Cập nhật thành công";
                $flag = true;
            } else {
                $mess = "Cập nhật thất bại";
            }
        } else {
            $mess = "Vui lòng nhập mật khẩu cũ chính xác để xác nhận!";
        }
    }
}

include('template/header.php');
include('template/nav.php');
?>

<div class="container d-flex my-3 d-flex justify-content-center">
    <div class="w-75 p-5 border border-dark">
        <h1 class="text-warning text-center">Chỉnh sửa thông tin</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" class="form-control" placeholder="Nhập tên người dùng" name="username" id="username"
                    required value="<?= htmlspecialchars($user['username']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" placeholder="Nhập email" name="email" id="email" required
                    value="<?= htmlspecialchars($user['email']); ?>">
            </div>
            <div class="form-group">
                <label for="old_password">Mật khẩu cũ:</label>
                <input type="password" class="form-control" placeholder="Nhập mật khẩu cũ để xác nhận"
                    name="old_password" id="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" class="form-control" placeholder="Nhập mật khẩu mới nếu muốn đổi"
                    name="new_password" id="new_password">
            </div>

            <?php if (!empty($mess)): ?>
                <div class="alert alert-<?php echo $flag ? "success" : "danger"; ?> mt-2">
                    <strong>Thông báo</strong> <?php echo $mess; ?>.
                </div>
            <?php endif; ?>

            <div class="text-center">
                <button type="submit" class="btn btn-warning w-25"><i class="fa-solid fa-floppy-disk"></i><span
                        class="px-2">Lưu</span></button>
            </div>
        </form>
    </div>
</div>

<?php
include('template/footer.php');
?>
