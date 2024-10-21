<?php
require('admin/inc/db_config.php');
require('admin/inc/essentails.php');

require('inc/vnpay/config.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (isset($_POST['pay_now'])) {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    $checkSum = "";
    $vnp_TxnRef = 'ORD_' . $_SESSION['uId'] . random_int(11111, 99999); // order_id
    $vnp_CusId = $_SESSION['uId'];
    $vnp_Amount = $_SESSION['room']['payment'];
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    $vnp_BankCode = "";
    $vnp_Locale = 'vn';

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount * 100,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
        "vnp_OrderType" => "other",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $expire,
    ); 

    $frm_data = filteration($_POST);

    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`) 
        VALUES (?,?,?,?,?)";

    insert($query1, [
        $vnp_CusId,
        $_SESSION['room']['id'],
        $frm_data['checkin'],
        $frm_data['checkout'],
        $vnp_TxnRef
    ], 'issss');

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, 
        `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";

    insert(
        $query2,
        [
            $booking_id,
            $_SESSION['room']['name'],
            $_SESSION['room']['price'],
            $vnp_Amount,
            $frm_data['name'],
            $frm_data['phonenum'],
            $frm_data['address']
        ],
        'issssss'
    );
}

if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}   

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}
header('Location: ' . $vnp_Url);
die();

