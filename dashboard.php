<?php
session_start();
include 'config.php';
include 'auth.php';
include 'functions.php';
include 'controllers/case_controller.php';
include 'controllers/user_controller.php';
include 'controllers/schedule_controller.php';
include 'controllers/dashboard_controller.php';
include 'controllers/lawyer_controller.php';

requireAuth();

$isSuperAdmin = $_SESSION['role'] === 'super_admin';
$current_user_id = $_SESSION['user_id'];

$message = '';
$messageType = '';
$selected_lawyer_id = isset($_GET['lawyer_filter']) ? intval($_GET['lawyer_filter']) : 0;
$selected_date = isset($_GET['schedule_date']) ? $_GET['schedule_date'] : date('Y-m-d');
$current_section = isset($_GET['section']) ? $_GET['section'] : '';

ensureScheduleTable($conn);
ensureArchivedAtColumn($conn);
ensureArchivedByColumn($conn);

if (isset($_POST['update_status'])) {
    processUpdateStatus($conn);
}

if (isset($_POST['add_case']) && $isSuperAdmin) {
    $redirect_id = processAddCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=cases&lawyer_filter=$redirect_id");
        exit();
    }
}

if (isset($_POST['add_archived']) && $isSuperAdmin) {
    $redirect_id = processAddArchivedCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=archived");
        exit();
    }
}

if (isset($_POST['edit_case'])) {
    processEditCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=cases&lawyer_filter=" . $selected_lawyer_id);
        exit();
    }
}

if (isset($_POST['edit_archived_case'])) {
    processEditArchivedCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=archived");
        exit();
    }
}

if (isset($_GET['delete_case'])) {
    processDeleteCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=cases");
        exit();
    }
}

if (isset($_GET['restore_case'])) {
    processRestoreArchivedCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=cases");
        exit();
    }
}

if (isset($_GET['delete_archived']) && $isSuperAdmin) {
    $case_id = intval($_GET['delete_archived']);
    $res = permanentDeleteArchivedCase($conn, $case_id);
    if ($res) {
        $message = 'Case permanently deleted.';
        $messageType = 'success';
        header("Location: dashboard.php?section=archived");
        exit();
    } else {
        $message = 'Failed to delete case.';
        $messageType = 'danger';
    }
}

if (isset($_GET['archive_case'])) {
    processArchiveCase($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=archived");
        exit();
    }
}

if (isset($_POST['add_user']) && $isSuperAdmin) {
    processAddUser($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=users");
        exit();
    }
}

if (isset($_POST['edit_user']) && $isSuperAdmin) {
    processEditUser($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=users");
        exit();
    }
}

if (isset($_GET['delete_user']) && $isSuperAdmin) {
    processDeleteUser($conn, $message, $messageType, $_SESSION['user_id']);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=users");
        exit();
    }
}

if (isset($_POST['add_schedule'])) {
    processAddSchedule($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=schedule&lawyer_filter=" . intval($_POST['lawyer_id']));
        exit();
    }
}

if (isset($_POST['edit_schedule'])) {
    processEditSchedule($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=schedule");
        exit();
    }
}

if (isset($_GET['delete_schedule_id'])) {
    processDeleteSchedule($conn, $message, $messageType);
    if ($messageType === 'success') {
        header("Location: dashboard.php?section=schedule");
        exit();
    }
}

if ($isSuperAdmin) {
    $stats = getDashboardStats($conn);
    $casesByStatus = getCasesByStatus($conn);
    $casesByPriority = getCasesByPriority($conn);
    $monthlyTrends = getMonthlyCaseTrends($conn);
    $lawyerPerformance = getLawyerPerformanceDetailed($conn);
    $recentActivity = getRecentActivity($conn);
    $alerts = getAlerts($conn);
    $upcomingSchedules = getUpcomingSchedules($conn);
    $highPriorityCases = getHighPriorityCases($conn);
    $overallStats = getOverallStats($conn);
    $weeklyTrends = getWeeklyTrends($conn);
    $yearlyTrends = getYearlyTrends($conn);
    $cases = getCases($conn, $selected_lawyer_id);
} else {
    $stats = getLawyerDashboardStats($conn, $current_user_id);
    $monthlyTrends = getLawyerMonthlyTrends($conn, $current_user_id, 6);
    $upcomingSchedules = getLawyerUpcomingSchedules($conn, $current_user_id, 14);
    $cases = getLawyerCases($conn, $current_user_id);
    $alerts = [];
}

$lawyers = getLawyers($conn);
$lawyer_case_counts = getLawyerCaseCounts($conn);
$selected_lawyer_name = getSelectedLawyerName($conn, $selected_lawyer_id);
$users_result = getUsers($conn);

$archived_cases = getArchivedCases($conn, $isSuperAdmin ? $selected_lawyer_id : 0);
$archived_case_counts = getArchivedCaseCounts($conn);

$schedules = getAllSchedules($conn, $selected_lawyer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDRL Law Office | Case Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="main-content" id="mainContent">
    <div class="top-navbar">
        <div><button class="menu-toggle" id="menuToggle"><i class="bi bi-list"></i></button></div>
        <div class="d-flex align-items-center gap-3">
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?></div>
            <a href="logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>

    <div class="container-fluid p-4">
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;" role="alert" id="autoCloseAlert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php include 'views/dashboard_view.php'; ?>
        <?php include 'views/cases_view.php'; ?>
        <?php include 'views/archived_cases_view.php'; ?>
        <?php include 'views/schedule_view.php'; ?>
        <?php include 'views/users_view.php'; ?>
    </div>
</div>

<?php include 'modals/case_modals.php'; ?>
<?php include 'modals/schedule_modals.php'; ?>
<?php include 'modals/user_modals.php'; ?>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Are you sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmActionBtn" class="btn btn-danger">Confirm</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('scheduleDate')?.addEventListener('change', function() {
    const lawyer = document.getElementById('scheduleLawyerFilter').value;
    window.location.href = 'dashboard.php?section=schedule&schedule_date=' + this.value + '&lawyer_filter=' + lawyer;
});

document.getElementById('scheduleLawyerFilter')?.addEventListener('change', function() {
    const date = document.getElementById('scheduleDate').value;
    window.location.href = 'dashboard.php?section=schedule&schedule_date=' + date + '&lawyer_filter=' + this.value;
});

document.querySelectorAll('.edit-schedule').forEach(btn => {
    btn.addEventListener('click', function() {
        const data = $(this).data('schedule');
        $('#edit_schedule_id').val(data.schedule_id);
        $('#edit_lawyer_id').val(data.lawyer_id);
        $('#edit_schedule_date').val(data.schedule_date);
        $('#edit_start_time').val(data.start_time);
        $('#edit_end_time').val(data.end_time);
        $('#edit_event_title').val(data.event_title);
        $('#edit_event_type').val(data.event_type);
        $('#edit_location').val(data.location || '');
        $('#edit_description').val(data.description || '');
        new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
    });
});

// Initial section on page load - get section from URL
(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var section = urlParams.get('section') || 'dashboard';
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.classList.remove('active');
        if (link.getAttribute('data-section') === section) link.classList.add('active');
    });
    document.querySelectorAll('.content-section').forEach(function(s) {
        s.classList.remove('active');
    });
    var target = document.getElementById(section + '-section');
    if (target) target.classList.add('active');
})();

// Confirmation Modal Handler
document.addEventListener('click', function(e) {
    const confirmLink = e.target.closest('[data-confirm]');
    if (confirmLink) {
        e.preventDefault();
        const message = confirmLink.getAttribute('data-confirm') || 'Are you sure?';
        const href = confirmLink.getAttribute('href');
        
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmActionBtn').href = href || '#';
        
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }
});

// Auto-close alert after 5 seconds
const alertEl = document.getElementById('autoCloseAlert');
if (alertEl) {
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertEl);
        bsAlert.close();
    }, 5000);
}
</script>
