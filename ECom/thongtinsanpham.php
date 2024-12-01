<?php
session_start();
include("conn/connect.php"); // Kết nối với cơ sở dữ liệu
include("template/header.php");
include("template/nav.php");

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn lấy thông tin sản phẩm theo id
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Hiển thị thông tin sản phẩm
        ?>
        <html>
            <head>
                <style>
                    /* Đảm bảo ảnh trong phần chi tiết sản phẩm vừa vặn và giữ tỉ lệ */
.img-fluid {
    width: 500px;
    height: 500px;
    max-height: 600px;  /* Giới hạn chiều cao của ảnh để phù hợp với giao diện */
    object-fit: contain; /* Đảm bảo ảnh không bị bóp méo */
    padding-bottom: 50px;
}

/* Điều chỉnh chiều rộng của cột chứa ảnh nếu cần */
.col-md-6 {
    display: flex;
    justify-content: center;
    align-items: center;
}
/* Căn chỉnh phần chi tiết sản phẩm bên phải ảnh */
.row.mt-4 {
    display: flex;
    align-items: center;  /* Căn giữa dọc nội dung trong cột bên phải */
    justify-content: flex-start; /* Đảm bảo nội dung bắt đầu từ đầu cột */
}

/* Đảm bảo phần thông tin chi tiết được căn giữa */
.col-md-6 {
    display: flex;
    flex-direction: column;  /* Đặt các phần tử theo chiều dọc */
    justify-content: center; /* Căn giữa nội dung theo chiều dọc */
    align-items: flex-start; /* Căn các phần tử về phía trái */
    padding-left: 30px;  /* Thêm khoảng cách giữa ảnh và thông tin */
}

/* Căn giữa các dòng bên trong .col-md-6 */
.list-group-item {
    text-align: left;
    padding: 10px;
}

/* Giới hạn chiều cao cho các phần tử để tránh việc chúng bị lệch */
h2, h4 {
    text-align: left;
}

/* Tùy chỉnh các button và nội dung */
.mt-3 {
    margin-top: 20px;
}



                </style>
            </head>
                <body>
                    
        <div class="container mt-5">
            <!-- Header -->
            <div class="text-center">
                <h1 class="mb-3"><?php echo $row['title']; ?></h1>
                <p class="text-muted">Hiệu suất đỉnh cao - Thiết kế thời thượng</p>
            </div>

            <!-- Main Content -->
            <div class="row mt-4">
                <!-- Image Section -->
                <div class="col-md-6">
                    <img src="images/<?php echo $row['image']; ?>" alt="Giày Beyono Thunder" class="img-fluid rounded">
                </div>

                <!-- Details Section -->
                <div class="col-md-6">
                    <h2>Thông tin chi tiết</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">💨 <strong>Chất liệu:</strong> <?php echo $row['description']; ?></li>
                        <!-- Bạn có thể thêm các chi tiết khác nếu có trong cơ sở dữ liệu -->
                    </ul>
                    <div class="mt-3">
                        <h4 class="text-danger">Giá: $<?php echo number_format($row['price'], 0, ',', '.'); ?></h4>
                        <a href="index.php" class="btn btn-primary mt-2">Mua Ngay</a>
                    </div>
                </div>
            </div>
        </div>
                </body>
        </html>
        
        <?php
    } else {
        echo "<p>Sản phẩm không tồn tại.</p>";
    }
} else {
    echo "<p>Không tìm thấy thông tin sản phẩm.</p>";
}

include("template/footer.php");
?>
