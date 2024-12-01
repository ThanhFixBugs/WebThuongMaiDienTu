<?php
session_start();
include 'conn/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = 'images/';
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];

        $destination = $uploadDir . $fileName;

        move_uploaded_file($fileTmpPath, $destination);
    }

    $id = $_POST['id'];
    $catid = $_POST['catid'];
    $title = $_POST['title'];
    $price = $_POST['price'];

    if ($price > 0) {
        $description = $_POST['description'];
        $image = $_POST['name-image'];
        $date_added = $_POST['date_added'] . ' 00:00:00';
        $status = $_POST['status'];

        $sql = "INSERT INTO products(catid, title, price, description, image, date_added, status) VALUES
            ($catid, '$title', $price, '$description', '$image', '$date_added', $status)";

        mysqli_query($conn, $sql);
        if ($conn->affected_rows > 0)
            $_SESSION['msg'] = 'Thêm thành công!';
        else
            $_SESSION['msg'] = 'Thêm thất bại!';
    } else {
        $_SESSION['msg'] = 'Giá tiền phải lớn hơn 0';
    }
}


include("template/header.php");
include("template/nav.php");



// Thêm SweetAlert2 xử lý thông báo
if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") {
    echo "<script>
        const doubleCheckIcon =
          '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\" width=\"32\"><path d=\"M342.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 178.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l80 80c12.5 12.5 32.8 12.5 45.3 0l160-160zm96 128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 402.7 54.6 297.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5-12.5 32.8-12.5 45.3 0l256-256z\" fill=\"currentColor\" /></svg>';
        
        Swal.fire({
          title: 'Thông báo',
          text: '" . $_SESSION['msg'] . "',
          icon: 'success',
          iconHtml: doubleCheckIcon,
          customClass: {
            icon: 'rotate-y',
          },
        });
    </script>";
    $_SESSION['msg'] = ""; // Reset message
}
?>

<form action="" method="POST" class="container my-5" enctype="multipart/form-data">
    <h1 class="text-warmblue">Thêm sản phẩm</h1>
    <label class="my-2">Loại</label>
    <select class="form-control" name="catid">
        <?php
        $sql_get_categories = "SELECT * FROM category";
        echo $sql_get_categories;
        $result = mysqli_query($conn, $sql_get_categories);
        while ($row_cat = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row_cat['id'] . "'>" . $row_cat['name'] . "</option>";
        }
        ?>
    </select>
    <input type="hidden" name="id">
    <label class="my-2">Tiêu đề</label>
    <input class="form-control" type="text" name="title" required>

    <label class="my-2">Giá</label>
    <input class="form-control" type="number" name="price" required min="0">

    <label class="my-2">Mô tả</label>
    <input class="form-control" type="text" name="description" required>

    <label class="my-2">Hình ảnh</label>
    <input class="form-control p-1" type="file" name="file" id="file">
    <input type="hidden" name="name-image" id="name-image">

    <div class="w-48 d-inline-block">
        <label class="my-2">Ngày thêm</label>
        <input class="form-control" type="date" name="date_added" required>
    </div>

    <div class="w-50 d-inline-block">
        <label class="my-2">Trạng thái</label>
        <input class="form-control" type="number" name="status" value="0">
    </div>
<div class="text-center my-3">
        <button class="btn btn-primary my-2 text-center w-25">
            <i class="fa-solid fa-square-plus"></i>
            <span class="px-2">Thêm</span>
        </button>
    </div>
</form>
<script>
    document.getElementById('file').addEventListener('change', function (event) {
        const fileInput = event.target;
        const fileName = document.getElementById('name-image');

        if (fileInput.files.length > 0) {
            fileName.value = fileInput.files[0].name;
        }
    });

</script>


<?php
include("template/footer.php");
?>