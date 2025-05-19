<?php
include('inc.connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// Check if the user exists and get the user_type
$sql = "SELECT user_type FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    $user_type = $user['user_type'];

    // If it's a Lawyer, delete associated profile
    if ($user_type === 'Lawyer') {
        $delete_profile_sql = "DELETE FROM lawyers_profile WHERE lawyer_id = $user_id";
        mysqli_query($conn, $delete_profile_sql); // continue even if fails
    }

    // Delete the user from Users table
    $delete_user_sql = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($conn, $delete_user_sql)) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }

} else {
    echo "User not found.";
}
?>
