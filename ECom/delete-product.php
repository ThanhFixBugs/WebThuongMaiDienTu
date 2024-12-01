<?php
session_start();
include("conn/connect.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Kiểm tra xem sản phẩm có trong đơn hàng chưa
    $check_order_sql = "SELECT COUNT(*) as order_count FROM orderitems WHERE product_id = '$id'";
    $check_order_result = mysqli_query($conn, $check_order_sql);
    $order_row = mysqli_fetch_assoc($check_order_result);

    if ($order_row['order_count'] > 0) {
        // Sản phẩm đã được đặt hàng, không thể xóa
        echo json_encode([
            "success" => false,
            "message" => "Không thể xóa sản phẩm vì sản phẩm đã được đặt hàng!"
        ]);
    } else {
        // Nếu sản phẩm chưa được đặt hàng, xóa sản phẩm
        $delete_sql = "DELETE FROM products WHERE id = '$id'";
        mysqli_query($conn, $delete_sql);

        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode([
                "success" => true,
                "message" => "Xóa sản phẩm thành công!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Xóa thất bại! Sản phẩm có thể không tồn tại."
            ]);
        }
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Không tìm thấy thông tin sản phẩm cần xóa."
    ]);
}

mysqli_close($conn);
?>
