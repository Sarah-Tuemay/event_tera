<?php
// [WEEK 9: Object-Oriented PHP (Classes, Methods)]
// [WEEK 5: CRUD Operations with MySQLi & PDO]

// Bulletproof path finding using __DIR__
require_once __DIR__ . '/../config/database.php';

class Event {
    private $db;

    public function __construct() {
        // Get the PDO instance from the global scope
        $this->db = $GLOBALS['pdo']; 
    }

    // --------------------------------------------------------
    // CREATE: Add new event
    // --------------------------------------------------------
    public function create($title, $desc, $category, $date, $location, $imagePath, $organizerId) {
        // [WEEK 11: SQL Injection Prevention using Prepared Statements]
        $sql = "INSERT INTO events (title, description, category, event_date, location, image_path, organizer_id) 
                VALUES (:title, :desc, :cat, :date, :loc, :img, :org_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':desc' => $desc,
            ':cat' => $category,
            ':date' => $date,
            ':loc' => $location,
            ':img' => $imagePath,
            ':org_id' => $organizerId
        ]);
    }

    // --------------------------------------------------------
    // READ: Get all events for public page (with JOIN)
    // --------------------------------------------------------
    public function getAllEvents() {
        // Join with users table to get the organizer's name instead of just their ID
        $sql = "SELECT e.*, u.fullname as organizer_name 
                FROM events e 
                JOIN users u ON e.organizer_id = u.id 
                ORDER BY e.event_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------
    // READ: Get events as JSON for the Frontend API
    // --------------------------------------------------------
    public function getEventsJSON() {
        return json_encode($this->getAllEvents());
    }

    // --------------------------------------------------------
    // READ: Get events ONLY by a specific organizer (for Dashboard)
    // --------------------------------------------------------
    public function getEventsByOrganizer($organizerId) {
        $sql = "SELECT * FROM events WHERE organizer_id = :org_id ORDER BY event_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':org_id' => $organizerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------
    // DELETE: Remove event and its physical image file
    // --------------------------------------------------------
    public function deleteEvent($eventId, $organizerId) {
        // [SECURITY CHECK]: Ensure the logged-in user actually owns this event 
        // to prevent users from deleting other people's events via URL manipulation!
        $sql = "SELECT image_path FROM events WHERE id = :id AND organizer_id = :org_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $eventId, ':org_id' => $organizerId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the event exists and belongs to this organizer
        if ($event) {
            // [WEEK 8: File Handling - Delete physical image from server]
            if (!empty($event['image_path'])) {
                $filePath = '../' . $event['image_path']; // Go up one directory to reach root
                if (file_exists($filePath)) {
                    unlink($filePath); // Deletes the actual file from the hard drive
                }
            }

            // [WEEK 5: CRUD - Delete the database row]
            $delSql = "DELETE FROM events WHERE id = :id AND organizer_id = :org_id";
            $delStmt = $this->db->prepare($delSql);
            return $delStmt->execute([':id' => $eventId, ':org_id' => $organizerId]);
        }
        
        return false; // Event not found or doesn't belong to this user
    }
}
?>