<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Event.php';
require_once '../classes/Security.php';

User::checkAuth();
 $eventManager = new Event();
 $msg = ''; $msgType = '';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($eventManager->deleteEvent((int)$_GET['id'], $_SESSION['user_id'])) {
        header("Location: dashboard.php?msg=Event+deleted+successfully&type=success");
        exit();
    }
}
if (isset($_GET['msg'])) { $msg = Security::sanitizeInput($_GET['msg']); $msgType = Security::sanitizeInput($_GET['type']); }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!Security::verifyCSRF($_POST['csrf_token'])) die("Security Error");
    $title = Security::sanitizeInput($_POST['title']);
    $desc = Security::sanitizeInput($_POST['description']);
    $category = Security::sanitizeInput($_POST['category']);
    $date = Security::sanitizeInput($_POST['event_date']);
    $location = Security::sanitizeInput($_POST['location']);
    $imagePath = NULL;

    if(isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));
        if(in_array($fileExt, $allowed)) {
            $newName = uniqid('event_') . '.' . $fileExt;
            if(move_uploaded_file($_FILES['poster']['tmp_name'], '../assets/uploads/' . $newName)) {
                $imagePath = 'assets/uploads/' . $newName;
            }
        }
    }
    if($eventManager->create($title, $desc, $category, $date, $location, $imagePath, $_SESSION['user_id'])) {
        $msg = "Event published!"; $msgType = "success";
    } else { $msg = "Failed to publish."; $msgType = "error"; }
}

 $csrfToken = Security::generateCSRF();
 $myEvents = $eventManager->getEventsByOrganizer($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" href="../images/Temp-favicon.jpg">
</head>
<body>
    <header class="header">
        <a href="../index.php" class="logo">EVENT ተራ<span>Admin Portal</span></a>
        <nav id="main-nav"><a href="logout.php" style="color:#f44336;">Logout</a></nav>
    </header>
    <main id="main-content">
        <div class="container">
            <div class="dashboard-container">
                <h2>Welcome, <?php echo $_SESSION['user_name']; ?></h2>
                <?php if($msg): ?><p class="<?php echo $msgType; ?>"><?php echo $msg; ?></p><?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <label>Event Title</label>
                    <input type="text" name="title" required>
                    <label>Description</label>
                    <textarea name="description" rows="4" required></textarea>
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Arts & Culture">Arts & Culture</option>
                        <option value="Music">Music</option>
                        <option value="Technology">Technology</option>
                        <option value="Community">Community</option>
                    </select>
                    <label>Event Date</label>
                    <input type="date" name="event_date" required>
                    <label>Location (AASTU)</label>
                    <input type="text" name="location" required placeholder="e.g., Block 04, Room 101">
                    <label>Event Poster</label>
                    <input type="file" name="poster" accept="image/*">
                    <button type="submit" class="btn-submit">Publish Event</button>
                </form>
                
                <div class="event-list">
                    <h2 style="margin-bottom:15px;">Your Active Events</h2>
                    <?php if (empty($myEvents)): ?>
                        <p style="color:#888;">You haven't created any events yet.</p>
                    <?php else: ?>
                        <?php foreach ($myEvents as $ev): ?>
                            <div class="event-item">
                                <div>
                                    <h4><?php echo htmlspecialchars($ev['title']); ?></h4>
                                    <small><?php echo $ev['category']; ?> | <?php echo date('M d, Y', strtotime($ev['event_date'])); ?></small>
                                </div>
                                <a href="?action=delete&id=<?php echo $ev['id']; ?>" class="btn-delete" onclick="return confirm('Delete this event permanently?');">Delete</a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p style="text-align:center; margin-top:20px;"><a href="../index.php" style="color:#00bcd4; text-decoration:none;">← View Public Website</a></p>
            </div>
        </div>
    </main>
</body>
</html>