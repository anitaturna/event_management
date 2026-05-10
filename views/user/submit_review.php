<?php
include('../../config/db_connect.php');
if(!isset($_SESSION['user_id'])) { header('location:../auth/login.php'); exit(); }
if(isset($_POST['submit_review'])) {
    $user_id  = (int)$_SESSION['user_id'];
    $event_id = (int)$_POST['event_id'];
    $rating   = (int)$_POST['rating'];
    $comment  = mysqli_real_escape_string($conn, $_POST['comment']);
    $existing = mysqli_query($conn, "SELECT id FROM reviews WHERE user_id=$user_id AND event_id=$event_id");
    if(mysqli_num_rows($existing)>0) {
        mysqli_query($conn, "UPDATE reviews SET rating=$rating, comment='$comment' WHERE user_id=$user_id AND event_id=$event_id");
    } else {
        mysqli_query($conn, "INSERT INTO reviews (user_id, event_id, rating, comment, created_at) VALUES ($user_id, $event_id, $rating, '$comment', NOW())");
    }
}
$eid = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
header("location:event_details.php?id=$eid");
exit();
