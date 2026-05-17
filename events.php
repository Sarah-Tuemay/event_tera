<?php
require_once 'config/database.php';
require_once 'classes/Event.php';
require_once 'classes/Security.php';

 $eventObj = new Event();
 $allEvents = $eventObj->getAllEvents();
 $viewTitle = "All Events";
 $filteredEvents = $allEvents;

// Check if user clicked a category
if (isset($_GET['category'])) {
    $currentCategory = Security::sanitizeInput($_GET['category']);
    $viewTitle = htmlspecialchars($currentCategory) . " Events";
    
    $filteredEvents = array_filter($allEvents, function($e) use ($currentCategory) {
        return strtolower($e['category']) === strtolower($currentCategory);
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events - EVENT ተራ</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/index.css">
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
            <div style="margin-bottom: 2rem;">
                <a href="index.php" style="color: #00bcd4; text-decoration: none;">← Back to Home</a>
            </div>

            <h2 class="page-title"><?php echo $viewTitle; ?></h2>
            <p class="page-subtitle">Click on an event to see details and RSVP</p>

            <div class="events-grid">
                <?php if (empty($filteredEvents)): ?>
                    <p style="color: #9aa9b1; grid-column: 1/-1;">No events found in this category yet.</p>
                <?php else: ?>
                    <?php foreach ($filteredEvents as $e): ?>
               
                        <div class="event-card" onclick="window.location.href='event-details.php?id=<?php echo $e['id']; ?>'">
                            <?php $imgSrc = !empty($e['image_path']) ? $e['image_path'] : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80'; ?>
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($e['title']); ?>">
                            <span class="category"><?php echo htmlspecialchars($e['category']); ?></span>
                            <div class="content">
                                <h3><?php echo htmlspecialchars($e['title']); ?></h3>
                                <p class="meta">📅 <?php echo date('M d, Y', strtotime($e['event_date'])); ?></p>
                                <p class="meta">📍 <?php echo htmlspecialchars($e['location']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container"><p>© <span id="current-year"></span> EVENT ተራ. All rights reserved.</p></div>
    </footer>

    <script src="assets/js/common.js"></script>
</body>
</html>