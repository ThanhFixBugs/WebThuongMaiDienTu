<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_comm";


$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//chọn database 
$select_db = mysqli_select_db($conn, $dbname);
if (!$select_db) {
    die("Kết nối cơ sở dữ liệu không thành công!" . mysqli_error($conn));
}

mysqli_query($conn, "SET CHARACTER SET utf8");
//echo 'Ket noi thanh cong';