<?php
session_start();
include("conn/connect.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user-id'];

$total = 0; // Variable to store total price of selected items

include("template/header.php");
include("template/nav.php");
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_products = $_POST['selected_products']; // Mảng sản phẩm được chọn

    // Lưu thông tin vào session
    session_start();
    $_SESSION['selected_products'] = $selected_products;

    // Chuyển hướng tới trang thanh toán
    header("Location: checkout.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <title>Giỏ Hàng</title>
</head>
<body>

<div class="container">
    <h1>Giỏ Hàng</h1>
    <form id="cartForm" action="checkout.php" method="post">
        <table class="table">
            <thead class="bg-warning">
                <tr>
                    <th scope="col">S.Number</th>
                    <th scope="col">Check</th>
                    <th scope="col">Item name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT products.id, products.title, products.price, cart.quantity 
                        FROM cart 
                        JOIN products ON products.id = cart.product_id 
                        WHERE cart.user_id = $user_id";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $index = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        $product_total = $row['price'] * $row['quantity'];
                        $total += $product_total; // Add the price of each product to the total
                        echo "<tr>
                                <th scope='row'>$index</th>
                                <td class='text-center'><input class='form-check-input' type='checkbox' name='selected_products[]' value='{$row['id']}'></td>
                                <td>{$row['title']}</td>
                                <td>&#36;" . number_format($row['price']) . "</td>
                                <td>{$row['quantity']}</td>
                                <td><a href='removeFromCart.php?product-id={$row['id']}'><button type='button' class='btn btn-danger'>Remove</button></a></td>
                              </tr>";
                        $index++;
                    }
                } else {
                    echo "<tr><td colspan='6'>No items in cart.</td></tr>";
                }
                ?>
                <tr>
                    <th scope="row">Total</th>
                    <td colspan="4"></td>
                    <td><button type="button" id="checkoutBtn" class="btn btn-warning text-dark">Checkout</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Hidden input to pass total to checkout -->
        <input type="hidden" name="total_amount" value="<?php echo $total; ?>">

    </form>
</div>

<script>
    // jQuery function to check if any product is selected when the checkout button is clicked
    $('#checkoutBtn').click(function() {
        if ($('input[name="selected_products[]"]:checked').length === 0) {
            alert("Vui Lòng Chọn Sản Phẩm Để Thanh Toán");
        } else {
            $('#cartForm').submit();  // Submit the form if at least one checkbox is selected
        }
    });
</script>

</body>
</html>

<?php include("template/footer.php"); ?>

