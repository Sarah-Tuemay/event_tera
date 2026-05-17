<?php
require_once 'config/database.php';
require_once 'classes/Event.php';

$eventObj = new Event();
$allEvents = $eventObj->getAllEvents();
$featuredEvents = array_slice($allEvents, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENT ተራ - AASTU Events</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" href="images/Temp-favicon.png">
</head>

<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="header">
        <a href="index.php" class="logo">EVENT ተራ<span>AASTU Event Finder</span></a>
        <button class="mobile-nav-toggle" id="mobile-menu-btn" aria-label="Toggle navigation"><span class="hamburger"></span></button>
        <nav id="main-nav">
            <a href="index.php" class="active">Home</a>
            <a href="events.php">Browse Events</a>
            <a href="about_us.php">About</a>
            <a href="contact_us.php">Contact Us</a>
            <a href="admin/login.php" style="color: #00bcd4; font-weight: bold;">Admin Portal</a>
        </nav>
    </header>

    <main id="main-content">
        <div id="error-container" class="error-container"></div>

        <section class="hero">
            <div class="container">
                <h1>Discover Campus Events at AASTU</h1>
                <p>Find free community events, cultural gatherings, and workshops near you</p>
                <div class="search-box">
                    <input type="text" placeholder="Search events..." id="home-search">
                    <button type="button" onclick="window.location.href='events.php'">Browse All Events</button>
                </div>
            </div>
        </section>

        <section class="featured-section">
            <div class="container">
                <h2>Featured Events</h2>
                <div id="featured-events-container" class="events-grid">
                    <?php if (empty($featuredEvents)): ?>
                        <p>No events found.</p>
                    <?php else: ?>
                        <?php foreach ($featuredEvents as $event): ?>
                            <div class="event-card" onclick="window.location.href='events.php?id=<?php echo $event['id']; ?>'">
                                <?php $imgSrc = !empty($event['image_path']) ? $event['image_path'] : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80'; ?>
                                <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                                <span class="category"><?php echo htmlspecialchars($event['category']); ?></span>
                                <div class="content">
                                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p class="meta">📅 <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
                                    <p class="meta">📍 <?php echo htmlspecialchars($event['location']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="categories">
            <div class="container">
                <h2>Explore Categories</h2>
                <div class="category-grid">
                    <div class="category-card" onclick="window.location.href='events.php?category=Arts%20%26%20Culture'">
                        <div class="category-icon">🎨</div>
                        <h3>Arts & Culture</h3>
                    </div>
                    <div class="category-card" onclick="window.location.href='events.php?category=Music'">
                        <div class="category-icon">🎵</div>
                        <h3>Music</h3>
                    </div>
                    <div class="category-card" onclick="window.location.href='events.php?category=Technology'">
                        <div class="category-icon">💻</div>
                        <h3>Technology</h3>
                    </div>
                    <div class="category-card" onclick="window.location.href='events.php?category=Community'">
                        <div class="category-icon">🤝</div>
                        <h3>Community</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">

                    <div class="stat-item">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Monthly Events</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500</div>
                        <div class="stat-label">Active Students</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Clubs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Free Events</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2>How EVENT ተራ Works</h2>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Browse Events</h3>
                        <p>Explore events at AASTU</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Find What You Like</h3>
                        <p>Filter by category and date</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>RSVP & Attend</h3>
                        <p>Confirm your attendance</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© <span id="current-year"></span> EVENT ተራ. All rights reserved.</p>
            <p><a href="about_us.php">About</a> | <a href="contact_us.php">Contact</a> | <a href="events.php">Events</a></p>
        </div>
    </footer>

    <script src="assets/js/common.js"></script>
</body>

</html>