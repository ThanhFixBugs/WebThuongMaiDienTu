<?php
session_start();
include "conn/connect.php";

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: login.php");
    exit();
}

// Lấy ID người dùng và quyền từ session, thêm kiểm tra tránh lỗi undefined
$user_id = isset($_SESSION['user-id']) ? $_SESSION['user-id'] : null;
$is_admin = isset($_SESSION['user']) && $_SESSION['user'] === 'thanhkingchess'; // Kiểm tra quyền admin

function getStatus($status_num) {
    switch ($status_num) {
        case 0:
            return "Đã đặt hàng";
        case 1:
            return "Đã vận chuyển";
        case 2:
            return "Đang xử lý";
        case 3:
            return "Đã giao hàng";
        case -1:
            return "Đã hủy";
        default:
            return "Không xác định";
    }
}

include "template/header.php";
include "template/nav.php";
?>

<div class="container my-4">
    <h1>Chi tiết đơn hàng</h1>
    <table class="table">
        <thead class="bg-secondary text-light">
            <tr>
                <th scope="col">Mã đơn hàng</th>
                <th scope="col">Tên người dùng</th> <!-- Hiển thị tên người dùng -->
                <th scope="col">Ngày</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Phương thức thanh toán</th>
                <th scope="col">Tổng</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Nếu là admin, hiển thị tất cả đơn hàng; nếu không, chỉ hiển thị đơn hàng của người dùng
            $sql = $is_admin
                ? "SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id"
                : "SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE orders.user_id = $user_id";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
                $order_id = $row['id'];
                $username = $row['username']; // Tên người dùng
                // Lấy chi tiết đơn hàng từ bảng orderitems và sản phẩm
                $order_items_sql = "SELECT products.id, products.title, orderitems.quantity, products.price 
                                    FROM orderitems
                                    JOIN products ON orderitems.product_id = products.id
                                    WHERE orderitems.order_id = $order_id";
                $order_items_result = mysqli_query($conn, $order_items_sql);
                $order_items = [];
                while ($item = mysqli_fetch_assoc($order_items_result)) {
                    $order_items[] = $item;
                }
                ?>
                <tr>
                    <th class="text-center" scope="row">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <?= $row['id'] ?>
                    </th>
                    <td><?= htmlspecialchars($username) ?></td> <!-- Hiển thị tên đăng nhập -->
                    <td><?= $row['payment_date'] ?></td>
                    <td><?= getStatus($row['order_status']) ?></td>
                    <td><?= $row['payment_method'] ?></td>
                    <td class="price">&#36;<?= number_format($row['total_price'], 0, '', '.') ?></td>
                    <td>
                        <a href="cancelOrder.php?order_id=<?= $row['id'] ?>" class="btn btn-danger">
                            <i class="fa-solid fa-xmark"></i><span class="mx-3">Hủy</span>
                        </a>
                        <button type="button" class="btn btn-primary view-order" data-bs-toggle="modal"
                                data-bs-target="#detailModal" 
                                data-orders="<?= htmlspecialchars(json_encode($order_items), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="fa-solid fa-eye"></i><span class="mx-3">Xem</span>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal Chi tiết Đơn Hàng -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="product_table">
                    <thead>
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên SP</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody id="product_details">
                        <!-- Các sản phẩm sẽ được thêm vào đây -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Hiển thị thông báo thành công/thất bại -->
<?php
if (isset($_SESSION['success_message'])) {
    echo "<script>
        swal('Thành công!', '{$_SESSION['success_message']}', 'success');
    </script>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<script>
        swal('Lỗi!', '{$_SESSION['error_message']}', 'error');
    </script>";
    unset($_SESSION['error_message']);
}
?>

<?php include "template/footer.php"; ?>

<script>
// Lắng nghe sự kiện khi nhấn nút "Xem"
$(document).on('click', '.view-order', function () {
    var orderItems = $(this).data('orders'); // Lấy dữ liệu đơn hàng từ thuộc tính data-orders

    // Xóa nội dung cũ trong modal
    $('#product_details').empty();

    // Duyệt qua các sản phẩm và hiển thị trong bảng modal
    $.each(orderItems, function (index, item) {
        var row = '<tr>' +
                  '<td>' + item.id + '</td>' +
                  '<td>' + item.title + '</td>' +
                  '<td>' + item.quantity + '</td>' +
                  '<td>' + item.price + '</td>' +
                  '</tr>';
        $('#product_details').append(row);
    });
});
</script>
