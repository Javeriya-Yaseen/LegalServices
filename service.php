<?php
include('inc.header.php');

// Fetch specializations for Practices Areas (services)
$services = [];
$sql = "SELECT specialization_name, service_icon, description FROM specializations ORDER BY specialization_name";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
}

// Define dynamic features content (can also be fetched from DB if desired)
$features = [
    [
        'icon' => 'fa fa-gavel',
        'title' => 'Best Law Practices',
        'description' => 'Our legal team follows the highest standards to ensure you get the best representation possible.',
    ],
    [
        'icon' => 'fa fa-balance-scale',
        'title' => 'Efficiency & Trust',
        'description' => 'We value your time and trust, providing timely legal solutions with complete transparency.',
    ],
    [
        'icon' => 'far fa-smile',
        'title' => 'Results You Deserve',
        'description' => 'We work tirelessly to deliver favorable outcomes and peace of mind for our clients.',
    ],
];
?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Practices Areas</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="practices.php">Practices Areas</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Service Start -->
<div class="service">
    <div class="container">
        <div class="section-header">
            <h2>Our Practices Areas</h2>
        </div>
        <div class="row">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="service-icon">
                            <?= $service['service_icon'] ?>
                        </div>
                        <h3><?= htmlspecialchars($service['specialization_name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <a class="btn" href="specialization.php?id=<?= urlencode($service['specialization_name']) ?>">Learn More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Service End -->

<!-- Feature Start -->
<div class="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="section-header">
                    <h2>Why Choose Us</h2>
                </div>
                <?php foreach ($features as $feature): ?>
                    <div class="row align-items-center feature-item">
                        <div class="col-5">
                            <div class="feature-icon">
                                <i class="<?= $feature['icon'] ?>"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <h3><?= htmlspecialchars($feature['title']) ?></h3>
                            <p><?= htmlspecialchars($feature['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-5">
                <div class="feature-img">
                    <img src="img/feature.jpg" alt="Feature">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Feature End -->

<?php include('inc.footer.php'); ?>
