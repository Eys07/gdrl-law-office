<?php
$isSuperAdmin = $_SESSION['role'] === 'super_admin';
?>

<div id="dashboard-section" class="content-section active">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><i class="bi bi-speedometer2 me-2"></i><?php echo $isSuperAdmin ? 'Admin Dashboard' : 'My Dashboard'; ?></h2>
                <p class="mb-0 text-white-50">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
            </div>
            <div class="text-white-50">
                <i class="bi bi-calendar3 me-1"></i> <?php echo date('F d, Y'); ?>
            </div>
        </div>
    </div>

    <?php if (!empty($alerts)): ?>
    <div class="alert-section mb-4">
        <?php foreach ($alerts as $alert): ?>
        <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
            <i class="bi <?php echo $alert['icon']; ?> me-2"></i>
            <?php echo $alert['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($isSuperAdmin): ?>
    <!-- Super Admin Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Cases</h6>
                        <h3 class="mb-0"><?php echo $stats['total_cases']; ?></h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> <?php echo $stats['cases_this_month']; ?> this month</small>
                    </div>
                    <div><i class="bi bi-folder2-open fs-1" style="color: #d4af37;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Active Lawyers</h6>
                        <h3 class="mb-0"><?php echo $stats['total_lawyers']; ?></h3>
                        <small class="text-muted"><?php echo $stats['total_secretaries']; ?> secretaries</small>
                    </div>
                    <div><i class="bi bi-people-fill fs-1" style="color: #0d6efd;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Active</h6>
                        <h3 class="mb-0 text-primary"><?php echo $stats['active_cases']; ?></h3>
                        <small class="text-primary"><i class="bi bi-folder-fill"></i> Current cases</small>
                    </div>
                    <div><i class="bi bi-folder2-open fs-1" style="color: #0d6efd;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Archived</h6>
                        <h3 class="mb-0 text-secondary"><?php echo $stats['archived_cases']; ?></h3>
                        <small class="text-secondary"><i class="bi bi-archive"></i> Archived cases</small>
                    </div>
                    <div><i class="bi bi-archive-fill fs-1" style="color: #6c757d;"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="table-container h-100">
                <h5 class="mb-3"><i class="bi bi-graph-up me-2" style="color: #d4af37;"></i>Monthly Case Trends</h5>
                <div class="chart-container" style="height: 200px;">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Lawyer Performance & Upcoming -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-people-fill me-2" style="color: #d4af37;"></i>Lawyer Performance</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-valign-top">
                        <thead>
                            <tr><th>Lawyer</th><th class="text-center">Active</th><th class="text-center">Archived</th><th>Workload</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lawyerPerformance as $perf): ?>
                            <?php $total = $perf['active_cases'] + $perf['archived_cases']; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($perf['full_name']); ?></td>
                                <td class="text-center"><span class="badge bg-primary"><?php echo $perf['active_cases']; ?></span></td>
                                <td class="text-center"><span class="badge bg-secondary"><?php echo $perf['archived_cases']; ?></span></td>
                                <td>
                                    <div class="progress" style="height: 8px; min-width: 80px;">
                                        <div class="progress-bar <?php echo $perf['active_cases'] > 10 ? 'bg-danger' : ($perf['active_cases'] > 5 ? 'bg-warning' : 'bg-success'); ?>" style="width: <?php echo min($perf['active_cases'] * 10, 100); ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-calendar-event me-2" style="color: #d4af37;"></i>Upcoming Event</h5>
                <?php if (empty($upcomingSchedules)): ?>
                <div class="text-center py-4 text-muted"><i class="bi bi-calendar-x" style="font-size: 2rem;"></i><p class="mt-2 mb-0">No upcoming events</p></div>
                <?php else: ?>
                <div class="upcoming-list">
                    <?php $nextEvent = reset($upcomingSchedules); ?>
                    <div class="upcoming-item">
                        <span class="badge <?php echo getEventTypeBadge($nextEvent['event_type']); ?> mb-1"><?php echo $nextEvent['event_type']; ?></span>
                        <h6 class="mb-1"><?php echo htmlspecialchars($nextEvent['event_title']); ?></h6>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($nextEvent['schedule_date'])); ?> - <?php echo htmlspecialchars($nextEvent['lawyer_name']); ?></small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Attorney Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Active</h6>
                        <h3 class="mb-0 text-primary"><?php echo $stats['active_cases']; ?></h3>
                        <small class="text-primary"><i class="bi bi-folder-fill"></i> My cases</small>
                    </div>
                    <div><i class="bi bi-folder2-open fs-1" style="color: #0d6efd;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Archived</h6>
                        <h3 class="mb-0 text-secondary"><?php echo $stats['archived_cases']; ?></h3>
                        <small class="text-secondary"><i class="bi bi-archive"></i> Closed cases</small>
                    </div>
                    <div><i class="bi bi-archive-fill fs-1" style="color: #6c757d;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0 text-success"><?php echo $stats['cases_this_month']; ?></h3>
                        <small class="text-success"><i class="bi bi-calendar-check"></i> New cases</small>
                    </div>
                    <div><i class="bi bi-calendar-event fs-1" style="color: #198754;"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="table-container h-100">
                <h5 class="mb-3"><i class="bi bi-graph-up me-2" style="color: #d4af37;"></i>My Performance</h5>
                <div class="chart-container" style="height: 180px;">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="table-container h-100">
                <h5 class="mb-3"><i class="bi bi-calendar-day me-2" style="color: #d4af37;"></i>My Schedule</h5>
                <?php if (empty($upcomingSchedules)): ?>
                <div class="text-center py-4 text-muted"><i class="bi bi-calendar-x" style="font-size: 2rem;"></i><p class="mt-2 mb-0">No upcoming appointments</p></div>
                <?php else: ?>
                <div class="upcoming-list">
                    <?php foreach (array_slice($upcomingSchedules, 0, 5) as $s): ?>
                    <div class="upcoming-item">
                        <span class="badge <?php echo getEventTypeBadge($s['event_type']); ?> mb-1"><?php echo $s['event_type']; ?></span>
                        <h6 class="mb-1"><?php echo htmlspecialchars($s['event_title']); ?></h6>
                        <small class="text-muted"><?php echo date('M d, h:i A', strtotime($s['schedule_date'] . ' ' . $s['start_time'])); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if ($isSuperAdmin): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trendsCtx = document.getElementById('trendsChart')?.getContext('2d');
    
    if (trendsCtx) {
        const monthLabels = <?php echo json_encode(array_keys($monthlyTrends)); ?>;
        const monthData = <?php echo json_encode(array_values($monthlyTrends)); ?>;
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: monthLabels.map(m => { const [y, mo] = m.split('-'); return new Date(y, mo-1).toLocaleDateString('en-US', {month:'short'}); }),
                datasets: [{ label: 'Cases', data: monthData, borderColor: '#d4af37', backgroundColor: 'rgba(212,175,55,0.1)', fill: true, tension: 0.4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
});
</script>
<?php else: ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trendsCtx = document.getElementById('trendsChart')?.getContext('2d');
    if (trendsCtx) {
        const monthLabels = <?php echo json_encode(array_keys($monthlyTrends)); ?>;
        const monthData = <?php echo json_encode(array_values($monthlyTrends)); ?>;
        new Chart(trendsCtx, {
            type: 'bar',
            data: {
                labels: monthLabels.map(m => { const [y, mo] = m.split('-'); return new Date(y, mo-1).toLocaleDateString('en-US', {month:'short'}); }),
                datasets: [{ label: 'Cases', data: monthData, backgroundColor: '#d4af37' }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
});
</script>
<?php endif; ?>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: white;
    padding: 1.5rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
}
.dashboard-header h2 { color: white; }
.upcoming-list { max-height: 300px; overflow-y: auto; }
.upcoming-item { padding: 0.75rem; border-bottom: 1px solid #eee; }
.upcoming-item:last-child { border-bottom: none; }
.upcoming-item:hover { background: #fafafa; }
</style>
