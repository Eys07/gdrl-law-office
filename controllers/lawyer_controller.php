<?php
function getLawyers($conn) {
    $result = $conn->query("SELECT user_id, full_name, color_code FROM users WHERE role = 'attorney' ORDER BY full_name");
    $lawyers = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $lawyers[] = $row;
        }
    }
    return $lawyers;
}

function getLawyerDashboardStats($conn, $lawyer_id) {
    $stats = [];
    
    $stats['active_cases'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE assigned_lawyer_id = $lawyer_id AND (status != 'Archive' OR status IS NULL OR status = '')")->fetch_assoc()['count'] ?? 0;
    $stats['archived_cases'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE assigned_lawyer_id = $lawyer_id AND status = 'Archive'")->fetch_assoc()['count'] ?? 0;
    $stats['total_cases'] = $stats['active_cases'] + $stats['archived_cases'];
    
    $stats['cases_this_month'] = $conn->query("SELECT COUNT(*) as count FROM case_inventory WHERE assigned_lawyer_id = $lawyer_id AND (status != 'Archive' OR status IS NULL OR status = '') AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")->fetch_assoc()['count'] ?? 0;
    
    return $stats;
}

function getLawyerCases($conn, $lawyer_id) {
    $stmt = $conn->prepare("
        SELECT * FROM case_inventory 
        WHERE assigned_lawyer_id = ? AND (status != 'Archive' OR status IS NULL OR status = '')
        ORDER BY 
            CASE priority 
                WHEN 'Urgent' THEN 1 
                WHEN 'High' THEN 2 
                WHEN 'Medium' THEN 3 
                WHEN 'Low' THEN 4 
            END,
            created_at DESC
    ");
    $stmt->bind_param("i", $lawyer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cases = [];
    while ($row = $result->fetch_assoc()) {
        $cases[] = $row;
    }
    $stmt->close();
    return $cases;
}

function getLawyerSchedules($conn, $lawyer_id, $date = null) {
    if ($date === null) {
        $date = date('Y-m-d');
    }
    
    $stmt = $conn->prepare("
        SELECT * FROM attorney_schedule 
        WHERE lawyer_id = ? AND schedule_date = ?
        ORDER BY start_time ASC
    ");
    $stmt->bind_param("is", $lawyer_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    return $schedules;
}

function getLawyerUpcomingSchedules($conn, $lawyer_id, $days = 14) {
    $stmt = $conn->prepare("
        SELECT * FROM attorney_schedule 
        WHERE lawyer_id = ? AND schedule_date >= CURDATE() AND schedule_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
        ORDER BY schedule_date ASC, start_time ASC
    ");
    $stmt->bind_param("ii", $lawyer_id, $days);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    return $schedules;
}

function getLawyerMonthlyTrends($conn, $lawyer_id, $months = 6) {
    $stmt = $conn->prepare("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as count
        FROM case_inventory 
        WHERE assigned_lawyer_id = ? 
        AND (status != 'Archive' OR status IS NULL OR status = '')
        AND created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->bind_param("ii", $lawyer_id, $months);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['month']] = $row['count'];
    }
    $stmt->close();
    return $data;
}

function processLawyerUpdateStatus($conn, $case_id, $status) {
    $stmt = $conn->prepare("UPDATE case_inventory SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $case_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>