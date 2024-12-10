<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_package'])) {
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $package_description = mysqli_real_escape_string($conn, $_POST['package_description']);
    $discount_percentage = mysqli_real_escape_string($conn, $_POST['discount_percentage']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    
    // Image Upload
    $image_name = $_FILES['package_image']['name'];
    $image_size = $_FILES['package_image']['size'];
    $image_tmp_name = $_FILES['package_image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image_name;

    if ($image_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        // Insert package details into the database (without the price initially)
        $insert_package = mysqli_query($conn, "INSERT INTO `packages` (package_name, package_description, discount_percentage, start_time, end_time, package_image) VALUES ('$package_name', '$package_description', '$discount_percentage', '$start_time', '$end_time', '$image_name')") or die('query failed');

        if ($insert_package) {
            $package_id = mysqli_insert_id($conn); // Get the last inserted package ID
            
            // Calculate the total price of selected menu items
            $total_price = 0;
            if (isset($_POST['menu_items'])) {
                foreach ($_POST['menu_items'] as $menu_item) {
                    $menu_item = mysqli_real_escape_string($conn, $menu_item);
                    $select_price = mysqli_query($conn, "SELECT price FROM `products` WHERE id = '$menu_item'") or die('query failed');
                    $fetch_price = mysqli_fetch_assoc($select_price);
                    $total_price += $fetch_price['price']; // Add item price to the total
                    $insert_menu_item = mysqli_query($conn, "INSERT INTO `package_items` (package_id, product_id) VALUES ('$package_id', '$menu_item')") or die('query failed');
                }
            }

            // Apply discount to the total price
            $discounted_price = $total_price - (($discount_percentage / 100) * $total_price);

            // Update the discounted price in the packages table
            $update_price = mysqli_query($conn, "UPDATE `packages` SET discounted_price = '$discounted_price' WHERE package_id = '$package_id'") or die('query failed');

            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Package added successfully!';
        } else {
            $message[] = 'Failed to add package!';
        }
    }
}

if (isset($_POST['update_discount'])) {
    $package_id = mysqli_real_escape_string($conn, $_POST['package_id']);
    $new_discount = mysqli_real_escape_string($conn, $_POST['new_discount']);
    
    // Update discount percentage in the database
    $update_discount = mysqli_query($conn, "UPDATE `packages` SET discount_percentage = '$new_discount' WHERE package_id = '$package_id'") or die('query failed');
    
    if ($update_discount) {
        $message[] = 'Discount updated successfully!';
    } else {
        $message[] = 'Failed to update discount!';
    }
}

// Delete package functionality
if (isset($_GET['delete'])) {
    $package_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Fetch the image name from the database to delete the image from the server
    $select_image = mysqli_query($conn, "SELECT package_image FROM `packages` WHERE package_id = '$package_id'") or die('query failed');
    $fetch_image = mysqli_fetch_assoc($select_image);
    $image_name = $fetch_image['package_image'];

    // Delete the package from the database
    $delete_package = mysqli_query($conn, "DELETE FROM `packages` WHERE package_id = '$package_id'") or die('query failed');
    
    if ($delete_package) {
        // Delete the package image from the server
        if (file_exists('uploaded_img/' . $image_name)) {
            unlink('uploaded_img/' . $image_name);
        }
        $message[] = 'Package deleted successfully!';
    } else {
        $message[] = 'Failed to delete package!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom Admin CSS -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php @include 'admin_header.php'; ?>
<section class="add-package">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Add New Package</h3>
        
        <!-- Package Name -->
        <input type="text" name="package_name" placeholder="Enter package name" class="box" required>

        <!-- Package Description -->
        <textarea name="package_description" placeholder="Enter package description" class="box" required></textarea>
        
        <!-- Discount Percentage -->
        <input type="number" name="discount_percentage" id="discount_percentage" min="0" max="100" step="0.01" placeholder="Enter discount percentage" class="box" required>
        
        <!-- Start Time -->
        <input type="datetime-local" name="start_time" class="box" required>
        
        <!-- End Time -->
        <input type="datetime-local" name="end_time" class="box" required>
        
        <!-- Package Image -->
        <input type="file" name="package_image" accept="image/*" class="box" required>

        <!-- Select Menu Items -->
        <h4>Select Menu Items for Package:</h4>
        <div class="checkbox-list">
            <?php
            // Fetch all menu items from the products table
            $select_items = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
            while ($fetch_items = mysqli_fetch_assoc($select_items)) {
                // Display each menu item as a checkbox with its name and price
                echo '<label><input type="checkbox" name="menu_items[]" value="' . $fetch_items['id'] . '" data-price="' . $fetch_items['price'] . '" onclick="calculateTotalPrice()"> ' . $fetch_items['name'] . ' - &pound;' . $fetch_items['price'] . '</label><br>';
            }
            ?>
        </div>

        <!-- Display Total Price and Discounted Price -->
        <h4>Total Price: &pound;<span id="total_price">0.00</span></h4>
        <h4>Discounted Price: &pound;<span id="discounted_price">0.00</span></h4>

        <!-- Submit Button -->
        <input type="submit" name="add_package" value="Add Package" class="btn">
    </form>
</section>

<section class="show-packages">
   <div class="box-container">
      <?php
         // Fetch all packages from the database
         $select_packages = mysqli_query($conn, "SELECT * FROM `packages`") or die('Query failed');
         if(mysqli_num_rows($select_packages) > 0){
            while($fetch_packages = mysqli_fetch_assoc($select_packages)){
                $package_id = $fetch_packages['package_id'];
                
                // Fetch menu items associated with this package
                $select_menu_items = mysqli_query($conn, "SELECT p.name, p.price FROM `package_items` pi JOIN `products` p ON pi.product_id = p.id WHERE pi.package_id = '$package_id'") or die('Query failed');
                
                $menu_items = [];
                $total_price = 0;
                
                while ($menu_item = mysqli_fetch_assoc($select_menu_items)) {
                    $menu_items[] = $menu_item['name'];
                    $total_price += $menu_item['price'];  // Sum the price of each menu item
                }

                // Calculate discounted price
                $discount_percentage = $fetch_packages['discount_percentage'];
                $discounted_price = $total_price - ($total_price * ($discount_percentage / 100));
      ?>
      <div class="box">
         <!-- Package Image -->
         <img class="image" src="uploaded_img/<?php echo $fetch_packages['package_image']; ?>" alt="Package Image">
         
         <!-- Package Name -->
         <div class="name"><?php echo $fetch_packages['package_name']; ?></div>
         
         <!-- Package Description -->
         <div class="description"><?php echo $fetch_packages['package_description']; ?></div>
         
         <!-- Discount Percentage -->
         <p class="discount">Discount: <?php echo $fetch_packages['discount_percentage']; ?>%</p>
         
         <!-- Start and End Time -->
         <p class="time">Start: <?php echo $fetch_packages['start_time']; ?></p>
         <p class="time">End: <?php echo $fetch_packages['end_time']; ?></p>
         
         <!-- Display Menu Items -->
         <p class="menu-items">Menu Items: <?php echo implode(', ', $menu_items); ?></p>
         
         <!-- Display Total Price -->
         <p class="total-price">Total Price: &pound;<?php echo number_format($total_price, 2); ?></p>
         
         <!-- Display Discounted Price -->
         <p class="discounted-price">Discounted Price: &pound;<?php echo number_format($discounted_price, 2); ?></p>
         
         <!-- Update Discount Form -->
         <div class="update-discount-form">
            <form action="" method="POST">
               <input type="hidden" name="package_id" value="<?php echo $fetch_packages['package_id']; ?>">
               <label for="new_discount">Update Discount (%)</label>
               <input type="number" id="new_discount" name="new_discount" min="0" max="100" step="0.01" value="<?php echo $fetch_packages['discount_percentage']; ?>" class="box" required>
               <input type="submit" name="update_discount" value="Update Discount" class="btn">
            </form>
         </div>

         <!-- Update and Delete Links -->
         <div class="action-buttons">
            <a href="admin_update_package.php?update=<?php echo $fetch_packages['package_id']; ?>" class="option-btn">Update</a>
            <a href="admin_create_package.php?delete=<?php echo $fetch_packages['package_id']; ?>" class="delete-btn" onclick="return confirm('Delete this package?');">Delete</a>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No packages added yet!</p>';
      }
      ?>
   </div>
</section>

<script src="js/admin_script.js"></script>
<script>
        // Function to calculate total price and discounted price
        function calculateTotalPrice() {
            let totalPrice = 0;
            let discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
            let discountedPrice = 0;
            
            // Get the selected menu items
            let selectedItems = document.querySelectorAll('input[name="menu_items[]"]:checked');
            
            // Calculate the total price based on selected items
            selectedItems.forEach(function(item) {
                totalPrice += parseFloat(item.getAttribute('data-price'));
            });

            // Calculate the discounted price
            discountedPrice = totalPrice - (totalPrice * (discountPercentage / 100));

            // Update the total and discounted prices on the page
            document.getElementById('total_price').textContent = totalPrice.toFixed(2);
            document.getElementById('discounted_price').textContent = discountedPrice.toFixed(2);
        }
        
        // Event listener for discount percentage change
        document.getElementById('discount_percentage').addEventListener('input', calculateTotalPrice);
    </script>
</body>
</html>