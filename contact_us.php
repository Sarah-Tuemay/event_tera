<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EVENT ተራ</title>
    <meta name="description" content="Get in touch with EVENT ተራ. Contact us for event promotions, partnerships, or support.">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="icon" href="images/Temp-favicon.jpg">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
<header class="header">
    <a href="index.php" class="logo">
        EVENT ተራ
        <span>AASTU Event Finder</span>
    </a>
    <button class="mobile-nav-toggle" id="mobile-menu-btn" aria-label="Toggle navigation">
        <span class="hamburger"></span>
    </button>
    <nav id="main-nav">
        <a href="index.php">Home</a>
        <a href="events.php">Browse Events</a>
        <a href="about_us.php">About</a>
        <a href="contact_us.php" class="active">Contact Us</a>
    </nav>
</header>

    <main id="main-content">
        <div id="error-container" class="error-container"></div>
        
        <section class="hero">
            <div class="container">
                <h1>Contact Us</h1>
                <p>We're here to support AASTU's event community</p>
            </div>
        </section>

        <section class="contact-cards">
            <div class="container">
                <h2>Get in Touch</h2>
                <div class="cards-grid">
                    <article class="card">
                        <div class="card-icon">📍</div>
                        <h3>Location</h3>
                        <p>AASTU, Addis Ababa, Ethiopia</p>
                    </article>
                    <article class="card">
                        <div class="card-icon">📧</div>
                        <h3>Email</h3>
                        <p><a href="mailto:support@eventtera.et">support@eventtera.et</a></p>
                    </article>
                    <article class="card">
                        <div class="card-icon">📞</div>
                        <h3>Phone</h3>
                        <p><a href="tel:+251900000000">+251 900 00 0000</a></p>
                    </article>
                    <article class="card">
                        <div class="card-icon">🌐</div>
                        <h3>Social Media</h3>
                        <p>@event_tera</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="contact-form-section">
            <div class="container">
                <div class="form-container">
                    <h2>Send Us a Message</h2>
                    <form id="contact-form" novalidate>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                                <span class="error-message"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reason">Reason for Contact</label>
                            <select id="reason" name="reason">
                                <option value="">Select a reason</option>
                                <option value="general">General Inquiry</option>
                                <option value="promotion">Event Promotion</option>
                                <option value="support">Technical Support</option>
                                <option value="partnership">Partnership</option>
                            </select>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" placeholder="Write your message here" required></textarea>
                            <span class="error-message"></span>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
                
                <div class="info-container">
                    <h3>Office Hours</h3>
                    <div class="hours-list">
                        <div class="hours-item">
                            <span class="day">Monday – Friday</span>
                            <span class="time">9:00 AM – 5:00 PM</span>
                        </div>
                        <div class="hours-item">
                            <span class="day">Saturday</span>
                            <span class="time">10:00 AM – 2:00 PM</span>
                        </div>
                        <div class="hours-item">
                            <span class="day">Sunday</span>
                            <span class="time">Closed</span>
                        </div>
                    </div>
                    
                    <div class="response-time">
                        <h4>Response Time</h4>
                        <p>We typically respond within 24 hours during business days.</p>
                    </div>

                    <div class="faq-section">
                        <h4>Frequently Asked Questions</h4>
                        <div class="faq-item">
                            <button class="faq-toggle" onclick="toggleFAQ(this)">
                                <span>How do I list my event on EVENT ተራ?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-content">
                                <p>Simply register for an Organizer account via the Admin Portal, and you can publish events directly to the platform.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <button class="faq-toggle" onclick="toggleFAQ(this)">
                                <span>Is there a cost to use EVENT ተራ?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-content">
                                <p>No, EVENT ተራ is completely free for both event organizers and attendees.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <button class="faq-toggle" onclick="toggleFAQ(this)">
                                <span>Who can RSVP?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-content">
                                <p>Anyone with an AASTU email or general email address can RSVP for listed events.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© <span id="current-year"></span> EVENT ተራ. All rights reserved.</p>
            <p>
                <a href="about_us.php">About</a> | 
                <a href="contact_us.php">Contact</a> | 
                <a href="events.php">Events</a>
            </p>
        </div>
    </footer>

    <script src="assets/js/common.js"></script>
    <script>
        // FAQ toggle function
        function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            button.classList.toggle('active');
            content.classList.toggle('show');
            
            if (content.classList.contains('show')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>