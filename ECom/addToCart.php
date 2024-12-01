<?php
session_start();
include 'conn/connect.php';

// Nếu chưa đăng nhập
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['quantity']) || $_GET['quantity'] <= 0) {
    header('location: index.php?status=failed');
    exit();
}

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $product_id = $_GET['id'];
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user-id'];
    $quantity = $_GET['quantity'];

    // Tìm số lượng sản phẩm của user
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $quantity += $row['quantity'];
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Nếu không có sản phẩm nào
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();

    // Lấy chi tiết giỏ hàng
    $stmt = $conn->prepare("
        SELECT p.title, c.quantity, p.price, (c.quantity * p.price) AS total_price 
        FROM cart c
        INNER JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    
    $cart_details = [];
    while ($cart_row = $cart_result->fetch_assoc()) {
        $cart_details[] = $cart_row;
    }
    $stmt->close();

    // Chuyển chi tiết giỏ hàng vào session để sử dụng trong thông báo
    $_SESSION['cart_details'] = $cart_details;
    $_SESSION['msg'] = "Sản phẩm đã được thêm vào giỏ hàng thành công!";

    // Chuyển hướng về trang chính với thông báo thành công
    header('Location: index.php?status=success');
    exit();
} else {
    header('Location: index.php?status=failed');
    exit();
}
?>
