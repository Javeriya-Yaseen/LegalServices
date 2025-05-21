<?php
include('inc.header.php');

// Check if Customer is logged in
if (!isset($_SESSION['CustomerLogin']) || $_SESSION['CustomerLogin'] !== true) {
    header('Location: login.php');
    exit;
}

// Block access if user type is not Customer
if (!isset($_SESSION['UserType']) || $_SESSION['UserType'] !== 'Customer') {
    header('Location: index.php');
    exit;
}

$customerId = $_SESSION['CustomerID'];
$user = null;

// Fetch current customer details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND user_type = 'Customer' LIMIT 1");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}
$stmt->close();

$updateSuccess = '';
$updateError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $cityId = intval($_POST['city'] ?? 0);
    $password = trim($_POST['password'] ?? '');

    // Basic validation
    if ($name === '' || $email === '' || $cityId <= 0) {
        $updateError = "Name, Email, and City are required.";
    } else {
        if ($password !== '') {            
            
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, contact_info=?, city_id=?, password=? WHERE user_id=? AND user_type='Customer'");
            $stmt->bind_param("sssisi", $name, $email, $phone, $cityId, $password, $customerId);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, contact_info=?, city_id=? WHERE user_id=? AND user_type='Customer'");
            $stmt->bind_param("sssii", $name, $email, $phone, $cityId, $customerId);
        }

        if ($stmt->execute()) {
            $updateSuccess = "Profile updated successfully.";
            // Refresh user data
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND user_type = 'Customer' LIMIT 1");
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
            }
            $stmt->close();
        } else {
            $updateError = "Error updating profile.";
        }
    }
}
?>

<body>
<div class="wrapper">

    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Edit Profile</h2>
                </div>
                <div class="col-12">
                    <a href="index.php">Home</a>
                    <a href="#">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Edit Profile Form Start -->
    <div class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Update Your Information</h2>
            </div>

            <?php if ($updateSuccess): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($updateSuccess); ?></div>
            <?php endif; ?>
            <?php if ($updateError): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($updateError); ?></div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="<?php echo htmlspecialchars($user['contact_info'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <select name="city" id="city" class="form-control" required>
                                    <option value="">Select City</option>
                                    <?php
                                    // Fetch all cities from database
                                    $citiesResult = $conn->query("SELECT city_id, city_name FROM cities ORDER BY city_name ASC");
                                    if ($citiesResult) {
                                        while ($city = $citiesResult->fetch_assoc()) {
                                            // Check if this city is user's current city
                                            $selected = (!empty($user['city_id']) && $user['city_id'] == $city['city_id']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($city['city_id']) . '" ' . $selected . '>' . htmlspecialchars($city['city_name']) . '</option>';
                                        }
                                        $citiesResult->free();
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password (optional)">
                            </div>
                            <button type="submit" class="btn">Save Changes</button>
                            <a href="userprofile.php" class="btn btn-outline-secondary ml-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Profile Form End -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
</div>

<?php include('inc.footer.php'); ?>
