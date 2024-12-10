<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>your orders</h3>
    <p> <a href="home.php">home</a> / order </p>
</section>

<section class="placed-orders">

    <h1 class="title">placed orders</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "
        SELECT o.*, s.seat_number, s.status
        FROM orders o
        INNER JOIN seats s ON o.seat_number = s.seat_id
        WHERE o.user_id = '$user_id'
    ") or die('Query failed: ' . mysqli_error($conn));
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> Placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> Roll Number : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> Time_slot : <span><?php echo $fetch_orders['time_slot_id']; ?></span> </p>
        <p> Reference ID : <span><?php echo $fetch_orders['reference_code']; ?></span> </p>
        <p> seat number : <span><?php echo $fetch_orders['seat_number']; ?></span> </p>
        <p> Your Orders : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
        <p> Total price : <span>&pound;<?php echo $fetch_orders['total_price']; ?></span> </p>
        <p> Order status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'tomato'; }else{echo 'green';} ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">no orders placed yet!</p>';
    }
    ?>
    </div>

</section>







<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>