<?php
session_start();
include("conn/connect.php");
include("template/header.php");
include("template/nav.php");

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['user'])) {
        echo "<p>Vui lòng <a href='login.php'>đăng nhập</a> để chọn sản phẩm.</p>";
    } else {
        // Truy vấn lấy tên phân loại
        $sqlCategory = "SELECT name FROM category WHERE id = $categoryId";
        $categoryResult = mysqli_query($conn, $sqlCategory);
        $categoryRow = mysqli_fetch_array($categoryResult);

        if ($categoryRow) {
            echo '<h1>' . $categoryRow['name'] . '</h1>';

            // Truy vấn lấy các sản phẩm thuộc phân loại này
            $sql = "SELECT * FROM products WHERE category_id = $categoryId";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="row">';
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <div class="product-container">
                        <form action="addToCart.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                            <a href="thongtinsanpham.php?id=<?php echo $row['id'] ?>">
                                <div class="thumbnail">
                                    <img src="images/<?php echo $row['image'] ?>" alt="Lỗi ảnh..." class="img-fluid">
                                </div>
                            </a>

                            <h4 class="title"><?php echo $row['title'] ?></h4>

                            <div class="info">
                                <p class="price pt-3"><b>&#36;<?php echo $row['price'] ?></b></p>
                                <select class="custom-select w-25 mr-3" name="quantity">
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                <button type="submit" class="btn btn-warning translate-x-6">Thêm giỏ hàng</button>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo "<p>Không có sản phẩm thuộc phân loại này.</p>";
            }
        } else {
            echo "<p>Không tìm thấy phân loại giày này.</p>";
        }
    }
} else {
    echo "<p>Không tìm thấy phân loại giày.</p>";
}

include("template/footer.php");
?>
