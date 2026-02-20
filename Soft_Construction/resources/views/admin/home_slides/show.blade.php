<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #4b5563;
            line-height: 1.6;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .back-btn i {
            margin-right: 8px;
        }

        /* Contact Detail Card */
        .contact-detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-body {
            padding: 30px;
        }

        /* Map Preview Section */
        .map-preview-section {
            margin-bottom: 30px;
        }

        .map-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .map-label {
            background: #3b82f6;
            color: white;
            padding: 12px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .map-label i {
            font-size: 18px;
        }

        .map-wrapper {
            height: 400px;
            width: 100%;
        }

        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Detail Section */
        .detail-section {
            margin-top: 30px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .detail-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .detail-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .detail-label {
            color: #3b82f6;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }

        .detail-label i {
            width: 20px;
            text-align: center;
        }

        .detail-value {
            color: #4b5563;
            margin: 0;
            line-height: 1.6;
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-icon.facebook {
            background: #3b5998;
        }

        .social-icon.twitter {
            background: #1da1f2;
        }

        .social-icon.linkedin {
            background: #0077b5;
        }

        .social-icon.instagram {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-action {
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        .particle:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            left: 70%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 80%;
            left: 30%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 40%;
            left: 50%;
            animation-delay: 6s;
        }

        .particle:nth-child(5) {
            width: 60px;
            height: 60px;
            top: 10%;
            left: 80%;
            animation-delay: 8s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .back-btn {
                margin-top: 15px;
                width: 100%;
                justify-content: center;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-eye"></i> Contact Details</h1>
                <p>View comprehensive contact information</p>
            </div>
            <a href="#" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Contacts
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="contact-detail-card">
            <div class="card-body">
                <!-- Map Preview Section -->
                <div class="map-preview-section">
                    <div class="map-container">
                        <div class="map-label">
                            <i class="fas fa-map-marked-alt"></i> Location Map
                        </div>
                        <div class="map-wrapper">
                            <!-- Sample map iframe for demonstration -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1840523761735!2d-73.9876141845825!3d40.74824397932678!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1645562346326!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="detail-section">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </h5>
                            <p class="detail-value">350 5th Ave, New York, NY 10118, USA</p>
                        </div>
                        
                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-phone"></i> Phone
                            </h5>
                            <p class="detail-value">+1 (212) 736-3100</p>
                        </div>

                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-envelope"></i> Email
                            </h5>
                            <p class="detail-value">info@example.com</p>
                        </div>
                        
                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-clock"></i> Opening Hours
                            </h5>
                            <p class="detail-value">Monday-Friday: 9AM - 5PM<br>Saturday: 10AM - 4PM<br>Sunday: Closed</p>
                        </div>
                        
                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-calendar-alt"></i> Created At
                            </h5>
                            <p class="detail-value">Jan 15, 2023 14:30</p>
                        </div>

                        <div class="detail-item">
                            <h5 class="detail-label">
                                <i class="fas fa-clock"></i> Last Updated
                            </h5>
                            <p class="detail-value">Feb 28, 2023 10:15</p>
                        </div>

                        <!-- Social Media Links -->
                        <div class="detail-item full-width">
                            <h5 class="detail-label">
                                <i class="fas fa-share-alt"></i> Social Media Links
                            </h5>
                            <div class="social-links">
                                <a href="#" target="_blank" class="social-icon facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" target="_blank" class="social-icon twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" target="_blank" class="social-icon linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" target="_blank" class="social-icon instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit Contact
                    </a>
                    
                    <button type="submit" class="btn-action btn-delete">
                        <i class="fas fa-trash"></i> Delete Contact
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Confirmation dialog for delete
        function confirmDelete() {
            return confirm('Are you sure you want to delete this contact? This action cannot be undone.');
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Stagger animations for detail items
            const detailItems = document.querySelectorAll('.detail-item');
            detailItems.forEach((item, index) => {
                item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
                item.style.animation = 'fadeInUp 0.6s ease-out both';
            });

            // Add hover effects to social icons
            const socialIcons = document.querySelectorAll('.social-icon');
            socialIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.2)';
                });
                icon.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Parallax effect for particles
            document.addEventListener('mousemove', function(e) {
                const particles = document.querySelectorAll('.particle');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                particles.forEach((particle, index) => {
                    const speed = (index + 1) * 0.5;
                    particle.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
                });
            });
        });
    </script>
</body>
</html>