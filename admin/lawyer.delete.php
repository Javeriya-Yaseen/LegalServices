<?php
include('inc.connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid lawyer ID.");
}

$lawyer_id = intval($_GET['id']);

// First, delete the profile (due to foreign key constraints)
$delete_profile_sql = "DELETE FROM lawyers_profile WHERE lawyer_id = $lawyer_id";
$delete_user_sql = "DELETE FROM users WHERE user_id = $lawyer_id AND user_type = 'Lawyer'";

if (mysqli_query($conn, $delete_profile_sql)) {
    if (mysqli_query($conn, $delete_user_sql)) {
        header("Location: lawyers.php");
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "Error deleting profile: " . mysqli_error($conn);
}
?>
