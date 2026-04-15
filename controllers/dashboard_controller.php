<?php
function getDashboardStats($conn) {
    $stats = [];
    
    $stats['active_cases'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE status != 'Archived' OR status IS NULL OR status = ''")->fetch_assoc()['count'] ?? 0;
    $stats['archived_cases'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE status = 'Archived'")->fetch_assoc()['count'] ?? 0;
    $stats['total_cases'] = $stats['active_cases'] + $stats['archived_cases'];
    
    $stats['total_users'] = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'] ?? 0;
    $stats['total_lawyers'] = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'attorney'")->fetch_assoc()['count'] ?? 0;
    $stats['total_secretaries'] = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'secretary'")->fetch_assoc()['count'] ?? 0;
    
    $stats['cases_this_month'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")->fetch_assoc()['count'] ?? 0;
    $stats['cases_today'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE (status != 'Archived' OR status IS NULL OR status = '') AND DATE(created_at) = CURDATE()")->fetch_assoc()['count'] ?? 0;
    
    return $stats;
}

function getCasesByStatus($conn) {
    $result = $conn->query("SELECT status, COUNT(*) as count FROM case_inventory WHERE status != 'Archived' OR status IS NULL OR status = '' GROUP BY status");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['status']] = $row['count'];
    }
    return $data;
}

function getCasesByPriority($conn) {
    $result = $conn->query("SELECT priority, COUNT(*) as count FROM case_inventory WHERE status != 'Archived' OR status IS NULL OR status = '' GROUP BY priority");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['priority']] = $row['count'];
    }
    return $data;
}

function getMonthlyCaseTrends($conn, $months = 6) {
    $result = $conn->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as count
        FROM case_inventory 
        WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(NOW(), INTERVAL $months MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['month']] = $row['count'];
    }
    return $data;
}

function getLawyerPerformance($conn) {
    $result = $conn->query("
        SELECT 
            u.user_id,
            u.full_name,
            COALESCE(active.cnt, 0) as active_cases,
            COALESCE(archived.cnt, 0) as archived_cases
        FROM users u
            LEFT JOIN (
                SELECT assigned_lawyer_id, COUNT(*) as cnt 
                FROM case_inventory 
                WHERE status != 'Archived' OR status IS NULL OR status = ''
                GROUP BY assigned_lawyer_id
            ) active ON u.user_id = active.assigned_lawyer_id
            LEFT JOIN (
                SELECT assigned_lawyer_id, COUNT(*) as cnt 
                FROM case_inventory 
                WHERE status = 'Archived' 
                GROUP BY assigned_lawyer_id
            ) archived ON u.user_id = archived.assigned_lawyer_id
        WHERE u.role = 'attorney'
        ORDER BY u.full_name ASC
    ");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getRecentActivity($conn, $limit = 10) {
    $result = $conn->query("
        SELECT 
            c.id AS case_id,
            c.client_name,
            c.status,
            c.priority,
            c.created_at,
            u.full_name as lawyer_name
        FROM case_inventory c
        LEFT JOIN users u ON c.assigned_lawyer_id = u.user_id
        WHERE c.status != 'Archived' OR c.status IS NULL OR c.status = ''
        ORDER BY c.created_at DESC
        LIMIT $limit
    ");
    
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    return $activities;
}

function getAlerts($conn) {
    $alerts = [];
    
    $high_load = $conn->query("
        SELECT u.full_name, COUNT(*) as case_count
        FROM case_inventory c
        JOIN users u ON c.assigned_lawyer_id = u.user_id
        WHERE c.status != 'Archived' OR c.status IS NULL OR c.status = ''
        GROUP BY u.user_id, u.full_name
        HAVING case_count > 10
    ");
    while ($row = $high_load->fetch_assoc()) {
        $alerts[] = [
            'type' => 'warning',
            'icon' => 'bi-exclamation-triangle',
            'message' => "{$row['full_name']} has {$row['case_count']} active cases (high workload)"
        ];
    }
    
    $urgent = $conn->query("SELECT COUNT(*) as cnt FROM case_inventory WHERE priority IN ('High', 'Urgent') AND (status != 'Archived' OR status IS NULL OR status = '')");
    $urgent_count = $urgent->fetch_assoc()['cnt'] ?? 0;
    if ($urgent_count > 0) {
        $alerts[] = [
            'type' => 'danger',
            'icon' => 'bi-exclamation-octagon',
            'message' => "$urgent_count urgent case(s) need immediate attention"
        ];
    }
    
    return $alerts;
}

function getHighPriorityCases($conn, $limit = 10) {
    $result = $conn->query("
        SELECT c.*, u.full_name as lawyer_name 
        FROM case_inventory c 
        LEFT JOIN users u ON c.assigned_lawyer_id = u.user_id 
        WHERE c.priority IN ('High', 'Urgent') 
        AND (c.status != 'Archived' OR c.status IS NULL OR c.status = '')
        ORDER BY FIELD(c.priority, 'Urgent', 'High'), c.created_at DESC 
        LIMIT $limit
    ");
    return $result;
}

function getWeeklyTrends($conn) {
    $result = $conn->query("
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count
        FROM case_inventory 
        WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getYearlyTrends($conn) {
    $result = $conn->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y') as year,
            DATE_FORMAT(created_at, '%m') as month,
            COUNT(*) as count
        FROM case_inventory 
        WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY year ASC, month ASC
    ");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getLawyerPerformanceDetailed($conn) {
    // Use the getLawyers function from lawyer_controller.php
    // Make sure lawyer_controller.php is included before this function is called
    $lawyers = getLawyers($conn);
    $performance = [];
    
    foreach ($lawyers as $lawyer) {
        $lawyer_id = $lawyer['user_id'];
        
        $weekly = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
        ")->fetch_assoc()['count'] ?? 0;
        
        $monthly = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        ")->fetch_assoc()['count'] ?? 0;
        
        $yearly = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
        ")->fetch_assoc()['count'] ?? 0;
        
        $active_cases = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND (status != 'Archived' OR status IS NULL OR status = '')
        ")->fetch_assoc()['count'] ?? 0;
        
        $archived_cases = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND status = 'Archived'
        ")->fetch_assoc()['count'] ?? 0;
        
        $high_priority = $conn->query("
            SELECT COUNT(*) as count FROM case_inventory 
            WHERE assigned_lawyer_id = $lawyer_id 
            AND priority IN ('High', 'Urgent')
            AND (status != 'Archived' OR status IS NULL OR status = '')
        ")->fetch_assoc()['count'] ?? 0;
        
        $performance[] = [
            'lawyer_id' => $lawyer_id,
            'full_name' => $lawyer['full_name'],
            'weekly' => $weekly,
            'monthly' => $monthly,
            'yearly' => $yearly,
            'active_cases' => $active_cases,
            'archived_cases' => $archived_cases,
            'high_priority' => $high_priority
        ];
    }
    
    return $performance;
}

function getOverallStats($conn) {
    return [
        'cases_this_week' => $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)")->fetch_assoc()['count'] ?? 0,
        'cases_this_month' => $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_assoc()['count'] ?? 0,
        'cases_this_year' => $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE (status != 'Archived' OR status IS NULL OR status = '') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)")->fetch_assoc()['count'] ?? 0,
        'archived_this_week' => $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE status = 'Archived' AND archived_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)")->fetch_assoc()['count'] ?? 0,
        'archived_this_month' => $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE status = 'Archived' AND archived_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_assoc()['count'] ?? 0,
        'schedules_this_week' => $conn->query("SELECT COUNT(*) as count FROM attorney_schedule WHERE schedule_date >= CURDATE() AND schedule_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['count'] ?? 0,
        'schedules_this_month' => $conn->query("SELECT COUNT(*) as count FROM attorney_schedule WHERE schedule_date >= CURDATE() AND schedule_date <= LAST_DAY(CURDATE())")->fetch_assoc()['count'] ?? 0,
    ];
}

function getUpcomingSchedules($conn, $days = 14) {
    $stmt = $conn->prepare("
        SELECT s.*, u.full_name as lawyer_name 
        FROM attorney_schedule s
        LEFT JOIN users u ON s.lawyer_id = u.user_id
        WHERE s.schedule_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY) 
        ORDER BY s.schedule_date ASC, s.start_time ASC
    ");
    $stmt->bind_param("i", $days);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    return $schedules;
}

function getCases($conn, $lawyer_id = 0) {
    $where = "WHERE c.status != 'Archived'";
    if ($lawyer_id > 0) {
        $where .= " AND c.assigned_lawyer_id = " . intval($lawyer_id);
    }
    $query = "SELECT c.*, c.id AS case_id, u.full_name AS lawyer_name FROM case_inventory c LEFT JOIN users u ON c.assigned_lawyer_id = u.user_id $where ORDER BY c.created_at DESC";

    $result = $conn->query($query);
    $cases = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cases[] = $row;
        }
    }
    return $cases;
}

function getLawyerCaseCounts($conn) {
    $result = $conn->query("SELECT assigned_lawyer_id, COUNT(*) as case_count FROM case_inventory WHERE status != 'Archived' GROUP BY assigned_lawyer_id");
    $lawyerCaseCounts = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $lawyerCaseCounts[$row['assigned_lawyer_id']] = $row['case_count'];
        }
    }
    return $lawyerCaseCounts;
}

function getArchivedCaseCounts($conn) {
    $result = $conn->query("SELECT assigned_lawyer_id, COUNT(*) as case_count FROM case_inventory WHERE status = 'Archived' GROUP BY assigned_lawyer_id");
    $lawyerCaseCounts = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $lawyerCaseCounts[$row['assigned_lawyer_id']] = $row['case_count'];
        }
    }
    return $lawyerCaseCounts;
}

function ensureArchivedAtColumn($conn) {
    $result = $conn->query("SHOW COLUMNS FROM case_inventory LIKE 'archived_at'");
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE case_inventory ADD COLUMN archived_at TIMESTAMP NULL DEFAULT NULL");
    }
}

function ensureArchivedByColumn($conn) {
    $result = $conn->query("SHOW COLUMNS FROM case_inventory LIKE 'archived_by'");
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE case_inventory ADD COLUMN archived_by INT NULL DEFAULT NULL");
    }
}

// REMOVED: getArchivedCases() function - already exists in case_controller.php
// REMOVED: getLawyers() function - already exists in lawyer_controller.php

function getSelectedLawyerName($conn, $lawyer_id) {
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ? AND role = 'attorney'");
    $stmt->bind_param("i", $lawyer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lawyer = $result->fetch_assoc();
    $stmt->close();
    return $lawyer['full_name'] ?? null;
}

// DO NOT redeclare getLawyers() here - it's already in lawyer_controller.php
?>