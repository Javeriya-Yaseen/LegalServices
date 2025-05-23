<?php include('inc.header.php') ?>
            
            
            <!-- Page Header Start -->
            <div class="page-header">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2>About Us</h2>
                        </div>
                        <div class="col-12">
                            <a href="">Home</a>
                            <a href="">About Us</a>
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
                                <h2>Learn About Us</h2>
                            </div>
                            <div class="about-text">
                                <p>
                                    At Legal Services, we are committed to providing expert legal advice and representation tailored to the unique needs of each client. With years of experience across multiple specialties, our team of dedicated attorneys works tirelessly to uphold justice and protect your rights.
                                </p>
                                <p>
                                    Our firm specializes in various areas of law, including family law, criminal defense
                                </p>
                                <a class="btn" href="">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About End -->


            <!-- Timeline Start -->
<div class="timeline">
    <div class="container">
        <div class="section-header">
            <h2>Learn About Our Journey</h2>
        </div>
        <div class="timeline-start">
            <div class="timeline-container left">
                <div class="timeline-content">
                    <h2><span>2020</span>Launch of Our Legal Platform</h2>
                    <p>
                        We officially launched our digital platform with the mission to connect individuals with trusted legal professionals across various specialties, all from the comfort of their homes.
                    </p>
                </div>
            </div>
            <div class="timeline-container right">
                <div class="timeline-content">
                    <h2><span>2021</span>Expanded Lawyer Network Nationwide</h2>
                    <p>
                        With the addition of hundreds of verified lawyers from major cities, we expanded our network, allowing clients to easily find legal help based on city and specialization.
                    </p>
                </div>
            </div>
            <div class="timeline-container left">
                <div class="timeline-content">
                    <h2><span>2022</span>Launched Online Appointment System</h2>
                    <p>
                        We introduced a seamless online appointment booking system, giving clients the ability to schedule consultations with lawyers securely and efficiently.
                    </p>
                </div>
            </div>
            <div class="timeline-container right">
                <div class="timeline-content">
                    <h2><span>2023</span>Enhanced Profiles & Lawyer Ratings</h2>
                    <p>
                        Lawyer profiles were upgraded to include detailed bios, experience, specializations, and user reviews, empowering clients to make informed legal choices.
                    </p>
                </div>
            </div>
            <div class="timeline-container left">
                <div class="timeline-content">
                    <h2><span>2024</span>Customer & Lawyer Dashboards</h2>
                    <p>
                        We rolled out intuitive dashboards for both clients and lawyers to manage appointments, update profiles, and track consultations efficiently.
                    </p>
                </div>
            </div>
            <div class="timeline-container right">
                <div class="timeline-content">
                    <h2><span>2025</span>AI-Powered Legal Matching (Coming Soon)</h2>
                    <p>
                        We're working on AI-powered lawyer recommendations based on your legal needs, location, and past consultation patterns — ensuring smarter legal help for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Timeline End -->



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
