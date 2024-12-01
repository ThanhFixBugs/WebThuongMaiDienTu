<?php
session_start();
include "conn/connect.php";

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']); // Bảo mật bằng cách ép kiểu dữ liệu
    $sql = "UPDATE orders SET order_status = -1 WHERE id = $order_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Đơn hàng #$order_id đã được hủy thành công!";
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi hủy đơn hàng #$order_id. Vui lòng thử lại.";
    }
}

header('Location: myaccount.php');
exit();