<?php 
include('inc.header.php'); 

// Prepare filters from GET parameters
$filterCity = $_GET['city'] ?? '';
$filterSpec = $_GET['specialization'] ?? '';

// Fetch all cities for filter dropdown
$citiesResult = $conn->query("SELECT city_id, city_name FROM cities ORDER BY city_name ASC");
$cities = [];
while ($row = $citiesResult->fetch_assoc()) {
    $cities[] = $row;
}

// Fetch all specializations for filter dropdown
$specsResult = $conn->query("SELECT specialization_id, specialization_name FROM specializations ORDER BY specialization_name ASC");
$specializations = [];
while ($row = $specsResult->fetch_assoc()) {
    $specializations[] = $row;
}

// Prepare SQL with optional filters
$sql = "
SELECT u.user_id, u.name, u.profile_photo, c.city_name, s.specialization_name
FROM users u
LEFT JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id
LEFT JOIN specializations s ON lp.specialization_id = s.specialization_id
LEFT JOIN cities c ON u.city_id = c.city_id
WHERE u.user_type = 'Lawyer' 
";

$params = [];
$types = '';
$conditions = [];

if ($filterCity !== '') {
    $conditions[] = "u.city_id = ?";
    $params[] = $filterCity;
    $types .= 'i';
}
if ($filterSpec !== '') {
    $conditions[] = "lp.specialization_id = ?";
    $params[] = $filterSpec;
    $types .= 'i';
}

if ($conditions) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY u.name ASC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$lawyers = [];
while ($row = $result->fetch_assoc()) {
    $lawyers[] = $row;
}

$stmt->close();
$query = "SELECT specialization_name, service_icon, description FROM specializations ORDER BY specialization_name ASC";
$result = $conn->query($query);
?>


<!-- Carousel Start -->
<div id="carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/carousel-1.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <h1 class="animated fadeInLeft">We fight for your justice</h1>
                <p class="animated fadeInRight">Lorem ipsum dolor sit amet elit. Mauris odio mauris...</p>
                <a class="btn animated fadeInUp" href="bookappointment.php">Get free consultation</a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="img/carousel-2.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <h1 class="animated fadeInLeft">We prepared to oppose for you</h1>
                <p class="animated fadeInRight">Lorem ipsum dolor sit amet elit. Mauris odio mauris...</p>
                <a class="btn animated fadeInUp" href="bookappointment.php">Get free consultation</a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="img/carousel-3.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <h1 class="animated fadeInLeft">We fight for your privilege</h1>
                <p class="animated fadeInRight">Lorem ipsum dolor sit amet elit. Mauris odio mauris...</p>
                <a class="btn animated fadeInUp" href="bookappointment.php">Get free consultation</a>
            </div>
        </div>
    </div>

    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- Carousel End -->

<!-- Filter Section -->
<div class="container mt-4">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-auto">
            <select class="form-select" name="city" onchange="this.form.submit()">
                <option value="">All Cities</option>
                <?php foreach($cities as $city): ?>
                <option value="<?= $city['city_id'] ?>" <?= ($filterCity == $city['city_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($city['city_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <select class="form-select" name="specialization" onchange="this.form.submit()">
                <option value="">All Specializations</option>
                <?php foreach($specializations as $spec): ?>
                <option value="<?= $spec['specialization_id'] ?>" <?= ($filterSpec == $spec['specialization_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($spec['specialization_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
        </div>
    </form>
</div>

<!-- Lawyers List -->
<div class="container mt-5">
    <h2>Our Expert Lawyers</h2>
    <div class="row">
        <?php if (count($lawyers) > 0): ?>
            <?php foreach ($lawyers as $lawyer): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php
                        $photo = $lawyer['profile_photo'] ? "lawyer/uploads/profiles/{$lawyer['profile_photo']}" : "assets/img/default-user.png";
                        ?>
                        <img src="<?= htmlspecialchars($photo) ?>" class="card-img-top" alt="<?= htmlspecialchars($lawyer['name']) ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($lawyer['name']) ?></h5>
                            <p class="card-text"><strong>Specialization:</strong> <?= htmlspecialchars($lawyer['specialization_name'] ?? 'N/A') ?></p>
                            <p class="card-text"><strong>City:</strong> <?= htmlspecialchars($lawyer['city_name'] ?? 'N/A') ?></p>
                            <a href="bookappointment.php?id=<?= $lawyer['user_id'] ?>" class="btn btn-primary">Book Appointment</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No lawyers found for the selected filters.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Top Feature Start-->
<div class="feature-top">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-3 col-sm-6">
                <div class="feature-item">
                    <i class="far fa-check-circle"></i>
                    <h3>Legal</h3>
                    <p>Govt Approved</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-item">
                    <i class="fa fa-user-tie"></i>
                    <h3>Attorneys</h3>
                    <p>Expert Attorneys</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-item">
                    <i class="far fa-thumbs-up"></i>
                    <h3>Success</h3>
                    <p>99.99% Case Won</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-item">
                    <i class="far fa-handshake"></i>
                    <h3>Support</h3>
                    <p>Quick Support</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Top Feature End-->


<!-- About Start -->
<div class="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="about-img">
                    <img src="img/about.jpg" alt="About Our Law Firm">
                </div>
            </div>
            <div class="col-lg-7 col-md-6">
                <div class="section-header">
                    <h2>Learn About Us</h2>
                </div>
                <div class="about-text">
                    <p>
                        At Legal Services, we are committed to providing expert legal advice and representation tailored to the unique needs of each client. With years of experience across multiple specialties, our team of dedicated attorneys works tirelessly to uphold justice and protect your rights.
                    </p>
                    <p>
                        Our firm combines deep legal knowledge with compassionate client care, ensuring clear communication and strategic solutions. Whether you are facing a complex legal challenge or seeking guidance for future matters, we stand by your side every step of the way, delivering results you can trust.
                    </p>
                    <a class="btn" href="about.php">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->



<!-- Service Start -->
<div class="service">
    <div class="container">
        <div class="section-header">
            <h2>Our Practices Areas</h2>
        </div>
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-item">
                    <div class="service-icon">
                        <?= $row['service_icon'] ?: '<i class="fa fa-briefcase"></i>' ?>
                    </div>
                    <h3><?= htmlspecialchars($row['specialization_name']) ?></h3>
                    <p>
                        <?= htmlspecialchars($row['description']) ?>
                    </p>
                    <a class="btn" href="">Learn More</a>
                </div>
            </div>
            <?php endwhile; ?>
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
                <div class="row align-items-center feature-item">
                    <div class="col-5">
                        <div class="feature-icon">
                            <i class="fa fa-gavel"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <h3>Experienced Legal Professionals</h3>
                        <p>
                            Our team consists of seasoned attorneys with extensive knowledge in various fields of law, ensuring you receive expert guidance and representation.
                        </p>
                    </div>
                </div>
                <div class="row align-items-center feature-item">
                    <div class="col-5">
                        <div class="feature-icon">
                            <i class="fa fa-balance-scale"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <h3>Integrity & Trust</h3>
                        <p>
                            We prioritize transparency and honesty in every case, building lasting relationships with clients based on trust and ethical practice.
                        </p>
                    </div>
                </div>
                <div class="row align-items-center feature-item">
                    <div class="col-5">
                        <div class="feature-icon">
                            <i class="far fa-smile"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <h3>Client-Focused Results</h3>
                        <p>
                            We are dedicated to achieving the best possible outcomes, tailoring strategies to meet your unique legal needs and goals.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="feature-img">
                    <img src="img/feature.jpg" alt="Our Commitment to You">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Feature End -->



<!-- Team Start -->
<div class="team">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Expert Attorneys</h2>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="team-item">
                    <div class="team-img">
                        <img src="img/team-1.jpg" alt="Team Image">
                    </div>
                    <div class="team-text">
                        <h2>Adam Phillips</h2>
                        <p>Business Consultant</p>
                        <div class="team-social">
                            <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                            <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-item">
                    <div class="team-img">
                        <img src="img/team-2.jpg" alt="Team Image">
                    </div>
                    <div class="team-text">
                        <h2>Dylan Adams</h2>
                        <p>Criminal Consultant</p>
                        <div class="team-social">
                            <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                            <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-item">
                    <div class="team-img">
                        <img src="img/team-3.jpg" alt="Team Image">
                    </div>
                    <div class="team-text">
                        <h2>Gloria Edwards</h2>
                        <p>Divorce Consultant</p>
                        <div class="team-social">
                            <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                            <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-item">
                    <div class="team-img">
                        <img src="img/team-4.jpg" alt="Team Image">
                    </div>
                    <div class="team-text">
                        <h2>Josh Dunn</h2>
                        <p>Immigration Consultant</p>
                        <div class="team-social">
                            <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                            <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->


<!-- FAQs Start -->
<div class="faqs">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="faqs-img">
                    <img src="img/faqs.jpg" alt="Frequently Asked Questions">
                </div>
            </div>
            <div class="col-md-7">
                <div class="section-header">
                    <h2>Have Questions?</h2>
                </div>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                <span>1</span> How do I book a consultation with a lawyer?
                            </a>
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                            <div class="card-body">
                                You can easily book a consultation by selecting your preferred lawyer and scheduling an appointment through our online booking system.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#collapseTwo">
                                <span>2</span> What types of legal services do you offer?
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                We provide a wide range of legal services including criminal law, family law, business law, civil litigation, education law, and cyber law, among others.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#collapseThree">
                                <span>3</span> How can I find a lawyer specializing in my legal issue?
                            </a>
                        </div>
                        <div id="collapseThree" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Use our search and filter options to find lawyers by specialization and location to ensure you get the best match for your case.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#collapseFour">
                                <span>4</span> What are your consultation fees?
                            </a>
                        </div>
                        <div id="collapseFour" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Consultation fees vary depending on the lawyer and case complexity. You can view fee details on each lawyer's profile before booking.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#collapseFive">
                                <span>5</span> How is my personal information protected?
                            </a>
                        </div>
                        <div id="collapseFive" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                We take privacy seriously. All personal data is stored securely and only shared with authorized legal professionals involved in your case.
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn" href="contact.php">Ask more</a>
            </div>
        </div>
    </div>
</div>
<!-- FAQs End -->



<!-- Testimonial Start -->
<div class="testimonial">
    <div class="container">
        <div class="section-header">
            <h2>Review From Client</h2>
        </div>
        <div class="owl-carousel testimonials-carousel">
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-1.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-2.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-3.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-4.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-1.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-2.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <i class="fa fa-quote-right"></i>
                <div class="row align-items-center">
                    <div class="col-3">
                        <img src="img/testimonial-3.jpg" alt="">
                    </div>
                    <div class="col-9">
                        <h2>Client Name</h2>
                        <p>Profession</p>
                    </div>
                    <div class="col-12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam accumsan lacus eget velit
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->


<!-- Blog Start -->
<div class="blog">
    <div class="container">
        <div class="section-header">
            <h2>Latest From Blog</h2>
        </div>
        <div class="owl-carousel blog-carousel">
            <div class="blog-item">
                <img src="img/blog-1.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Civil Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-2.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Family Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-3.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Business Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-1.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Education Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-2.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Criminal Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-3.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Cyber Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="blog-item">
                <img src="img/blog-1.jpg" alt="Blog">
                <h3>Lorem ipsum dolor</h3>
                <div class="meta">
                    <i class="fa fa-list-alt"></i>
                    <a href="">Business Law</a>
                    <i class="fa fa-calendar-alt"></i>
                    <p>01-Jan-2045</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor
                </p>
                <a class="btn" href="">Read More <i class="fa fa-angle-right"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- Blog End -->



<?php include('inc.footer.php'); ?>