<?php
session_start();
include("conn/connect.php");

// Make sure user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: login.php");
    exit();
}


$_SESSION['checkout-info'] = $_POST;
$_SESSION['products-id'] = $_POST['products-id'];

$total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Thanh toán qua Paypal</title>
</head>

<body>
    <?php
    if ($_POST['payment-method'] == 'cash') {
        header('location: success.php');
    } else {
        ?>


        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="form-autosubmit">
            <!-- Nhập địa chỉ email người nhận tiền (người bán) -->
            <input type="hidden" name="business" value="sb-xc472i34013223@business.example.com">
            <!-- Tham số cmd có giá trị _xclick chỉ rõ cho Paypal biết là người dùng nhấn nút thanh toán -->
            <input type="hidden" name="cmd" value="_xclick">
            <!-- Thông tin mua hàng -->
            <input type="hidden" name="item_name" value="HoaDonMuaHang">

            <!-- Số tiền, nhập vào nhưng không cho sửa (readonly) -->
            <!-- <label for="amount">Số tiền hóa đơn: </label> -->
            <input type="hidden" id="amount" name="amount" value="<?= $total_amount; ?>" readonly>

            <!-- Loại tiền -->
            <input type="hidden" name="currency_code" value="USD">

            <!-- Đường link cung cấp cho Paypal biết để sau khi xử lý thành công nó sẽ chuyển về link này -->
            <input type="hidden" name="return" value="http://localhost/ECom/success.php">

            <!-- Đường link cung cấp cho Paypal biết để nếu xử lý KHÔNG thành công nó sẽ chuyển về link này -->
            <input type="hidden" name="cancel_return" value="http://localhost/ECom/Loi.php">

            <!-- <input type="submit" name="submit" value="Thanh toán qua Paypal"> -->
        </form>
        <?php
    }
    ?>
    <script>
        function autoSubmit() {
            const form = document.getElementById('form-autosubmit');
            form.submit();
        }

        window.onload = autoSubmit;

    </script>
</body>

</html>