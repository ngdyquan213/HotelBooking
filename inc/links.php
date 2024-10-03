<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Css Custom -->
<link rel="stylesheet" href="css/common.css">

<?php 

    session_start();

    require('admin/inc/db_config.php');
    require('admin/inc/essentails.php');

    $values = [1];

    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));

    $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
    $settings_r = mysqli_fetch_assoc(select($settings_q, $values,'i'));

    if($settings_r['shutdown']){
        echo<<<alertbar
            <div class='bg-danger text-center p-2 fw-bold'>
                <i class='bi bi-exclamation-triangle-fill'></i>
                Bookings are temporarily closed!
            </div>
        alertbar;
    }
?>