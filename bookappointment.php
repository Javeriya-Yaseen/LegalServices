<?php
include('inc.header.php');

// Check login
if (!isset($_SESSION['CustomerLogin']) || !$_SESSION['CustomerLogin']) {
    header("Location: login.php");
    exit;
}

// Variables
$lawyer_id = $schedule_date = '';
$lawyerErr = $dateErr = $successMsg = $errorMsg = '';

// Handle Form Submission
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

        if ($stmt->execute()) {
            $successMsg = "Appointment booked successfully!";
            $lawyer_id = $schedule_date = ''; // reset form
        } else {
            $errorMsg = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch lawyers for dropdown
$lawyers = [];
$result = $conn->query("SELECT user_id, name FROM users WHERE user_type = 'Lawyer'");
while ($row = $result->fetch_assoc()) {
    $lawyers[] = $row;
}
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
                    <h2>Book an Appointment</h2>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success"><?= $successMsg ?></div>
                    <?php elseif ($errorMsg): ?>
                        <div class="alert alert-danger"><?= $errorMsg ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="lawyer_id">Select Lawyer</label>
                            <select class="form-control" name="lawyer_id" required>
                                <option value="">-- Select Lawyer --</option>
                                <?php foreach ($lawyers as $lawyer): ?>
                                    <option value="<?= $lawyer['user_id'] ?>" <?= $lawyer_id == $lawyer['user_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lawyer['name']) ?>
                                    </option>
                                <?php endforeach; ?>
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Appointment Form End -->

<?php include('inc.footer.php'); ?>
