<?php
// This file handles case-related operations.

function checkCaseNumberExists($conn, $case_no, $exclude_id = 0) {
    $case_no = trim($case_no);
    if (empty($case_no)) return false;
    
    $stmt = $conn->prepare("SELECT id FROM case_inventory WHERE case_no = ? AND id != ?");
    $stmt->bind_param("si", $case_no, $exclude_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

function getAllCases($conn) {
    $query = "SELECT c.*, c.id AS case_id, u.full_name AS lawyer_name FROM case_inventory c LEFT JOIN users u ON c.assigned_lawyer_id = u.user_id WHERE (c.status != 'Archived' OR c.status IS NULL OR c.status = '') ORDER BY c.created_at DESC";

    $result = $conn->query($query);
    $cases = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cases[] = $row;
        }
    }
    return $cases;
}

function getArchivedCases($conn) {
    $query = "SELECT c.*, c.id AS case_id, u.full_name AS lawyer_name, 
              au.full_name AS archived_by_name 
              FROM case_inventory c 
              LEFT JOIN users u ON c.assigned_lawyer_id = u.user_id 
              LEFT JOIN users au ON c.archived_by = au.user_id 
              WHERE c.status = 'Archived' 
              ORDER BY c.archived_at DESC";

    $result = $conn->query($query);
    $cases = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cases[] = $row;
        }
    }
    return $cases;
}

function getCaseById($conn, $case_id) {
    $stmt = $conn->prepare("SELECT * FROM case_inventory WHERE id = ?");
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function createCase($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO case_inventory (
        client_name, contact_no, primary_email, messenger, secondary_email,
        alt_contact_name, alt_contact_relationship, alt_contact_no, alt_primary_email, alt_messenger, alt_secondary_email,
        case_title, case_no, court, cause_of_action, stage_incident, status, priority,
        assigned_lawyer_id, notes, created_at, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    $stmt->bind_param("ssssssssssssssssssisi",
        $data['client_name'],
        $data['contact_no'],
        $data['primary_email'],
        $data['messenger'],
        $data['secondary_email'],
        $data['alt_contact_name'],
        $data['alt_contact_relationship'],
        $data['alt_contact_no'],
        $data['alt_primary_email'],
        $data['alt_messenger'],
        $data['alt_secondary_email'],
        $data['case_title'],
        $data['case_no'],
        $data['court'],
        $data['cause_of_action'],
        $data['stage_incident'],
        $data['status'],
        $data['priority'],
        $data['assigned_lawyer_id'],
        $data['notes'],
        $data['created_by']
    );
    $res = $stmt->execute();
    $insert_id = $stmt->insert_id;
    $stmt->close();
    return $res ? $insert_id : false;
}

function updateCase($conn, $case_id, $data) {
    $stmt = $conn->prepare("UPDATE case_inventory SET
        client_name = ?, contact_no = ?, primary_email = ?, messenger = ?, secondary_email = ?,
        alt_contact_name = ?, alt_contact_relationship = ?, alt_contact_no = ?, alt_primary_email = ?, alt_messenger = ?, alt_secondary_email = ?,
        case_title = ?, case_no = ?, court = ?, cause_of_action = ?, stage_incident = ?, status = ?, priority = ?,
        assigned_lawyer_id = ?, notes = ?, updated_at = NOW()
        WHERE id = ?");

    $stmt->bind_param("ssssssssssssssssssisi",
        $data['client_name'],
        $data['contact_no'],
        $data['primary_email'],
        $data['messenger'],
        $data['secondary_email'],
        $data['alt_contact_name'],
        $data['alt_contact_relationship'],
        $data['alt_contact_no'],
        $data['alt_primary_email'],
        $data['alt_messenger'],
        $data['alt_secondary_email'],
        $data['case_title'],
        $data['case_no'],
        $data['court'],
        $data['cause_of_action'],
        $data['stage_incident'],
        $data['status'],
        $data['priority'],
        $data['assigned_lawyer_id'],
        $data['notes'],
        $case_id
    );
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

function deleteCase($conn, $case_id) {
    $stmt = $conn->prepare("DELETE FROM case_inventory WHERE id = ?");
    $stmt->bind_param("i", $case_id);
    return $stmt->execute();
}

// Process Add Case from POST
function processAddCase($conn, &$message, &$messageType) {
    require_once 'functions.php';
    $data = [];
    $data['client_name'] = sanitize($_POST['client_name'] ?? '');
    $data['contact_no'] = sanitize($_POST['contact_no'] ?? $_POST['client_phone'] ?? '');
    $data['primary_email'] = sanitize($_POST['primary_email'] ?? $_POST['client_email'] ?? '');
    $data['messenger'] = sanitize($_POST['messenger'] ?? '');
    $data['secondary_email'] = sanitize($_POST['secondary_email'] ?? '');
    $data['alt_contact_no'] = sanitize($_POST['alt_contact_no'] ?? '');
    $data['alt_contact_name'] = sanitize($_POST['alt_contact_name'] ?? '');
    $data['alt_contact_relationship'] = sanitize($_POST['alt_contact_relationship'] ?? '');
    $data['alt_primary_email'] = sanitize($_POST['alt_primary_email'] ?? '');
    $data['alt_messenger'] = sanitize($_POST['alt_messenger'] ?? '');
    $data['alt_secondary_email'] = sanitize($_POST['alt_secondary_email'] ?? '');
    $data['case_title'] = sanitize($_POST['case_title'] ?? '');
    $data['case_no'] = sanitize($_POST['case_no'] ?? '');
    $data['court'] = sanitize($_POST['court'] ?? '');
    $data['cause_of_action'] = sanitize($_POST['cause_of_action'] ?? '');
    $data['stage_incident'] = sanitize($_POST['stage_incident'] ?? '');
    $data['status'] = sanitize($_POST['status'] ?? 'Active');
    $data['priority'] = sanitize($_POST['priority'] ?? 'Medium');
    $data['assigned_lawyer_id'] = intval($_POST['lawyer_id'] ?? 0);
    $data['notes'] = sanitize($_POST['notes'] ?? '');
    $data['created_by'] = $_SESSION['user_id'] ?? null;

    // Check if case number already exists
    $case_no = trim($_POST['case_no'] ?? '');
    if (!empty($case_no) && checkCaseNumberExists($conn, $case_no)) {
        $message = 'Case number already exists!';
        $messageType = 'danger';
        return false;
    }

    $insert_id = createCase($conn, $data);
    if ($insert_id) {
        $message = 'Case added successfully.';
        $messageType = 'success';
        return $insert_id;
    } else {
        $message = 'Failed to add case.';
        $messageType = 'danger';
        return false;
    }
}

// Process Add Archived Case from POST
function processAddArchivedCase($conn, &$message, &$messageType) {
    require_once 'functions.php';
    $data = [];
    $data['client_name'] = sanitize($_POST['client_name'] ?? '');
    $data['contact_no'] = sanitize($_POST['contact_no'] ?? '');
    $data['primary_email'] = sanitize($_POST['primary_email'] ?? '');
    $data['messenger'] = sanitize($_POST['messenger'] ?? '');
    $data['secondary_email'] = sanitize($_POST['secondary_email'] ?? '');
    $data['alt_contact_name'] = sanitize($_POST['alt_contact_name'] ?? '');
    $data['alt_contact_relationship'] = sanitize($_POST['alt_contact_relationship'] ?? '');
    $data['alt_contact_no'] = sanitize($_POST['alt_contact_no'] ?? '');
    $data['alt_primary_email'] = sanitize($_POST['alt_primary_email'] ?? '');
    $data['alt_messenger'] = sanitize($_POST['alt_messenger'] ?? '');
    $data['alt_secondary_email'] = sanitize($_POST['alt_secondary_email'] ?? '');
    $data['case_title'] = sanitize($_POST['case_title'] ?? '');
    $data['case_no'] = sanitize($_POST['case_no'] ?? '');
    $data['court'] = sanitize($_POST['court'] ?? '');
    $data['cause_of_action'] = sanitize($_POST['cause_of_action'] ?? '');
    $data['stage_incident'] = sanitize($_POST['stage_incident'] ?? '');
    $data['status'] = 'Archived';
    $data['priority'] = sanitize($_POST['priority'] ?? 'Medium');
    $data['assigned_lawyer_id'] = intval($_POST['lawyer_id'] ?? 0);
    $data['notes'] = sanitize($_POST['notes'] ?? '');
    $data['created_by'] = $_SESSION['user_id'] ?? null;

    // Check if case number already exists
    $case_no = trim($_POST['case_no'] ?? '');
    if (!empty($case_no) && checkCaseNumberExists($conn, $case_no)) {
        $message = 'Case number already exists!';
        $messageType = 'danger';
        return false;
    }

    $insert_id = createCase($conn, $data);
    if ($insert_id) {
        // Also set archived_at and archived_by for cases added directly to archive
        $archived_by = $_SESSION['user_id'] ?? null;
        $update_stmt = $conn->prepare("UPDATE case_inventory SET archived_at = NOW(), archived_by = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $archived_by, $insert_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        $message = 'Archived case added successfully.';
        $messageType = 'success';
        return $insert_id;
    } else {
        $message = 'Failed to add archived case.';
        $messageType = 'danger';
        return false;
    }
}

// Process Edit Case from POST
function processEditCase($conn, &$message, &$messageType) {
    require_once 'functions.php';
    $case_id = intval($_POST['case_id'] ?? 0);
    if (!$case_id) {
        $message = 'Invalid case.';
        $messageType = 'danger';
        return false;
    }
    $data = [];
    $data['client_name'] = sanitize($_POST['client_name'] ?? '');
    $data['contact_no'] = sanitize($_POST['contact_no'] ?? $_POST['client_phone'] ?? '');
    $data['primary_email'] = sanitize($_POST['primary_email'] ?? $_POST['client_email'] ?? '');
    $data['messenger'] = sanitize($_POST['messenger'] ?? '');
    $data['secondary_email'] = sanitize($_POST['secondary_email'] ?? '');
    $data['alt_contact_no'] = sanitize($_POST['alt_contact_no'] ?? '');
    $data['alt_contact_name'] = sanitize($_POST['alt_contact_name'] ?? '');
    $data['alt_contact_relationship'] = sanitize($_POST['alt_contact_relationship'] ?? '');
    $data['alt_primary_email'] = sanitize($_POST['alt_primary_email'] ?? '');
    $data['alt_messenger'] = sanitize($_POST['alt_messenger'] ?? '');
    $data['alt_secondary_email'] = sanitize($_POST['alt_secondary_email'] ?? '');
    $data['case_title'] = sanitize($_POST['case_title'] ?? '');
    $data['case_no'] = sanitize($_POST['case_no'] ?? '');
    $data['court'] = sanitize($_POST['court'] ?? '');
    $data['cause_of_action'] = sanitize($_POST['cause_of_action'] ?? '');
    $data['stage_incident'] = sanitize($_POST['stage_incident'] ?? '');
    $data['status'] = sanitize($_POST['status'] ?? 'Active');
    $data['priority'] = sanitize($_POST['priority'] ?? 'Medium');
    $data['assigned_lawyer_id'] = intval($_POST['lawyer_id'] ?? 0);
    $data['notes'] = sanitize($_POST['notes'] ?? '');

    // Check if case number already exists (exclude current case)
    $case_no = trim($_POST['case_no'] ?? '');
    if (!empty($case_no) && checkCaseNumberExists($conn, $case_no, $case_id)) {
        $message = 'Case number already exists!';
        $messageType = 'danger';
        return false;
    }

    $res = updateCase($conn, $case_id, $data);
    if ($res) {
        $message = 'Case updated successfully.';
        $messageType = 'success';
        return true;
    } else {
        $message = 'Failed to update case.';
        $messageType = 'danger';
        return false;
    }
}

// Process Edit Archived Case from POST
function processEditArchivedCase($conn, &$message, &$messageType) {
    require_once 'functions.php';
    $case_id = intval($_POST['case_id'] ?? 0);
    if (!$case_id) {
        $message = 'Invalid case.';
        $messageType = 'danger';
        return false;
    }
    $data = [];
    $data['client_name'] = sanitize($_POST['client_name'] ?? '');
    $data['contact_no'] = sanitize($_POST['contact_no'] ?? '');
    $data['primary_email'] = sanitize($_POST['primary_email'] ?? '');
    $data['messenger'] = sanitize($_POST['messenger'] ?? '');
    $data['secondary_email'] = sanitize($_POST['secondary_email'] ?? '');
    $data['alt_contact_name'] = sanitize($_POST['alt_contact_name'] ?? '');
    $data['alt_contact_relationship'] = sanitize($_POST['alt_contact_relationship'] ?? '');
    $data['alt_contact_no'] = sanitize($_POST['alt_contact_no'] ?? '');
    $data['alt_primary_email'] = sanitize($_POST['alt_primary_email'] ?? '');
    $data['alt_messenger'] = sanitize($_POST['alt_messenger'] ?? '');
    $data['alt_secondary_email'] = sanitize($_POST['alt_secondary_email'] ?? '');
    $data['case_title'] = sanitize($_POST['case_title'] ?? '');
    $data['case_no'] = sanitize($_POST['case_no'] ?? '');
    $data['court'] = sanitize($_POST['court'] ?? '');
    $data['cause_of_action'] = sanitize($_POST['cause_of_action'] ?? '');
    $data['stage_incident'] = sanitize($_POST['stage_incident'] ?? '');
    $data['status'] = sanitize($_POST['status'] ?? 'Archived');
    $data['priority'] = sanitize($_POST['priority'] ?? 'Medium');
    $data['assigned_lawyer_id'] = intval($_POST['lawyer_id'] ?? 0);
    $data['notes'] = sanitize($_POST['notes'] ?? '');

    // Check if case number already exists (exclude current case)
    $case_no = trim($_POST['case_no'] ?? '');
    if (!empty($case_no) && checkCaseNumberExists($conn, $case_no, $case_id)) {
        $message = 'Case number already exists!';
        $messageType = 'danger';
        return false;
    }

    $res = updateCase($conn, $case_id, $data);
    if ($res) {
        $message = 'Archived case updated successfully.';
        $messageType = 'success';
        return true;
    } else {
        $message = 'Failed to update archived case.';
        $messageType = 'danger';
        return false;
    }
}

// Process Delete Case
function processDeleteCase($conn, &$message, &$messageType) {
    $case_id = intval($_GET['delete_case'] ?? 0);
    if (!$case_id) return false;
    $res = deleteCase($conn, $case_id);
    if ($res) {
        $message = 'Case deleted.';
        $messageType = 'success';
    } else {
        $message = 'Failed to delete case.';
        $messageType = 'danger';
    }
    return $res;
}

// Process Update Status (AJAX)
function processUpdateStatus($conn) {
    $case_id = intval($_POST['case_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if (!$case_id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    $stmt = $conn->prepare("UPDATE case_inventory SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $case_id);
    $res = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $res]);
    exit;
}

// Archive a case (move to archived status)
function archiveCase($conn, $case_id, $archived_reason = '') {
    $archived_by = $_SESSION['user_id'] ?? null;
    $stmt = $conn->prepare("UPDATE case_inventory SET status = 'Archived', archived_at = NOW(), archived_by = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ii", $archived_by, $case_id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// Process archive case
function processArchiveCase($conn, &$message, &$messageType) {
    $case_id = intval($_GET['archive_case'] ?? 0);
    if (!$case_id) return false;
    
    $res = archiveCase($conn, $case_id);
    if ($res) {
        $message = 'Case moved to Archived cases.';
        $messageType = 'success';
    } else {
        $message = 'Failed to archive case.';
        $messageType = 'danger';
    }
    return $res;
}

// Restore archived case to active
function restoreArchivedCase($conn, $case_id) {
    $stmt = $conn->prepare("UPDATE case_inventory SET status = 'Active', archived_at = NULL, archived_by = NULL, updated_at = NOW() WHERE id = ? AND status = 'Archived'");
    $stmt->bind_param("i", $case_id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// Process restore archived case
function processRestoreArchivedCase($conn, &$message, &$messageType) {
    $case_id = intval($_GET['restore_case'] ?? 0);
    if (!$case_id) return false;
    
    $res = restoreArchivedCase($conn, $case_id);
    if ($res) {
        $message = 'Case moved to Active cases.';
        $messageType = 'success';
    } else {
        $message = 'Failed to restore case.';
        $messageType = 'danger';
    }
    return $res;
}

// Permanently delete archived case
function permanentDeleteArchivedCase($conn, $case_id) {
    $stmt = $conn->prepare("DELETE FROM case_inventory WHERE id = ? AND status = 'Archived'");
    $stmt->bind_param("i", $case_id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// Process permanent delete archived case
function processPermanentDeleteArchivedCase($conn, &$message, &$messageType) {
    $case_id = intval($_GET['delete_archived'] ?? 0);
    if (!$case_id) return false;
    
    $res = permanentDeleteArchivedCase($conn, $case_id);
    if ($res) {
        $message = 'Case permanently deleted.';
        $messageType = 'success';
    } else {
        $message = 'Failed to delete case.';
        $messageType = 'danger';
    }
    return $res;
}
?>