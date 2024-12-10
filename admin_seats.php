<?php
@include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php');
}

// Update seat status
if (isset($_POST['update_seat'])) {
    $seat_id = $_POST['seat_id'];
    $status = $_POST['status'];
    $time_slot_id = $_POST['time_slot_id'];
    
    mysqli_query($conn, "UPDATE seats SET status = '$status', time_slot_id = '$time_slot_id' WHERE seat_id = '$seat_id'") or die('Query failed');
    $message[] = 'Seat status updated successfully!';
}

// Fetch seats
$seats = mysqli_query($conn, "SELECT * FROM seats") or die('Query failed');
$time_slots = mysqli_query($conn, "SELECT * FROM time_slots") or die('Query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Seats</title>
</head>
<body>
    <h3>Manage Seats</h3>
    <?php if (isset($message)) foreach ($message as $msg) echo "<p>$msg</p>"; ?>
    <form action="" method="POST">
        <table border="1">
            <tr>
                <th>Seat ID</th>
                <th>Seat Number</th>
                <th>Status</th>
                <th>Time Slot</th>
                <th>Actions</th>
            </tr>
            <?php while ($seat = mysqli_fetch_assoc($seats)) { ?>
            <tr>
                <td><?= $seat['seat_id'] ?></td>
                <td><?= $seat['seat_number'] ?></td>
                <td><?= $seat['status'] ?></td>
                <td>
                    <select name="time_slot_id" required>
                        <?php while ($slot = mysqli_fetch_assoc($time_slots)) { ?>
                        <option value="<?= $slot['time_slot_id'] ?>" <?= $seat['time_slot_id'] == $slot['time_slot_id'] ? 'selected' : '' ?>><?= $slot['time_slot'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <select name="status">
                        <option value="available" <?= $seat['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="booked" <?= $seat['status'] == 'booked' ? 'selected' : '' ?>>Booked</option>
                    </select>
                    <input type="hidden" name="seat_id" value="<?= $seat['seat_id'] ?>">
                    <input type="submit" name="update_seat" value="Update">
                </td>
            </tr>
            <?php } ?>
        </table>
    </form>
</body>
</html>
