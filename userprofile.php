<?php 
include('inc.header.php');

if (!isset($_SESSION['CustomerLogin']) || $_SESSION['CustomerLogin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['CustomerID'];

// Fetch user data from database
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.profile_photo, u.user_type, c.city_name 
    FROM users u 
    LEFT JOIN cities c ON u.city_id = c.city_id
    WHERE u.user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// You may want to set some default values in case data is missing
$profile_photo = !empty($user['profile_photo']) ? 'uploads/'.$user['profile_photo'] : 'img/userprofile.jpg';
$user_name = htmlspecialchars($user['name']);
$user_email = htmlspecialchars($user['email']);
$user_type = htmlspecialchars($user['user_type']); // Expected: Customer, Lawyer, Admin
$user_city = htmlspecialchars($user['city_name'] ?? 'N/A');

// For customer, no specialization, bio, experience — can be extended if you store this for customers
?>

<body>
<div class="wrapper">
    <!-- Top Bar -->
    <!-- [Use existing top-bar and navbar here as-is] -->

    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>User Profile</h2>
                </div>
                <div class="col-12">
                    <a href="index.php">Home</a>
                    <a href="#">User Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Profile Section Start -->
    <div class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Profile Details</h2>
            </div>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="<?php echo $profile_photo; ?>" alt="User Photo" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                    <h3 class="mb-1"><?php echo $user_name; ?></h3>
                    <p><i class="fas fa-user-tag"></i> <?php echo $user_type; ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo $user_city; ?></p>
                </div>
                <div class="col-md-8">
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fa fa-envelope"></i>
                            <div class="contact-text">
                                <h2>Email</h2>
                                <p><?php echo $user_email; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-phone-alt"></i>
                            <div class="contact-text">
                                <h2>Phone</h2>
                                <p><!-- Add phone if you store it in users table or profile --></p>
                            </div>
                        </div>
                        <!-- Customers typically won't have specialization, experience, or bio, so you can remove or add if you want -->

                    </div>
                    <a href="editprofile.php" class="btn mt-3">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Section End -->

    <!-- Newsletter & Footer -->
    <!-- [Use existing newsletter and footer as-is] -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
</div>

<?php include('inc.footer.php'); ?>
