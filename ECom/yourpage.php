<?php
session_start();
include("conn/connect.php");
include("template/header.php");
include("template/nav.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Loại bỏ khoảng trắng thừa

if (!empty($search)) {
    // Câu truy vấn SQL với LOWER để không phân biệt hoa thường
    $sql = "SELECT * FROM products WHERE LOWER(title) LIKE LOWER('%$search%')";
    $result = mysqli_query($conn, $sql);
    $count = 0;

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row d-flex justify-content-center">';
        while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="product-container">
                <form action="addToCart.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <div class="thumbnail">
                        <a href="thongtinsanpham.php?id=<?php echo $row['id'] ?>">
                            <img src="images/<?php echo $row['image'] ?>" alt="Lỗi ảnh...">
                        </a>
                    </div>
                    <h4 class="title"><?php echo $row['title'] ?></h4>
                    <div class="info">
                        <p class="price pt-3"><b>&#36;<?php echo $row['price'] ?></b></p>
                        <select class="custom-select w-25 mr-3" name="quantity">
                            <option selected value="1">1</option>
                        </select>
                        <button type="submit" class="btn btn-warning translate-x-6">Thêm giỏ hàng</button>
                    </div>
                </form>
            </div>
            <?php
            $count++;
        }
        echo '</div>';
    } else {
        echo '<p class="text-center">Không tìm thấy sản phẩm nào với từ khóa: ' . htmlspecialchars($search) . '</p>';
    }
}
?>

        </div>
        <div class="col-1"></div>
    </div>
</div>

<script>
    function getConfirm(productName) {
        return confirm('Bạn có chắc muốn xóa sản phẩm\n' + productName);
    }
</script>

<?php
include("template/footer.php");
?>
