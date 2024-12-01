<?php
session_start();
include 'conn/connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user'] == "" || !isset($_SESSION['user-id']) || $_SESSION['user-id'] == "") {
    header("Location:login.php");
    exit();
}

$user_id = $_SESSION['user-id'];

$checkout_info = $_SESSION['checkout-info'];

$payment_method = $checkout_info['payment-method'];

/*
 * 0: order placed
 * 1: dispatched
 * 2: in progress
 * 3: deliveried
 * -1: cancelled 
 */

$country = $checkout_info['country'];
$first_name = $checkout_info['firstName'];
$last_name = $checkout_info['lastName'];
$company = $checkout_info['company'];
$address = $checkout_info['address'];
$city = $checkout_info['city'];
$state = $checkout_info['state'];
$postcode = $checkout_info['postcode'];
$phone = $checkout_info['phone'];
$total_price = $checkout_info['total_amount'];

//update info of user
$sql_get_user = "SELECT * FROM usersmeta WHERE user_id = $user_id";
$res = mysqli_query($conn, $sql_get_user);
if ($res && $res->num_rows > 0) {
    $sql_update_usermeta = "UPDATE usersmeta SET
    country = '$country',
    first_name = '$first_name',
    last_name = '$last_name',
    company = '$company',
    address = '$address',
    city = '$city',
    state = '$state',
    postcode = '$postcode',
    phone = '$phone'
    WHERE user_id = $user_id";

    echo $sql_update_usermeta;
    mysqli_query($conn, $sql_update_usermeta);
} else {
    $sql_insert_usersmeat = "INSERT INTO usersmeta(user_id, country, first_name, last_name, company, address, city, state, postcode, phone)
    VALUES ($user_id, '$country', '$first_name', '$last_name', '$company', '$address', '$city', '$state', '$postcode', '$phone')";

    mysqli_query($conn, $sql_insert_usersmeat);
}

//add billing details to database
$products_id = $_SESSION['products-id'];
date_default_timezone_set('Asia/Ho_Chi_Minh');
$current = date('Y-m-d H-i-s');


$sql_add_orders = "INSERT INTO orders(user_id, payment_date, order_status, payment_method, total_price)
                    VALUES ($user_id, '$current', 0, '$payment_method', $total_price)";

mysqli_query($conn, $sql_add_orders);

$last_order_id = mysqli_insert_id($conn);

//add order items
$sql_add_order_items = "INSERT INTO orderitems(order_id, product_id, quantity, product_price) VALUES";

$products_id = $checkout_info['products-id'];
$quantities = $checkout_info['quantities'];
$price = $checkout_info['price'];

$len = count($products_id);
for ($i = 0; $i < $len; $i++) {
    $sql_add_order_items = $sql_add_order_items . "($last_order_id, $products_id[$i], $quantities[$i], $price[$i]),";
}

$sql_add_order_items = substr_replace($sql_add_order_items, ';', -1);

mysqli_query($conn, $sql_add_order_items);
$sql_delete_cart = "DELETE FROM cart WHERE  user_id = $user_id AND product_id IN (" . implode(',', $products_id) . ")";
mysqli_query($conn, $sql_delete_cart);

header('location: myaccount.php');