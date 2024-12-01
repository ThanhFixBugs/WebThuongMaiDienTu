<?php
session_start();

include('conn/connect.php');

if (!isset($_SESSION['user-id']) || $_SESSION['user-id'] == "") {
    header("Location:login.php");
    return;
}

if (isset($_GET["product-id"]) && !empty($_GET["product-id"])) {
    $product_id = $_GET['product-id'];
    $user_id = $_SESSION['user-id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
}
header('location: cart.php');