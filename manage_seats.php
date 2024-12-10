<?php
@include 'config.php';

session_start();

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Initialize $message as an array
$message = [];

// Add a new seat
if (isset($_POST['add_seat'])) {
    $seat_number = mysqli_real_escape_string($conn, $_POST['seat_number']);
    $time_slot_id = mysqli_real_escape_string($conn, $_POST['time_slot_id']);
    $status = 'available'; // Default status for new seats

    $check_seat_query = "SELECT * FROM seats WHERE seat_number = '$seat_number' AND time_slot_id = '$time_slot_id'";
    $check_seat_result = mysqli_query($conn, $check_seat_query);

    if (mysqli_num_rows($check_seat_result) > 0) {
        $message[] = 'Seat already exists for the selected time slot!';
    } else {
        $add_seat_query = "INSERT INTO seats (seat_number, status, time_slot_id) VALUES ('$seat_number', '$status', '$time_slot_id')";
        mysqli_query($conn, $add_seat_query) or die('Query failed');
        $message[] = 'Seat added successfully!';
    }
}

if (isset($_POST['update_seat'])) {
    $seat_id = mysqli_real_escape_string($conn, $_POST['seat_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate seat ID and new status
    if (!empty($seat_id) && !empty($new_status)) {
        // Prepare the update query
        $update_query = "UPDATE seats SET status = '$new_status' WHERE seat_id = '$seat_id'";

        // Execute the update query
        if (mysqli_query($conn, $update_query)) {
            // Feedback to the user
            $message[] = 'Seat status updated successfully!';
        } else {
            $message[] = 'Error: Could not update seat status.';
        }
    } else {
        $message[] = 'Invalid seat or status.';
    }
}

// Delete a seat
if (isset($_POST['delete_seat'])) {
    $seat_id = mysqli_real_escape_string($conn, $_POST['seat_id']);
    $delete_query = "DELETE FROM seats WHERE seat_id = '$seat_id'";
    mysqli_query($conn, $delete_query) or die('Query failed');
    $message[] = 'Seat deleted successfully!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Seats</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php @include 'admin_header.php'; ?>

<section class="seat-management">
    <h1>Manage Seats</h1>

    <?php
    // Ensure $message is an array before using foreach
    if (is_array($message) && !empty($message)) {
        foreach ($message as $msg) {
            echo "<p class='message'>$msg</p>";
        }
    }
    ?>

    <!-- Add New Seat Form -->
    <form action="" method="POST" class="add-seat-form">
        <h3>Add New Seat</h3>
        <input type="text" name="seat_number" placeholder="Enter Seat Number (e.g., A1)" required>
        <select name="time_slot_id" required>
            <option value="" disabled selected>Select Time Slot</option>
            <?php
            $time_slots_query = mysqli_query($conn, "SELECT * FROM time_slots") or die('Query failed');
            while ($time_slot = mysqli_fetch_assoc($time_slots_query)) {
                echo "<option value='{$time_slot['time_slot_id']}'>{$time_slot['time_slot']}</option>";
            }
            ?>
        </select>
        <input type="submit" name="add_seat" value="Add Seat" class="btn">
    </form>

    <!-- Seat List Table -->
    <div class="seat-list">
        <h3>All Seats</h3>
        <table>
            <thead>
                <tr>
                   
                    <th>Seat Number</th>
                    <th>Status</th>
                    <th>Time Slot</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $seats_query = "SELECT s.*, ts.time_slot FROM seats s INNER JOIN time_slots ts ON s.time_slot_id = ts.time_slot_id";
                $seats_result = mysqli_query($conn, $seats_query) or die('Query failed');

                if (mysqli_num_rows($seats_result) > 0) {
                    while ($seat = mysqli_fetch_assoc($seats_result)) {
                        echo "
                        <tr>
                            
                            <td>{$seat['seat_number']}</td>
                            <td>{$seat['status']}</td>
                            <td>{$seat['time_slot']}</td>
                            <td>
                                <form action='' method='POST' class='inline-form'>
                                    <input type='hidden' name='seat_id' value='{$seat['seat_id']}'>
                                    <select name='status' required>
                                        <!-- Dynamically hide the current status option -->
                                        <option value='available' " . ($seat['status'] == 'available' ? 'selected' : '') . " " . ($seat['status'] == 'available' ? 'disabled' : '') . ">Available</option>
                                        <option value='booked' " . ($seat['status'] == 'booked' ? 'selected' : '') . " " . ($seat['status'] == 'booked' ? 'disabled' : '') . ">Booked</option>
                                    </select>
                                    <input type='submit' name='update_seat' value='Update' class='btn'>
                                    <input type='submit' name='delete_seat' value='Delete' class='btn btn-danger'>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No seats found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php @include 'admin_footer.php'; ?>

</body>
</html>
