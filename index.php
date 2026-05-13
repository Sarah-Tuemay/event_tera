<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENT ተራ | AASTU Arts & Culture</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <div class="logo">EVENT ተራ</div>
        <div class="header-links">
            <a href="admin/login.php">Organizer Portal</a>
        </div>
    </header>

    <section class="search-section">
        <h1>Discover Campus Life</h1>
        <p>An exclusive space for AASTU's most captivating seminars, workshops, and cultural showcases.</p>
        
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Search by title, location, or category...">
            <button>Explore</button>
        </div>

        <div class="filters">
            <button class="filter-btn active" data-category="All">All Showcases</button>
            <button class="filter-btn" data-category="Seminar">Seminars</button>
            <button class="filter-btn" data-category="Workshop">Workshops</button>
            <button class="filter-btn" data-category="Cultural">Cultural</button>
        </div>
    </section>

    <main class="event-container">
        <div class="event-grid" id="event-grid">
            <p style="color: #666;">Curating events for you...</p>
        </div>
    </main>

    <!-- ARTSY RSVP MODAL (Glassmorphism) -->
    <div class="modal-overlay" id="rsvpModal">
        <div class="modal-box">
            <h3>Secure Your Spot</h3>
            <form id="rsvpForm">
                <input type="hidden" id="modalEventId" name="event_id">
                <input type="text" id="modalName" name="attendee_name" placeholder="Your Full Name" required>
                <input type="email" id="modalEmail" name="attendee_email" placeholder="Your AASTU Email" required>
                <div class="modal-actions">
                    <button type="button" class="btn-close" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Confirm RSVP</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>