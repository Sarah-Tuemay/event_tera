<?php
require_once __DIR__ . '/../config/database.php';

class Event {
    private $db;

    public function __construct() {
        $this->db = $GLOBALS['pdo']; 
    }

    public function create($title, $desc, $category, $date, $location, $imagePath, $organizerId) {
        $sql = "INSERT INTO events (title, description, category, event_date, location, image_path, organizer_id) 
                VALUES (:title, :desc, :cat, :date, :loc, :img, :org_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title, ':desc' => $desc, ':cat' => $category,
            ':date' => $date, ':loc' => $location, ':img' => $imagePath, ':org_id' => $organizerId
        ]);
    }

    public function getAllEvents() {
        $sql = "SELECT e.*, u.fullname as organizer_name FROM events e JOIN users u ON e.organizer_id = u.id ORDER BY e.event_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventsJSON() {
        return json_encode($this->getAllEvents());
    }

    public function getEventsByOrganizer($organizerId) {
        $sql = "SELECT * FROM events WHERE organizer_id = :org_id ORDER BY event_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':org_id' => $organizerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEvent($eventId, $organizerId) {
        $sql = "SELECT image_path FROM events WHERE id = :id AND organizer_id = :org_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $eventId, ':org_id' => $organizerId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            if (!empty($event['image_path'])) {
                $filePath = '../' . $event['image_path'];
                if (file_exists($filePath)) {
                    unlink($filePath); 
                }
            }
            $delSql = "DELETE FROM events WHERE id = :id AND organizer_id = :org_id";
            $delStmt = $this->db->prepare($delSql);
            return $delStmt->execute([':id' => $eventId, ':org_id' => $organizerId]);
        }
        return false;
    }
}
?>