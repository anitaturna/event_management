<?php
// ইভেন্ট রিলেটেড সব কাজ এখানে থাকবে
function getAllActiveEvents($conn, $limit = 6) {
    $sql = "SELECT * FROM events WHERE status='active' ORDER BY id DESC LIMIT $limit";
    return mysqli_query($conn, $sql);
}

function getEventById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM events WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res);
}

function getPackagesByEvent($conn, $event_id) {
    $sql = "SELECT * FROM packages WHERE event_id = $event_id";
    return mysqli_query($conn, $sql);
}
?>