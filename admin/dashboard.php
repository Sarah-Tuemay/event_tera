<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Event.php';
require_once '../classes/Security.php';

User::checkAuth(); // Protects page
 $eventManager = new Event();
 $msg = '';
 $msgType = '';

// [WEEK 5: CRUD - Handling Delete Request]
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $deleteId = (int)$_GET['id'];
    if ($eventManager->deleteEvent($deleteId, $_SESSION['user_id'])) {
        $msg = "Event and its poster deleted successfully.";
        $msgType = "success";
        // Redirect to avoid page refresh resubmit bug
        header("Location: dashboard.php?msg=" . urlencode($msg) . "&type=" . $msgType);
        exit();
    } else {
        $msg = "Failed to delete event. You might not have permission.";
        $msgType = "error";
    }
}

// Check for redirect messages
if (isset($_GET['msg'])) {
    $msg = Security::sanitizeInput($_GET['msg']);
    $msgType = isset($_GET['type']) ? Security::sanitizeInput($_GET['type']) : 'error';
}

// Handle Event Creation (Same as before)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!Security::verifyCSRF($_POST['csrf_token'])) {
        die("Security Error: Invalid CSRF Token!");
    }

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
            $dest = '../assets/uploads/' . $newName;
            
            if(move_uploaded_file($_FILES['poster']['tmp_name'], $dest)) {
                $imagePath = 'assets/uploads/' . $newName;
            } else {
                $msg = "Server failed to save the image."; $msgType = "error";
            }
        } else {
            $msg = "Invalid file type!"; $msgType = "error";
        }
    }

    if(empty($msg)) {
        if($eventManager->create($title, $desc, $category, $date, $location, $imagePath, $_SESSION['user_id'])) {
            $msg = "Event published successfully!"; $msgType = "success";
        } else {
            $msg = "Database error."; $msgType = "error";
        }
    }
}

 $csrfToken = Security::generateCSRF();
// Fetch organizer's specific events to display below
 $myEvents = $eventManager->getEventsByOrganizer($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .event-list-container { margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; }
        .event-item { display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 15px; margin-bottom: 10px; border-radius: 8px; border-left: 4px solid #078930;}
        .event-info h4 { margin: 0 0 5px 0; }
        .event-info small { color: #666; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .btn-delete:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="logout-btn">
            <span>Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong></span> | 
            <a href="logout.php" style="color:red; text-decoration:none;">Logout</a>
        </div>

        <h2>Publish New Event</h2>
        
        <?php if($msg): ?>
            <p class="<?php echo $msgType; ?>"><?php echo $msg; ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
            <div class="form-group">
                <label>Event Title</label>
                <input type="text" name="title" required placeholder="e.g., Software Engineering Expo">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Seminar">Academic Seminar</option>
                        <option value="Workshop">Workshop / Training</option>
                        <option value="Cultural">Cultural / Artistic</option>
                    </select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Event Date</label>
                    <input type="date" name="event_date" required>
                </div>
            </div>
            <div class="form-group">
                <label>Location (AASTU)</label>
                <input type="text" name="location" required placeholder="e.g., Block 04, Room 101">
            </div>
            <div class="form-group">
                <label>Event Poster (Optional)</label>
                <input type="file" name="poster" accept="image/*">
            </div>
            <button type="submit" class="btn">Publish Event</button>
        </form>
        
        <!-- NEW: MANAGE EXISTING EVENTS SECTION -->
        <div class="event-list-container">
            <h3 style="margin-bottom:15px;">Your Active Events</h3>
            <?php if (empty($myEvents)): ?>
                <p style="color:#888;">You haven't created any events yet.</p>
            <?php else: ?>
                <?php foreach ($myEvents as $ev): ?>
                    <div class="event-item">
                        <div class="event-info">
                            <h4><?php echo htmlspecialchars($ev['title']); ?></h4>
                            <small><?php echo $ev['category']; ?> | <?php echo date('M d, Y', strtotime($ev['event_date'])); ?></small>
                        </div>
                        <!-- JS Confirm dialog to prevent accidental clicks -->
                        <a href="?action=delete&id=<?php echo $ev['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure? This will permanently delete the event and its poster!');">
                           Delete
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <p class="link-text" style="margin-top:30px;"><a href="../index.php">← View Public Website</a></p>
    </div>
</body>
</html>