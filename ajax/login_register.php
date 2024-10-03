<?php 
    require('../admin/inc/essentails.php');
    require('../admin/inc/db_config.php');

    if(isset($_POST['register'])){
        $data = filteration($_POST);
        
        // match password and confirm password fields

        if($data['pass'] != $data['cpass']){
            echo 'pass_mismatch';
        }

        // check user exists or not

        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? AND `phonenum`=? LIMIT 1",[$data['email'],$data['phonenum']],'ss');
    
        if(mysqli_num_rows($u_exist) != 0){
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
            exit;
        }

        // upload user image to server
        
        $img = uploadUserImage($_FILES['profile']);

        if($img == 'inv_img'){
            echo 'inv_img';
            exit;
        }else if($img == 'upd_failed'){
            echo 'upd_failed';
            exit;
        }

        $token = bin2hex(random_bytes(16));

        $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

        $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`,
                `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";
                
        $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'], $data['dob'],
            $img, $enc_pass,$token];

        if(insert($query, $values,'sssssssss')){
            echo 1;
        }else{
            echo 'ins_failed';
        }


        
    
    }