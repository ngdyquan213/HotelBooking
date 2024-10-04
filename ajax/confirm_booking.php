<?php
    require('../admin/inc/essentails.php');
    require('../admin/inc/db_config.php');
    session_start();

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if(isset($_POST['check_availability'])){
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";

        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($frm_data['checkin']);
        $checkout_date = new DateTime($frm_data['checkout']);

        if($checkin_date == $checkout_date){
            $status = 'check_in_out_equal';
            $result = json_encode(["status" => $status]);
        }else if($checkout_date < $checkin_date){
            $status = 'check_out_earlier';
            $result = json_encode(["status" => $status]);
        }else if($checkin_date < $today_date){
            $status = 'check_in_earlier';
            $result = json_encode(["status" => $status]);
        }

        if($status != ''){
            echo $result;
        }else{
            $_SESSION['room'];

            $count_days = date_diff($checkout_date, $checkin_date)->days;
            $payment = $_SESSION['room']['price'] * $count_days;

            $_SESSION['room']['[payment'] = $payment;
            $_SESSION['room']['available'] = true;

            $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
            echo $result;
        }

    }