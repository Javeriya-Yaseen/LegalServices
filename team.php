<?php include('inc.header.php') ?>
            
            
            
            <!-- Page Header Start -->
            <div class="page-header">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2>Attorneys</h2>
                        </div>
                        <div class="col-12">
                            <a href="">Home</a>
                            <a href="">Attorneys</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Header End -->

<!-- About Start -->
<div class="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="about-img">
                    <img src="img/about.jpg" alt="Image">
                </div>
            </div>
            <div class="col-lg-7 col-md-6">
                <div class="section-header">
                    <h2>About Our Legal Experts</h2>
                </div>
                <div class="about-text">
                    <p>
                        We are a trusted platform committed to connecting individuals with highly qualified and experienced lawyers across various fields of law. From civil and criminal cases to family law, corporate disputes, and legal advisory, our network of attorneys is here to serve your needs with professionalism and care.
                    </p>
                    <p>
                        Each lawyer on our platform undergoes a verification process and offers detailed profiles showcasing their qualifications, experience, and areas of specialization. Whether you're facing a legal issue or simply need advice, our user-friendly system lets you search, compare, and book appointments with the right legal expert — all in just a few clicks.
                    </p>
                    <a class="btn" href="lawyers.php">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


         <!-- Team Start -->
<div class="team">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Expert Attorneys</h2>
        </div>
        <div class="row">
            <?php
            // Fetch lawyers and their profile data
            $query = "SELECT u.name, s.specialization_name as specialization, u.profile_photo as profile_image, lp.linkedin, lp.facebook, lp.twitter, lp.instagram
                      FROM Users u
                      JOIN Lawyers_Profile lp ON u.user_id = lp.lawyer_id
                      JOIN specializations s ON lp.specialization_id = s.specialization_id
                      JOIN cities c ON u.city_id = c.city_id
                      WHERE u.user_type = 'lawyer' LIMIT 8"; // Adjust LIMIT as needed

            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $name = htmlspecialchars($row['name']);
                $specialization = htmlspecialchars($row['specialization']);
                $image = !empty($row['profile_image']) ? $row['profile_image'] : 'img/default-lawyer.jpg';
            ?>
                <div class="col-lg-3 col-md-6">
                    <div class="team-item">
                        <div class="team-img">
                            <img src="<?php echo 'lawyer/uploads/profiles/' . $image; ?>" alt="Attorney Image" style="height: 300px; object-fit: cover;">
                        </div>
                        <div class="team-text">
                            <h2><?php echo $name; ?></h2>
                            <p><?php echo $specialization; ?> Lawyer</p>
                            <div class="team-social">
                                <?php if (!empty($row['twitter'])): ?>
                                    <a class="social-tw" href="<?php echo $row['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($row['facebook'])): ?>
                                    <a class="social-fb" href="<?php echo $row['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($row['linkedin'])): ?>
                                    <a class="social-li" href="<?php echo $row['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($row['instagram'])): ?>
                                    <a class="social-in" href="<?php echo $row['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Team End -->


  <?php include('inc.footer.php'); ?>
