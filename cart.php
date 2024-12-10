<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    // Fetch the stock quantity for the product
    $product_id = mysqli_query($conn, "SELECT pid FROM `cart` WHERE id = '$cart_id'") or die('query failed');
    $product = mysqli_fetch_assoc($product_id);
    $product_id = $product['pid'];

    $stock_query = mysqli_query($conn, "SELECT stock FROM `products` WHERE id = '$product_id'") or die('query failed');
    $product_stock = mysqli_fetch_assoc($stock_query)['stock'];

    // Check if the cart quantity is within available stock
    if ($cart_quantity > $product_stock) {
        $message[] = 'Quantity exceeds available stock!';
    } else {
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
        $message[] = 'Cart quantity updated!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Food Basket</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <h3>Your Food Basket</h3>
        <p> <a href="home.php">home</a> / cart </p>
    </section>

    <section class="shopping-cart">

        <h1 class="title">Items in your cart</h1>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $product_id = $fetch_cart['pid'];
                    $query = "SELECT package_id FROM `packages`";
$result = mysqli_query($conn, $query) or die('Query failed');

// Initialize an array to store the package IDs
$package_ids = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $package_ids[] = $row['package_id']; // Add each package_id to the array
    }
}


                    
            ?>
            <?php  
            if (in_array($product_id, $package_ids)) {
                // Fetch the stock for each product
                $stock_query = mysqli_query($conn, "SELECT * FROM `packages` WHERE package_id = '$product_id'") or die('query failed');
                $stock_data = mysqli_fetch_assoc($stock_query);
                $available_stock = 0;
            } else {
               // Fetch the stock for each product
               $stock_query = mysqli_query($conn, "SELECT stock FROM `products` WHERE id = '$product_id'") or die('query failed');
               $stock_data = mysqli_fetch_assoc($stock_query);
               $available_stock = $stock_data['stock'];
            }
            
            ?>
                    <div class="box">
    <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
    <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
    <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
    <div class="name"><?php echo $fetch_cart['name']; ?></div>
    <div class="price">&pound;<?php echo $fetch_cart['price']; ?></div>
    <form action="" method="post">
        <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
        
        <?php 
        // Check if the product ID is in the package array
        if (in_array($product_id, $package_ids)) { 
        ?>
            <!-- If the product ID is a package, display a message or other logic -->
            
        <?php 
        } else { 
        ?>
            <!-- If it's not a package, allow quantity updates -->
            <input type="number" min="1" max="<?php echo $available_stock; ?>" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty" id="quantity-<?php echo $fetch_cart['id']; ?>" oninput="updateSubtotal(<?php echo $fetch_cart['id']; ?>)">
            <input type="submit" value="update" class="option-btn" name="update_quantity">
        <?php 
        } 
        ?>
        
        
    </form>
    <div class="sub-total" id="subtotal-<?php echo $fetch_cart['id']; ?>"> 
        sub-total : <span>&pound;<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> 
    </div>
</div>

            <?php
                    $grand_total += $sub_total;
                }
            } else {
                echo '<p class="empty">your cart is empty</p>';
            }
            ?>
        </div>

        <div class="more-btn">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>&pound;<?php echo $grand_total; ?></span></p>
            <a href="shop.php" class="option-btn">Add More Items</a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
        </div>

    </section>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
        // JavaScript function to dynamically update the subtotal and grand total
        function updateSubtotal(cartId) {
            const quantity = document.getElementById('quantity-' + cartId).value;
            const price = parseFloat(document.querySelector(`#cart-item-${cartId} .price`).textContent.replace('$', ''));
            const subtotal = (quantity * price).toFixed(2);

            // Update the individual subtotal
            document.getElementById('subtotal-' + cartId).innerHTML = `sub-total : <span>$${subtotal} </span>`;

            // Update the grand total dynamically
            let grandTotal = 0;
            document.querySelectorAll('.sub-total span').forEach(subtotalElem => {
                grandTotal += parseFloat(subtotalElem.textContent.replace('$', '').replace(' /-', ''));
            });
            document.getElementById('grand-total').textContent = `$${grandTotal.toFixed(2)} `;
        }
    </script>

</body>

</html>
