<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    
    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'already added to wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'product added to wishlist';
    }

}
// Handle "Add to Wishlist" functionality for packages
if (isset($_POST['add_to_wishlist_package'])) {

    $package_id = $_POST['package_id'];
    $package_name = $_POST['package_name'];
    $package_price = $_POST['package_price'];
    $package_image = $_POST['package_image'];
 
    // Check if package is already in wishlist or cart
    $check_package_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$package_name' AND user_id = '$user_id'") or die('query failed');
    $check_package_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$package_name' AND user_id = '$user_id'") or die('query failed');
 
    if (mysqli_num_rows($check_package_wishlist) > 0) {
        $message[] = 'Package already added to wishlist';
    } elseif (mysqli_num_rows($check_package_cart) > 0) {
        $message[] = 'Package already added to cart';
    } else {
        // Add package to wishlist
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$package_id', '$package_name', '$package_price', '$package_image')") or die('query failed');
        $message[] = 'Package added to wishlist';
    }
 }
 
 if (isset($_POST['add_to_cart_package'])) {
 
    $package_id = $_POST['package_id'];
    $package_name = $_POST['package_name'];
    $package_price = $_POST['package_price'];
    $package_image = $_POST['package_image'];
 
    // Check if the package is already in the cart
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$package_name' AND user_id = '$user_id'") or die('query failed');
 
    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Package is already in the cart';
    } else {
        // Check if the package is already in the wishlist and remove if necessary
        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$package_name' AND user_id = '$user_id'") or die('query failed');
 
        if (mysqli_num_rows($check_wishlist_numbers) > 0) {
            // Remove from wishlist if already added
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$package_name' AND user_id = '$user_id'") or die('query failed');
        }
 
        // Add the package to the cart
        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, image) VALUES('$user_id', '$package_id', '$package_name', '$package_price', '$package_image')") or die('query failed');
        $message[] = 'Package added to cart';
    }
 }

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="quick-view">

    <h1 class="title">menu details</h1>

    <?php  
        if(isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
    ?>
    <form action="" method="POST">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">&pound;<?php echo $fetch_products['price']; ?></div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
         <input type="number" name="product_quantity" value="1" min="0" class="qty">
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <input type="submit" value="add to eatlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
    <?php
            }
        }else{
        echo '<p class="empty">no products details available!</p>';
        }
    }
    ?>
    <section class="quick-view">

<h1 class="title">Package Details</h1>

<?php  
    if(isset($_GET['pid'])){
        $pid = $_GET['pid'];
        
        // Query to fetch package details and its associated products
        $select_packages = mysqli_query($conn, "
            SELECT p.*, pi.product_id, pr.name AS product_name, pr.price AS product_price, pr.image AS product_image
            FROM `packages` p
            LEFT JOIN `package_items` pi ON p.package_id = pi.package_id
            LEFT JOIN `products` pr ON pi.product_id = pr.id
            WHERE p.package_id = '$pid'
        ") or die('query failed');
        
        if(mysqli_num_rows($select_packages) > 0){
            $package = null;
            $package_items = [];
            
            while($fetch_package = mysqli_fetch_assoc($select_packages)) {
                // Set the first entry as the main package
                if (!$package) {
                    $package = $fetch_package;
                }
                
                // Add associated products to the items array
                $package_items[] = $fetch_package;
            }
?>

<form action="" method="POST">
     <img src="uploaded_img/<?php echo $package['package_image']; ?>" alt="" class="image">
     <div class="name"><?php echo $package['package_name']; ?></div>
     <div class="price">&pound;<?php echo $package['discounted_price']; ?></div>
     <div class="details"><?php echo $package['package_description']; ?></div>
     
     <div class="package-items">
        <?php
            // Display associated products in the package
            foreach ($package_items as $item) {
                if ($item['package_id'] == $package['package_id']) {
        ?>
            <p class="product-name"><?php echo $item['product_name']; ?></p>
           
        <?php
                }
            }
        ?>
     </div>
     
     <input type="hidden" name="package_id" value="<?php echo $package['package_id']; ?>">
     <input type="hidden" name="package_name" value="<?php echo $package['package_name']; ?>">
     <input type="hidden" name="package_price" value="<?php echo $package['discounted_price']; ?>">
     <input type="hidden" name="package_image" value="<?php echo $package['package_image']; ?>">
     <input type="submit" value="Add to Eatlist" name="add_to_wishlist_package" class="option-btn1">
     <input type="submit" value="Add to Cart" name="add_to_cart_package" class="btn1">
</form>

<?php
        } else {
            echo '<p class="empty">No package details available!</p>';
        }
    }
?>

</section>

    <div class="more-btn">
        <a href="home.php" class="option-btn">go to home page</a>
    </div>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>