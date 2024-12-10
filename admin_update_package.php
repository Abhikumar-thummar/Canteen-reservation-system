<?php

@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_GET['update'])) {
    $package_id = $_GET['update'];
    
    // Fetch the existing package details from the database
    $select_package = mysqli_query($conn, "SELECT * FROM `packages` WHERE package_id = '$package_id'") or die('query failed');
    $fetch_package = mysqli_fetch_assoc($select_package);
    
    if (!$fetch_package) {
        header('location:admin_create_package.php');
    }
}

if (isset($_POST['update_package'])) {
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $package_description = mysqli_real_escape_string($conn, $_POST['package_description']);
    $discount_percentage = mysqli_real_escape_string($conn, $_POST['discount_percentage']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    
    // Image Upload (if a new image is provided)
    $image_name = $_FILES['package_image']['name'];
    $image_tmp_name = $_FILES['package_image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image_name;
    
    // Check if a new image is uploaded
    if ($image_name) {
        $image_size = $_FILES['package_image']['size'];
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            // Delete old image if a new one is uploaded
            unlink('uploaded_img/' . $fetch_package['package_image']);
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        // If no new image, keep the old image
        $image_name = $fetch_package['package_image'];
    }

    // Update package details in the database
    $update_package = mysqli_query($conn, "UPDATE `packages` SET package_name = '$package_name', package_description = '$package_description', discount_percentage = '$discount_percentage', start_time = '$start_time', end_time = '$end_time', package_image = '$image_name' WHERE package_id = '$package_id'") or die('query failed');
    
    if ($update_package) {
        // $message[] = 'Package updated successfully!';
        header("Location: http://localhost/Canteen-management-system/admin_create_package.php");
        
    } else {
        $message[] = 'Failed to update package!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Package</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom Admin CSS -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php @include 'admin_header.php'; ?>
<section class="add-package">

    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Update Package</h3>
        <input type="text" name="package_name" placeholder="Enter package name" class="box" value="<?php echo $fetch_package['package_name']; ?>" required>
        <textarea name="package_description" placeholder="Enter package description" class="box" required><?php echo $fetch_package['package_description']; ?></textarea>
        <input type="number" name="discount_percentage" min="0" max="100" step="0.01" placeholder="Enter discount percentage" class="box" value="<?php echo $fetch_package['discount_percentage']; ?>" required>
        <input type="datetime-local" name="start_time" placeholder="Enter start time" class="box" value="<?php echo $fetch_package['start_time']; ?>" required>
        <input type="datetime-local" name="end_time" placeholder="Enter end time" class="box" value="<?php echo $fetch_package['end_time']; ?>" required>
        <input type="file" name="package_image" accept="image/*" class="box">
        <img src="uploaded_img/<?php echo $fetch_package['package_image']; ?>" alt="Current Image" width="150">
        <input type="submit" name="update_package" value="Update Package" class="btn">
    </form>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
