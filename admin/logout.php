<?php 

    require('inc/essentails.php');

    session_start();
    session_destroy();
    redirect('index.php');

