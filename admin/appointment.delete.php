<?php
include('inc.header.php');

// Get appointment ID from URL
$appointment_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Check if ID is valid
if ($appointment_id <= 0) {
    echo "<script>alert('Invalid appointment ID.'); window.location.href='appointments.php';</script>";
    exit;
}

// Check if appointment exists
$check = mysqli_query($conn, "SELECT appointment_id FROM appointments WHERE appointment_id = $appointment_id LIMIT 1");

if (!$check || mysqli_num_rows($check) === 0) {
    echo "<script>alert('Appointment not found.'); window.location.href='appointments.php';</script>";
    exit;
}

// Delete appointment
$delete = mysqli_query($conn, "DELETE FROM appointments WHERE appointment_id = $appointment_id");

if ($delete) {
    echo "<script>alert('Appointment deleted successfully.'); window.location.href='appointments.php';</script>";
} else {
    echo "<script>alert('Failed to delete appointment: " . mysqli_error($conn) . "'); window.location.href='appointments.php';</script>";
}
?>
