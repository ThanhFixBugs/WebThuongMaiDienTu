<?php
session_start();
include 'conn/connect.php';
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $date_added = mysqli_real_escape_string($conn, $_POST['date_added']);

    $image = $_POST['name-image']; // Mặc định lấy ảnh cũ
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $image = $fileName; // Cập nhật ảnh mới nếu upload thành công
        }
    }

    $sql = "UPDATE products SET 
            title='$title', 
            price='$price', 
            description='$description', 
            image='$image', 
            date_added='$date_added', 
            status='$status' 
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Cập nhật sản phẩm thành công!";
    } else {
        $_SESSION['msg'] = "Cập nhật sản phẩm thất bại!";
    }
    header("Location: index.php");
    exit();
}


include("template/header.php");
include("template/nav.php");
?>
<form action="" method="POST" class="container my-5" enctype="multipart/form-data">
    <h1 class="text-warmblue">Sửa sản phẩm</h1>
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
    <?php
    $sql = "SELECT * FROM products WHERE id = " . $_GET['id'];
    $res = mysqli_query($conn, $sql);
    $date_added = "";
    if ($row = mysqli_fetch_assoc($res)) {
        ?>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <label class="my-2">Tiêu đề</label>
        <input class="form-control" type="text" name="title" value="<?= $row['title'] ?>">

        <label class="my-2">Giá</label>
        <input class="form-control" type="number" name="price" value="<?= $row['price'] ?> "min = "0" required>

        <label class="my-2">Mô tả</label>
        <input class="form-control" type="text" name="description" value="<?= $row['description'] ?>">

        <label class="my-2">Hình ảnh</label>
        <input class="form-control p-1" type="file" name="file" id="file">
        <input type="hidden" name="name-image" id="name-image" value="<?= $row['image'] ?>">

        <div class="w-48 d-inline-block">
            <label class="my-2">Ngày thêm</label>
<input class="form-control" type="date" name="date_added" id="date" value="<?= $row['date_added'] ?>">
        </div>

        <div class="w-50 d-inline-block">
            <label class="my-2">Trạng thái</label>
            <input class="form-control" type="text" name="status" value="<?= $row['status'] ?>">
        </div>

        <div class="text-center my-3">
            <button class="btn btn-primary my-2 text-center w-25 data-toggle="modal data-target="#exampleModal">
                <i class="fa-solid fa-floppy-disk"></i>
                <span class="px-2">Lưu</span>
            </button>
        </div>

        <?php
        $date_added = $row['date_added'];
    }
    ?>
</form>
<script>
    document.getElementById('file').addEventListener('change', function (event) {
        const fileInput = event.target;
        const fileName = document.getElementById('name-image');

        if (fileInput.files.length > 0) {
            fileName.value = fileInput.files[0].name;
        }
    });

    const datetime = '<?= $date_added ?>';

    const datePart = datetime.split(' ')[0];

    document.getElementById('date').value = datePart;
</script>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php
include("template/footer.php");
?>
