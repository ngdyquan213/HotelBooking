<?php
    require('../admin/inc/essentails.php');
    require('../admin/inc/db_config.php');
    session_start();

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
        redirect('index.php');
    }

    if(isset($_POST['review_form'])){

        $frm_data = filteration($_POST);
        
        $query = "UPDATE `booking_order` SET `rate_review`=? WHERE `booking_id`=? AND `user_id` = ?";
        $values = [1, $frm_data['booking_id'], $_SESSION['uId']];
        $result = update($query, $values, 'iii');

        $ins_query = "INSERT INTO `rating_review`(`booking_id`, `room_id`, `user_id`, `rating`, `review`) VALUES (?,?,?,?,?)";
        $ins_values = [$frm_data['booking_id'], $frm_data['room_id'], $_SESSION['uId'], $frm_data['rating'], $frm_data['review']];
        $ins_result = insert($ins_query, $ins_values, 'iiiis');


        echo $ins_result;
    }