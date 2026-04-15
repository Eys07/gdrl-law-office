<?php
function processAddSchedule($conn, &$message, &$messageType) {
    $lawyer_id = intval($_POST['lawyer_id']);
    $schedule_date = $_POST['schedule_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $event_title = trim($_POST['event_title']);
    $event_type = $_POST['event_type'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $client_name = trim($_POST['client_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    if (checkScheduleConflict($conn, $lawyer_id, $schedule_date, $start_time, $end_time)) {
        $message = "Schedule conflict! This lawyer already has a schedule at this date/time.";
        $messageType = "danger";
        return false;
    }
    
    $sql = "INSERT INTO attorney_schedule (lawyer_id, schedule_date, start_time, end_time, event_title, event_type, description, location, client_name, phone, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssss", $lawyer_id, $schedule_date, $start_time, $end_time, $event_title, $event_type, $description, $location, $client_name, $phone, $email);
    
    if ($stmt->execute()) {
        $message = "Schedule added successfully!";
        $messageType = "success";
    } else {
        $message = "Error adding schedule: " . $conn->error;
        $messageType = "danger";
    }
    $stmt->close();
    return true;
}

function processEditSchedule($conn, &$message, &$messageType) {
    $schedule_id = intval($_POST['schedule_id']);
    $lawyer_id = intval($_POST['lawyer_id']);
    $schedule_date = $_POST['schedule_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $event_title = trim($_POST['event_title']);
    $event_type = $_POST['event_type'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $client_name = trim($_POST['client_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    if (checkScheduleConflict($conn, $lawyer_id, $schedule_date, $start_time, $end_time, $schedule_id)) {
        $message = "Schedule conflict! This lawyer already has a schedule at this date/time.";
        $messageType = "danger";
        return false;
    }
    
    $sql = "UPDATE attorney_schedule SET 
            lawyer_id = ?, schedule_date = ?, start_time = ?, end_time = ?, 
            event_title = ?, event_type = ?, description = ?, location = ?,
            client_name = ?, phone = ?, email = ?
            WHERE schedule_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssssi", $lawyer_id, $schedule_date, $start_time, $end_time, $event_title, $event_type, $description, $location, $client_name, $phone, $email, $schedule_id);
    
    if ($stmt->execute()) {
        $message = "Schedule updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error updating schedule: " . $conn->error;
        $messageType = "danger";
    }
    $stmt->close();
    return true;
}

function processDeleteSchedule($conn, &$message, &$messageType) {
    $schedule_id = intval($_GET['delete_schedule_id']);
    $stmt = $conn->prepare("DELETE FROM attorney_schedule WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    
    if ($stmt->execute()) {
        $message = "Schedule deleted successfully!";
        $messageType = "success";
    } else {
        $message = "Error deleting schedule: " . $conn->error;
        $messageType = "danger";
    }
    $stmt->close();
    return true;
}

function getSchedules($conn, $lawyer_id = 0, $date = null) {
    if ($date === null) {
        $date = date('Y-m-d');
    }
    
    $sql = "SELECT s.*, u.full_name as lawyer_name, u.user_id as lawyer_user_id
            FROM attorney_schedule s 
            LEFT JOIN users u ON s.lawyer_id = u.user_id 
            WHERE s.schedule_date = ?";
    
    if ($lawyer_id > 0) {
        $sql .= " AND s.lawyer_id = ?";
        $sql .= " ORDER BY s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $date, $lawyer_id);
    } else {
        $sql .= " ORDER BY u.full_name, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    
    return $schedules;
}

function getAllSchedulesForMonth($conn, $year, $month, $lawyer_id = 0) {
    $start_date = "$year-$month-01";
    $end_date = date("Y-m-t", strtotime($start_date));
    
    $sql = "SELECT s.*, u.full_name as lawyer_name, u.user_id as lawyer_user_id
            FROM attorney_schedule s 
            LEFT JOIN users u ON s.lawyer_id = u.user_id 
            WHERE s.schedule_date BETWEEN ? AND ?";
    
    if ($lawyer_id > 0) {
        $sql .= " AND s.lawyer_id = ?";
        $sql .= " ORDER BY s.schedule_date, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $start_date, $end_date, $lawyer_id);
    } else {
        $sql .= " ORDER BY s.schedule_date, u.full_name, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    
    return $schedules;
}

function getAllSchedules($conn, $lawyer_id = 0) {
    $sql = "SELECT s.*, u.full_name as lawyer_name, u.user_id as lawyer_user_id
            FROM attorney_schedule s 
            LEFT JOIN users u ON s.lawyer_id = u.user_id";
    
    if ($lawyer_id > 0) {
        $sql .= " WHERE s.lawyer_id = ?";
        $sql .= " ORDER BY s.schedule_date, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $lawyer_id);
    } else {
        $sql .= " ORDER BY s.schedule_date, u.full_name, s.start_time ASC";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    
    return $schedules;
}

function getScheduleById($conn, $schedule_id) {
    $stmt = $conn->prepare("SELECT s.*, u.full_name as lawyer_name 
                           FROM attorney_schedule s 
                           LEFT JOIN users u ON s.lawyer_id = u.user_id 
                           WHERE s.schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();
    return $schedule;
}

function getEventTypeBadge($type) {
    $badges = [
        'Court Hearing' => 'bg-danger',
        'Client Meeting' => 'bg-primary',
        'Filing Deadline' => 'bg-warning text-dark',
        'Deposition' => 'bg-info',
        'Mediation' => 'bg-success',
        'Research' => 'bg-secondary',
        'Consultation' => 'bg-dark',
        'Other' => 'bg-light text-dark'
    ];
    return $badges[$type] ?? 'bg-secondary';
}

function getEventTypeColor($type) {
    $colors = [
        'Court Hearing' => '#dc3545',
        'Client Meeting' => '#0d6efd',
        'Filing Deadline' => '#ffc107',
        'Deposition' => '#17a2b8',
        'Mediation' => '#28a745',
        'Research' => '#6c757d',
        'Consultation' => '#343a40',
        'Other' => '#d4af37'
    ];
    return $colors[$type] ?? '#d4af37';
}

function ensureScheduleTable($conn) {
    $result = $conn->query("SHOW TABLES LIKE 'attorney_schedule'");
    if ($result->num_rows == 0) {
        $sql = "CREATE TABLE attorney_schedule (
            schedule_id INT AUTO_INCREMENT PRIMARY KEY,
            lawyer_id INT NOT NULL,
            schedule_date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            event_title VARCHAR(255) NOT NULL,
            event_type VARCHAR(50) NOT NULL,
            description TEXT,
            location VARCHAR(255),
            client_name VARCHAR(255),
            phone VARCHAR(50),
            email VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lawyer_id) REFERENCES users(user_id) ON DELETE CASCADE,
            INDEX idx_schedule_date (schedule_date),
            INDEX idx_lawyer_id (lawyer_id)
        )";
        $conn->query($sql);
    } else {
        $result = $conn->query("SHOW COLUMNS FROM attorney_schedule LIKE 'client_name'");
        if ($result->num_rows == 0) {
            $conn->query("ALTER TABLE attorney_schedule ADD COLUMN client_name VARCHAR(255) AFTER location");
            $conn->query("ALTER TABLE attorney_schedule ADD COLUMN phone VARCHAR(50) AFTER client_name");
            $conn->query("ALTER TABLE attorney_schedule ADD COLUMN email VARCHAR(255) AFTER phone");
        }
    }
}

// AJAX handler for live calendar updates
function ajaxGetSchedules($conn) {
    header('Content-Type: application/json');
    
    $lawyer_id = isset($_GET['lawyer_id']) ? intval($_GET['lawyer_id']) : 0;
    $schedule_date = isset($_GET['schedule_date']) ? $_GET['schedule_date'] : date('Y-m-d');
    $last_check = isset($_GET['last_check']) ? intval($_GET['last_check']) : 0;
    $month_view = isset($_GET['month_view']) ? intval($_GET['month_view']) : 0;
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    
    // If month_view is true, get all schedules for the month
    if ($month_view) {
        $schedules = getAllSchedulesForMonth($conn, $year, $month, $lawyer_id);
    } else {
        $schedules = getSchedules($conn, $lawyer_id, $schedule_date);
    }
    
    // Count new events since last check (only for date-specific view)
    $new_count = 0;
    if (!$month_view && $last_check > 0) {
        foreach ($schedules as $schedule) {
            if (isset($schedule['created_at'])) {
                $created_time = strtotime($schedule['created_at']);
                if ($created_time > $last_check) {
                    $new_count++;
                }
            }
        }
    }
    
    // Also check for updated events
    $updated_count = 0;
    if (!$month_view && $last_check > 0) {
        foreach ($schedules as $schedule) {
            if (isset($schedule['updated_at'])) {
                $updated_time = strtotime($schedule['updated_at']);
                if ($updated_time > $last_check && $updated_time > strtotime($schedule['created_at'])) {
                    $updated_count++;
                }
            }
        }
    }
    
    $response = [
        'success' => true,
        'schedules' => $schedules,
        'new_events_count' => $new_count,
        'updated_events_count' => $updated_count,
        'timestamp' => time(),
        'total_count' => count($schedules)
    ];
    
    echo json_encode($response);
    exit();
}

// AJAX handler for getting schedule statistics
function ajaxGetScheduleStats($conn) {
    header('Content-Type: application/json');
    
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $lawyer_id = isset($_GET['lawyer_id']) ? intval($_GET['lawyer_id']) : 0;
    
    $schedules = getSchedules($conn, $lawyer_id, $date);
    
    $stats = [
        'total' => count($schedules),
        'by_type' => [],
        'upcoming' => 0,
        'urgent_count' => 0
    ];
    
    foreach ($schedules as $schedule) {
        $type = $schedule['event_type'];
        if (!isset($stats['by_type'][$type])) {
            $stats['by_type'][$type] = 0;
        }
        $stats['by_type'][$type]++;
        
        // Check if schedule is upcoming (current time before start time)
        $current_time = date('H:i:s');
        if ($schedule['start_time'] > $current_time) {
            $stats['upcoming']++;
        }
    }
    
    echo json_encode($stats);
    exit();
}

// Function to get schedules for a date range (for calendar view)
function getSchedulesForDateRange($conn, $start_date, $end_date, $lawyer_id = 0) {
    $sql = "SELECT s.*, u.full_name as lawyer_name, u.user_id as lawyer_user_id
            FROM attorney_schedule s 
            LEFT JOIN users u ON s.lawyer_id = u.user_id 
            WHERE s.schedule_date BETWEEN ? AND ?";
    
    if ($lawyer_id > 0) {
        $sql .= " AND s.lawyer_id = ?";
        $sql .= " ORDER BY s.schedule_date, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $start_date, $end_date, $lawyer_id);
    } else {
        $sql .= " ORDER BY s.schedule_date, u.full_name, s.start_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    
    return $schedules;
}

// Function to check for schedule conflicts
function checkScheduleConflict($conn, $lawyer_id, $schedule_date, $start_time, $end_time, $exclude_id = 0) {
    $sql = "SELECT COUNT(*) as conflict_count 
            FROM attorney_schedule 
            WHERE lawyer_id = ? 
            AND schedule_date = ? 
            AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?) OR (start_time >= ? AND end_time <= ?))";
    
    if ($exclude_id > 0) {
        $sql .= " AND schedule_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssssi", $lawyer_id, $schedule_date, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time, $exclude_id);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $lawyer_id, $schedule_date, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['conflict_count'] > 0;
}

// Handle AJAX requests
if (isset($_GET['ajax_get_schedules'])) {
    ajaxGetSchedules($conn);
}

if (isset($_GET['ajax_get_stats'])) {
    ajaxGetScheduleStats($conn);
}

if (isset($_GET['ajax_get_all_schedules'])) {
    header('Content-Type: application/json');
    
    if (!isset($conn)) {
        include_once '../config.php';
    }
    
    $lawyer_id = isset($_GET['lawyer_id']) ? intval($_GET['lawyer_id']) : 0;
    $schedules = getAllSchedules($conn, $lawyer_id);
    
    echo json_encode([
        'success' => true,
        'schedules' => $schedules
    ]);
    exit();
}
?>