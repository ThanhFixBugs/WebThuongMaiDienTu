<?php
session_start();
include("conn/connect.php");
include("template/header.php");
include("template/nav.php");

// Kiểm tra xem người dùng có chọn phân loại nào không
$category_filter = "";
if (isset($_GET['category'])) {
    $category_id = mysqli_real_escape_string($conn, $_GET['category']);
    // Lọc sản phẩm theo catid
    $category_filter = " WHERE products.catid = '$category_id'";
}

// Truy vấn để lấy danh sách sản phẩm theo phân loại
$sql = "SELECT products.*, category.name AS category_name
        FROM products
        INNER JOIN category ON products.catid = category.id" . $category_filter;
$result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">

            <?php
            if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Thông báo",
            text: "' . $_SESSION['msg'] . '",
        });
    </script>';
    $_SESSION['msg'] = "";
}
            ?>

            <div class="row d-flex justify-content-center">
                <?php
                $count = 0;
                while ($row = mysqli_fetch_array($result)) {
                    if ($count == 0) {
                        echo '<div class="row d-flex justify-content-center">'; // Mở hàng mới
                    }
                    ?>
                    <div class="product-container">
                        <form action="addToCart.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                            <!-- Kiểm tra nếu chưa đăng nhập, chuyển đến login.php -->
                            <?php if (!isset($_SESSION['user'])): ?>
                                <a href="login.php?redirect=<?php echo urlencode('thongtinsanpham.php?id=' . $row['id']) ?>">
                                    <div class="thumbnail">
                                        <img src="images/<?php echo $row['image'] ?>" alt="Lỗi ảnh..." class="img-fluid">
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="thongtinsanpham.php?id=<?php echo $row['id'] ?>">
                                    <div class="thumbnail">
                                        <img src="images/<?php echo $row['image'] ?>" alt="Lỗi ảnh..." class="img-fluid">
                                    </div>
                                </a>
                            <?php endif; ?>

                            <h4 class="title"><?php echo $row['title'] ?></h4>
                            <div class="info">
                                <p class="price pt-3"><b>&#36;<?php echo $row['price'] ?></b></p>
                                <div class="d-flex align-items-center">
                                    <input type="number" name="quantity" class="form-control w-25" min="1" value="1" aria-label="Quantity">
                                    <!-- Nút thêm giỏ hàng sử dụng icon -->
                                    <button type="submit" class="btn btn-warning ml-3" aria-label="Add to Cart">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <?php
                                if (isset($_SESSION['user']) && $_SESSION['user'] == 'thanhkingchess') {
                                    ?>
                                    <div class="my-3">
                                        <a class="btn btn-warning w-100 mr-3" href="modify-product.php?id=<?= $row['id'] ?>">
                                            <i class="fa-solid fa-pen"></i><span class="ml-1">Sửa đổi</span>
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger w-100" onclick="confirmDelete('<?= $row['title'] ?>', 'delete-product.php?id=<?= $row['id'] ?>')">
                                            <i class="fa-solid fa-trash"></i><span class="ml-2">Xóa sản phẩm</span>
                                        </button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>

                    <?php
                    $count++;
                    if ($count == 4) {
                        echo '</div>'; // Đóng hàng sau mỗi 4 sản phẩm
                        $count = 0;
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>

<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(productName, deleteUrl) {
        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Bạn sẽ không thể khôi phục lại sản phẩm: " + productName,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(deleteUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Đã xóa!", `Sản phẩm ${productName} đã được xóa.`, "success")
                                .then(() => location.reload());
                        } else {
                            Swal.fire("Không thể xóa!", data.message, "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Lỗi!", "Đã xảy ra lỗi trong quá trình xóa.", "error");
                    });
            }
        });
    }
</script>

<?php
include("template/footer.php");
?>
