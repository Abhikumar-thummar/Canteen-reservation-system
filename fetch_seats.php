<?php
@include 'config.php';

if (isset($_GET['time_slot_id'])) {
    $time_slot_id = mysqli_real_escape_string($conn, $_GET['time_slot_id']);
    $seats_query = mysqli_query($conn, "SELECT * FROM seats WHERE status = 'available' AND time_slot_id = '$time_slot_id'") or die('Query failed');
    
    $seats = array();
    while ($row = mysqli_fetch_assoc($seats_query)) {
        $seats[] = array(
            'seat_number' => $row['seat_number'],  // Return seat_number
            'seat_id' => $row['seat_id']
        );
    }
    
    echo json_encode($seats);  // Return as JSON response
}
?>
