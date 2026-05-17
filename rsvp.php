<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Event.php';
require_once 'classes/Security.php';

 $eventObj = new Event();
 $event = null;
 $successMsg = '';
 $errorMsg = '';

if(isset($_GET['id'])) {
    $eventId = (int)$_GET['id'];
    foreach($eventObj->getAllEvents() as $e) {

        if((int)$e['id'] == $eventId) { 
            $event = $e; 
            break; 
        }
    }
}

// 2. Handle RSVP Form Submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!$event) {
        $errorMsg = "Invalid event.";
    } else {
        $name = Security::sanitizeInput($_POST['name']);
        $email = Security::sanitizeInput($_POST['email']);
        $phone = Security::sanitizeInput($_POST['phone']);
        $attendees = (int)$_POST['attendees'];
        $message = Security::sanitizeInput($_POST['message']);

        if(empty($name) || empty($email)) {
            $errorMsg = "Name and Email are required.";
        } else {
            try {
                $sql = "INSERT IGNORE INTO rsvps (event_id, attendee_name, attendee_email, phone, num_attendees, message) 
                        VALUES (:eid, :name, :email, :phone, :att, :msg)";
                $stmt = $GLOBALS['pdo']->prepare($sql);
                $stmt->execute([
                    ':eid' => $event['id'], 
                    ':name' => $name, 
                    ':email' => $email,
                    ':phone' => $phone, 
                    ':att' => $attendees, 
                    ':msg' => $message
                ]);

                if($stmt->rowCount() > 0) {
                    $successMsg = "🎉 RSVP Confirmed! See you there, " . htmlspecialchars($name) . ".";
                } else {
                    $errorMsg = "This email has already RSVPed for this event.";
                }
            } catch(PDOException $e) {
                $errorMsg = "Database error occurred.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP - EVENT ተራ</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/rsvp.css">
    <link rel="icon" href="images/Temp-favicon.jpg">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <header class="header">
        <a href="index.php" class="logo">EVENT ተራ<span>AASTU Event Finder</span></a>
        <button class="mobile-nav-toggle" id="mobile-menu-btn" aria-label="Toggle navigation"><span class="hamburger"></span></button>
        <nav id="main-nav">
            <a href="index.php">Home</a><a href="events.php">Browse Events</a><a href="about_us.php">About</a><a href="contact_us.php">Contact Us</a>
        </nav>
    </header>

    <main id="main-content">
        <div class="container">
            <div class="back-link"><a href="javascript:history.back()" class="back-btn">← Back to Event</a></div>
            <section class="page-title"><h1>RSVP for Event</h1><p>Fill out the form below to confirm your attendance</p></section>
            
            <section class="rsvp-container">
                <div class="event-details-card">
                    <div id="event-preview">
                        <?php if($event): ?>
                            <?php $imgSrc = !empty($event['image_path']) ? $event['image_path'] : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80'; ?>
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <div class="event-detail-item"><strong>Date:</strong> <?php echo date('F d, Y', strtotime($event['event_date'])); ?></div>
                            <div class="event-detail-item"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></div>
                            <div class="admission-badge">Free Admission</div>
                        <?php else: ?>
                            <p style="color:red;">Event not found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="rsvp-form-card">
                    <h2>Confirm Your Attendance</h2>
                    
                    <?php if($successMsg): ?>
                        <div class="success-message show"><?php echo $successMsg; ?></div>
                    <?php else: ?>
                        <?php if($errorMsg): ?>
                            <div style="color: #f44336; margin-bottom: 15px; text-align: center; background: rgba(244,67,54,0.1); padding: 1rem; border-radius: 8px;"><?php echo $errorMsg; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" placeholder="your.email@example.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" placeholder="+251 911 123 456" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="attendees">Number of Attendees *</label>
                                <input type="number" id="attendees" name="attendees" min="1" max="10" value="1" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Additional Message (Optional)</label>
                                <textarea id="message" name="message" rows="4" placeholder="Any questions or special requirements?"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Confirm RSVP</button>
                        </form>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    <footer class="footer"><div class="container"><p>© <span id="current-year"></span> EVENT ተራ. All rights reserved.</p></div></footer>
    
    <script src="assets/js/common.js"></script>
</body>
</html>