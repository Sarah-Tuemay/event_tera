<?php
// [WEEK 4: Forms & User Input] & [WEEK 5: PHP-MySQL Integration]
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Security.php';

// Only accept POST requests
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

 $name = Security::sanitizeInput($_POST['attendee_name']);
 $email = Security::sanitizeInput($_POST['attendee_email']);
 $event_id = (int)$_POST['event_id'];

if(empty($name) || empty($email) || empty($event_id)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

try {
    // [WEEK 11: Security - Prepared Statements]
    // We use INSERT IGNORE because of the UNIQUE(event_id, email) constraint in the DB
    $sql = "INSERT IGNORE INTO rsvps (event_id, attendee_name, attendee_email) VALUES (:eid, :name, :email)";
    
    // FIXED THE TYPO HERE:
    $stmt = $GLOBALS['pdo']->prepare($sql);
    
    $stmt->execute([':eid' => $event_id, ':name' => $name, ':email' => $email]);

    if($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'You are going! See you there.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'This email has already RSVPed for this event.']);
    }
} catch(PDOException $e) {
    // In a real app, we log $e->getMessage(), but we hide it from the user for security
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>