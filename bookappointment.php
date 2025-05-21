<?php
include('inc.header.php');

// Check if Customer is logged in
if (!isset($_SESSION['CustomerLogin']) || !$_SESSION['CustomerLogin']) {
    header("Location: login.php");
    exit;
}

// Initialize variables for the form
$lawyer_id = $schedule_date = '';
$lawyerErr = $dateErr = $successMsg = $errorMsg = '';
$searchCity = $searchSpecialization = ''; // Variables to store filter data

// Handle form submission for booking appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookBtn'])) {
    if (empty($_POST['lawyer_id'])) {
        $lawyerErr = "Please select a lawyer.";
    } else {
        $lawyer_id = $_POST['lawyer_id'];
    }

    if (empty($_POST['schedule_date'])) {
        $dateErr = "Please select a date.";
    } else {
        $schedule_date = $_POST['schedule_date'];
    }

    if (empty($lawyerErr) && empty($dateErr)) {
        $customer_id = $_SESSION['CustomerID'];
        $stmt = $conn->prepare("INSERT INTO appointments (customer_id, lawyer_id, schedule_date, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iis", $customer_id, $lawyer_id, $schedule_date);

        // Execute query and provide feedback
        if ($stmt->execute()) {
            $successMsg = "Appointment booked successfully!";
            $lawyer_id = $schedule_date = ''; // Reset form fields
        } else {
            $errorMsg = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

// Search Lawyers based on City or Specialization
$searchQuery = "SELECT u.user_id, u.name, u.city_id, s.specialization_name 
                FROM users u 
                LEFT JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id
                LEFT JOIN specializations s ON lp.specialization_id = s.specialization_id 
                WHERE u.user_type = 'Lawyer'";

// Apply filters if they are set
if (!empty($_POST['searchCity'])) {
    $searchCity = $_POST['searchCity'];
    $searchQuery .= " AND u.city_id = ?";
}

if (!empty($_POST['searchSpecialization'])) {
    $searchSpecialization = $_POST['searchSpecialization'];
    $searchQuery .= " AND s.specialization_id = ?";  // Use specialization_id for filtering
}

// Prepare and bind parameters
$stmt = $conn->prepare($searchQuery);
if (!empty($searchCity) && !empty($searchSpecialization)) {
    $stmt->bind_param("ii", $searchCity, $searchSpecialization);  // Bind as integers
} elseif (!empty($searchCity)) {
    $stmt->bind_param("i", $searchCity);
} elseif (!empty($searchSpecialization)) {
    $stmt->bind_param("i", $searchSpecialization);  // Bind specialization_id as integer
}

// Execute query and get lawyers
$stmt->execute();
$lawyers = $stmt->get_result();

// Fetch cities and specializations for the filter options
$citiesResult = $conn->query("SELECT city_id, city_name FROM cities ORDER BY city_name ASC");
$specializationsResult = $conn->query("SELECT specialization_id, specialization_name FROM specializations ORDER BY specialization_name ASC");

?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Book Appointment</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="#">Book Appointment</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Appointment Form Start -->
<div class="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="login-form">
                    <h2>Search and Book an Appointment</h2>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success"><?= $successMsg ?></div>
                    <?php elseif ($errorMsg): ?>
                        <div class="alert alert-danger"><?= $errorMsg ?></div>
                    <?php endif; ?>

                    <!-- Search Form for City and Specialization -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="searchCity">City</label>
                            <select class="form-control" name="searchCity">
                                <option value="">Select City</option>
                                <?php while ($city = $citiesResult->fetch_assoc()): ?>
                                    <option value="<?= $city['city_id'] ?>" <?= $city['city_id'] == $searchCity ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($city['city_name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="searchSpecialization">Specialization</label>
                            <select class="form-control" name="searchSpecialization" id="searchSpecialization">
                                <option value="">Select Specialization</option>
                                <?php while ($specialization = $specializationsResult->fetch_assoc()): ?>
                                    <option value="<?= $specialization['specialization_id'] ?>" <?= $specialization['specialization_id'] == $searchSpecialization ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($specialization['specialization_name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark btn-block">Search Lawyers</button>
                        </div>
                    </form>

                    <!-- Display Lawyers Based on Search -->
                    <h3>Available Lawyers</h3>
                    <?php if ($lawyers->num_rows > 0): ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="lawyer_id">Select Lawyer</label>
                                <select class="form-control" name="lawyer_id" required>
                                    <option value="">-- Select Lawyer --</option>
                                    <?php while ($lawyer = $lawyers->fetch_assoc()): ?>
                                        <option value="<?= $lawyer['user_id'] ?>" <?= $lawyer_id == $lawyer['user_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($lawyer['name']) ?> (<?= htmlspecialchars($lawyer['specialization_name']) ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <?php if ($lawyerErr): ?><small class="text-danger"><?= $lawyerErr ?></small><?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="schedule_date">Schedule Date</label>
                                <input type="date" class="form-control" name="schedule_date" value="<?= $schedule_date ?>" required>
                                <?php if ($dateErr): ?><small class="text-danger"><?= $dateErr ?></small><?php endif; ?>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" name="bookBtn" class="btn btn-dark btn-block">Book Appointment</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>No lawyers found based on your search criteria.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Appointment Form End -->

<?php include('inc.footer.php'); ?>
