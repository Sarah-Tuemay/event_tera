<?php
require_once 'config/database.php';
require_once 'classes/Event.php';

 $eventObj = new Event();
 $event = null;

if (isset($_GET['id'])) {
    $eventId = (int)$_GET['id'];
    foreach ($eventObj->getAllEvents() as $e) {
        if ((int)$e['id'] == $eventId) { 
            $event = $e;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details - EVENT ተራ</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/landingpage.css">
    <link rel="icon" href="images/Temp-favicon.jpg">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <header class="header">
        <a href="index.php" class="logo">EVENT ተራ<span>AASTU Event Finder</span></a>
        <button class="mobile-nav-toggle" id="mobile-menu-btn" aria-label="Toggle navigation"><span class="hamburger"></span></button>
        <nav id="main-nav">
            <a href="index.php">Home</a>
            <a href="events.php" class="active">Browse Events</a>
            <a href="about_us.php">About</a>
            <a href="contact_us.php">Contact Us</a>
        </nav>
    </header>

    <main id="main-content">
        <div class="container">
            <?php if (!$event): ?>
                <div class="loading">
                    <p style="color: #f44336;">Event not found or invalid ID.</p>
                    <a href="events.php" class="btn btn-primary" style="margin-top:1rem; display:inline-block;">Browse All Events</a>
                </div>
            <?php else: ?>
                <?php 
                $imgSrc = !empty($event['image_path']) ? $event['image_path'] : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80';
                $formattedDate = date('F d, Y', strtotime($event['event_date']));
                ?>
                
                <div style="margin-bottom: 2rem;">
                    <a href="events.php" style="color: #00bcd4; text-decoration: none;">← Back to All Events</a>
                </div>

                <div class="event-detail">
                    <div class="event-main">
                        <img src="<?php echo $imgSrc; ?>" class="event-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
                        <div class="event-content">
                            <h1 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h1>
                            <div class="event-meta">
                                <div class="meta-item">📅 <?php echo $formattedDate; ?></div>
                                <div class="meta-item">📍 <?php echo htmlspecialchars($event['location']); ?></div>
                                <div class="meta-item">👤 <?php echo htmlspecialchars($event['organizer_name']); ?></div>
                            </div>
                            <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            
                            <div class="event-actions">

                                <a href="rsvp.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">RSVP Now</a>
                                <a href="events.php" class="btn btn-secondary">Back to Events</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-sidebar">
                        <div class="sidebar-card">
                            <h3>Event Details</h3>
                            <div class="info-item"><span class="info-label">Category</span> <span class="info-value"><?php echo htmlspecialchars($event['category']); ?></span></div>
                            <div class="info-item"><span class="info-label">Date</span> <span class="info-value"><?php echo $formattedDate; ?></span></div>
                            <div class="info-item"><span class="info-label">Location</span> <span class="info-value"><?php echo htmlspecialchars($event['location']); ?></span></div>
                        </div>
                        <div class="sidebar-card">
                            <span class="category-badge"><?php echo htmlspecialchars($event['category']); ?></span>
                            <span class="admission-badge">Free Admission</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container"><p>© <span id="current-year"></span> EVENT ተራ. All rights reserved.</p></div>
    </footer>

    <script src="assets/js/common.js"></script>
</body>
</html>