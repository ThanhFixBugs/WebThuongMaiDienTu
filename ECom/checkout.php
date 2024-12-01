<?php
session_start();
include("conn/connect.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user-id'];

// Initialize total order amount
$total = 0;

// Get selected products from POST
$selected_products = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];

if (empty($selected_products)) {
    echo "<p>No products selected. Please go back to the cart and select products to proceed.</p>";
    exit();
}

// Convert selected products array to a comma-separated string for SQL IN clause
$product_ids = implode(",", array_map('intval', $selected_products));

include("template/header.php");
include("template/nav.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <title>Checkout Page</title>
    <script>
    function validatePayment() {
        // Get references to payment options and terms checkbox
        const cashOption = document.getElementById('cash');
        const paypalOption = document.getElementById('paypol');
        const termsCheckbox = document.getElementById('terms');

        // Check if at least one payment option is selected
        if (!cashOption.checked && !paypalOption.checked) {
            alert("Vui lòng chọn một phương thức thanh toán.");
            return false;
        }

        // Check if terms checkbox is checked
        if (!termsCheckbox.checked) {
            alert("Vui lòng đọc và chấp nhận tất cả điều khoản.");
            return false;
        }

        // If all validations pass, allow form submission
        return true;
    }

    function validateForm() {
    const requiredFields = [
        "firstName",
        "lastName",
        "address",
        "deliveryAddress",
        "city",
        "postcode",
        "phone"
    ];

    for (let field of requiredFields) {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            alert("Vui lòng nhập đầy đủ thông tin vào tất cả các trường.");
            input.focus();
            return false;
        }
    }
    return true;
}

</script>

</head>
<body>
    <div class="container">
        <h2>Chi Tiết Thanh Toán</h2>
        <form action="thanhtoanpaypal.php" method="post" onsubmit="return validatePayment()">
            <div class="form-group">
                <label for="country">Quốc Gia</label>
                <select class="form-control" id="country" name="country">
                    <option>Chọn Quốc Gia</option>
                    <option>Việt Nam</option>
                    <option>Nhật Bổn</option>
                    <option>Pháp</option>
                    <option>Anh</option>
                    <option>Hàn</option>
                    <!-- Add more country options as needed -->
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">Tên</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Họ</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                </div>
            </div>
            <div class="form-group">
                <label for="company">Công Ty</label>
                <input type="text" class="form-control" id="company" name="company">
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="deliveryAddress">Địa Chỉ Nhận Hàng</label>
                <input type="text" class="form-control" id="deliveryAddress" name="deliveryAddress" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">Thành Phố</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="state">Tình Trạng Đơn Hàng</label>
                    <input type="text" class="form-control" id="state" name="state">
                </div>
                <div class="form-group col-md-2">
                    <label for="postcode">Mã Bưu Chính</label>
                    <input type="text" class="form-control" id="postcode" name="postcode" required>
                </div>
            </div>
            <div class="form-group">
                <label for="phone">Điện Thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
        

        <h2>Sản Phẩm Đặt Hàng</h2>
        <table class="table">
            <thead class="bg-warning">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Tên Hàng</th>
                    <th scope="col">Số Lượng</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Tổng Cộng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch only selected products
                $sql = "SELECT products.id, products.title, products.price, cart.quantity 
                        FROM cart 
                        JOIN products ON products.id = cart.product_id 
                        WHERE cart.user_id = $user_id AND products.id IN ($product_ids)";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $index = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_total = $row['price'] * $row['quantity'];
                        $total += $product_total;
                        echo "<tr>
                                <input type='hidden' name='products-id[]' value='".$row['id']."'>
                                <input type='hidden' name='quantities[]' value='".$row['quantity']."'>
                                <input type='hidden' name='price[]' value='".$row['price']."'>
                                <th scope='row'>$index</th>
                                <td>{$row['title']}</td>
                                <td>{$row['quantity']}</td>
                                <td>" . number_format($row['price']) . "</td>
                                <td>" . number_format($product_total) . "</td>
                              </tr>";
                        $index++;
                    }
                }
                ?>
                
            </tbody>
        </table>

        <h2>Đơn Hàng Của Bạn</h2>
        <table class="table">
            <tr>
                <td>Tổng Cộng Đặt Hàng</td>
                <td><?php echo number_format($total); ?> &#36;</td>
            </tr>
            <tr>
                <td>Vật Chuyển Và Xử Lý</td>
                <td>Free</td>
            </tr>
            <tr>
                <td>Tổng Đơn Hàng</td>
                <td><?php echo number_format($total); ?> &#36;</td>
            </tr>
        </table>

        <h2>Phương Thức Thanh Toán</h2>
        <!-- <form action="thanhtoanpaypal.php" method="post" onsubmit="return validatePayment()"> -->
    <table class="table">
        <tbody>
            <tr>
                <td>
                    <input type="radio" value="cash" name="payment-method" id="cash">
                    <label class="mx-2" for="cash">Tiền mặt</label>
                    <p>Make your payment directly into our bank account. Plese use your Order ID as the
                                    payment reference. Your order won't be shipped until the funds have cleared in your
                                    account</p>
                </td>
                <td>
                    <input type="radio" value="PayPal" name="payment-method" id="paypol">
                    <label class="mx-2" for="paypol">PayPal</label>
                    <p>Pay via PayPal. You can pay with your credit and if you don't have a PayPol account
                                </p>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Hidden field to pass the total order amount to PayPal -->
    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">

    <input type="checkbox" name="terms" id="terms">
    <label class="mx-2" for="terms">Tôi đã đọc và chấp nhận tất cả điều khoản</label>

    <div>
        <button type="submit" class="btn btn-primary mt-3">Thanh Toán Ngay</button>
    </div>
</form>


    </div>
</body>
</html>

<?php include("template/footer.php"); ?>
