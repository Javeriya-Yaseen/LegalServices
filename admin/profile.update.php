<?php
include('inc.header.php');
session_start();

$user_id = $_SESSION['AdminID'] ?? 0;

if ($user_id == 0) {
    echo "<script>alert('Unauthorized access'); window.location.href='login.php';</script>";
    exit;
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

// Fetch existing photo
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_photo FROM users WHERE user_id = $user_id"));
$old_photo = $existing['profile_photo'];

// Handle image upload
$new_photo = $old_photo; // default
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['profile_photo']['tmp_name'];
    $file_name = basename($_FILES['profile_photo']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($file_ext, $allowed_exts)) {
        $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $upload_dir = 'uploads/profiles/';
        $destination = $upload_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $destination)) {
            // Optionally delete old photo if it's not default
            if ($old_photo && file_exists($upload_dir . $old_photo)) {
                unlink($upload_dir . $old_photo);
            }

            $new_photo = $new_filename;
        }
    }
}

// Update user
$update = mysqli_query($conn, "
    UPDATE users 
    SET name = '$name', email = '$email', profile_photo = '$new_photo' 
    WHERE user_id = $user_id
");

if ($update) {
    echo "<script>alert('Profile updated successfully.'); window.location.href='userprofile.php';</script>";
} else {
    echo "<script>alert('Failed to update profile: " . mysqli_error($conn) . "'); window.location.href='userprofile.php';</script>";
}
?>
