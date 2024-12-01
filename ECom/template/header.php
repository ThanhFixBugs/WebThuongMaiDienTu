<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
<!-- SweetAlert JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>



    
    <title>Thanh Sports</title>
    <link rel="icon" type="image" href="images/icon.webp">
    <style>
        .product-container {
            width: 90px;
            height: 190px;
        }
    </style>
</head>
<header class="header mx-3">
  <div class="overlay"></div>
  <h1 class="highlighted-text-shadow">THANH SPORTS</h1>
  <a href="index.php"><img src="images/logoo.png" alt="Logo"></a>
</header>

<?php
if (isset($_SESSION['user']) && $_SESSION['user'] == 'thanhkingchess') {
    ?>
    <div class="d-flex bg-danger justify-content-center text-light align-items-center my-3 py-3 rounded mx-3">
        Đang truy cập quyền admin
    </div>
    <?php
}
?>
