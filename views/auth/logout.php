<?php 
include('../../config/constants.php');
session_destroy(); // সব সেশন মুছে ফেলা
header('location:'.SITEURL.'index.php');
?>