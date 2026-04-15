<div id="analytics-section" class="content-section">
    <div class="analytics-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1"><i class="bi bi-graph-up me-2"></i>Analytics</h2>
                <p class="mb-0 text-white-50">Lawyer Performance & Statistics</p>
            </div>
            <div class="d-flex gap-2">
                <select id="periodFilter" class="form-select" style="width: auto; min-width: 150px;">
                    <option value="weekly">This Week</option>
                    <option value="monthly" selected>This Month</option>
                    <option value="yearly">This Year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">This Week</h6>
                        <h3 class="mb-0"><?php echo $overallStats['cases_this_week']; ?></h3>
                        <small class="text-muted">New Cases</small>
                    </div>
                    <div><i class="bi bi-calendar-week fs-1" style="color: #0d6efd;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0"><?php echo $overallStats['cases_this_month']; ?></h3>
                        <small class="text-muted">New Cases</small>
                    </div>
                    <div><i class="bi bi-calendar-month fs-1" style="color: #198754;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">This Year</h6>
                        <h3 class="mb-0"><?php echo $overallStats['cases_this_year']; ?></h3>
                        <small class="text-muted">Total Cases</small>
                    </div>
                    <div><i class="bi bi-calendar3 fs-1" style="color: #d4af37;"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Completed</h6>
                        <h3 class="mb-0 text-success"><?php echo $overallStats['completed_this_month']; ?></h3>
                        <small class="text-success">This Month</small>
                    </div>
                    <div><i class="bi bi-check-circle-fill fs-1" style="color: #198754;"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="table-container h-100">
                <h5 class="mb-3"><i class="bi bi-bar-chart me-2" style="color: #d4af37;"></i>Cases Trend</h5>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="table-container h-100">
                <h5 class="mb-3"><i class="bi bi-pie-chart me-2" style="color: #d4af37;"></i>Cases by Status</h5>
                <div class="chart-container" style="height: 200px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="status-legend mt-3">
                    <?php
                    $statusColors = [
                        'Pending' => '#ffc107',
                        'In Progress' => '#0d6efd',
                        'Under Review' => '#17a2b8',
                        'Completed' => '#198754',
                        'Closed' => '#6c757d'
                    ];
                    foreach ($casesByStatus as $status => $count): ?>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center">
                            <span class="status-dot me-2" style="background: <?php echo $statusColors[$status] ?? '#d4af37'; ?>"></span>
                            <small><?php echo $status; ?></small>
                        </div>
                        <span class="badge bg-secondary"><?php echo $count; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lawyer Performance Table -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2" style="color: #d4af37;"></i>Lawyer Performance</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Lawyer</th>
                        <th class="text-center">Active Cases</th>
                        <th class="text-center">High Priority</th>
                        <th class="text-center">This Week</th>
                        <th class="text-center">This Month</th>
                        <th class="text-center">This Year</th>
                        <th class="text-center">Archived</th>
                        <th class="text-center">Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lawyerPerformance as $perf): ?>
                    <?php 
                        $totalScore = $perf['monthly'] * 10 + $perf['archived_cases'] * 15 + $perf['yearly'] * 5;
                        $maxScore = 100;
                        $percentage = min(($totalScore / $maxScore) * 100, 100);
                        $perfClass = $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="lawyer-avatar me-2"><?php echo strtoupper(substr($perf['full_name'], 0, 1)); ?></div>
                                <?php echo htmlspecialchars($perf['full_name']); ?>
                            </div>
                        </td>
                        <td class="text-center"><span class="badge bg-primary"><?php echo $perf['active_cases']; ?></span></td>
                        <td class="text-center">
                            <?php if ($perf['high_priority'] > 0): ?>
                                <span class="badge bg-danger"><?php echo $perf['high_priority']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><strong><?php echo $perf['weekly']; ?></strong></td>
                        <td class="text-center"><strong><?php echo $perf['monthly']; ?></strong></td>
                        <td class="text-center"><?php echo $perf['yearly']; ?></td>
                        <td class="text-center">
                            <span class="badge bg-secondary"><?php echo $perf['archived_cases']; ?></span>
                        </td>
                        <td style="min-width: 150px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar <?php echo $perfClass; ?>" role="progressbar" 
                                         style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <small class="text-muted"><?php echo round($percentage); ?>%</small>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-trophy-fill me-2" style="color: #d4af37;"></i>Top Performers This Month</h5>
                <div class="top-performers">
                    <?php
                    $topPerf = $lawyerPerformance;
                    usort($topPerf, function($a, $b) {
                        return $b['completed_this_month'] - $a['completed_this_month'];
                    });
                    $rank = 1;
                    foreach (array_slice($topPerf, 0, 5) as $lawyer): 
                        if ($lawyer['completed_this_month'] == 0 && $rank > 1) break;
                    ?>
                    <div class="performer-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rank-badge <?php echo $rank <= 3 ? 'top-' . $rank : ''; ?>"><?php echo $rank; ?></div>
                            <div class="lawyer-avatar"><?php echo strtoupper(substr($lawyer['full_name'], 0, 1)); ?></div>
                            <div class="flex-grow-1">
                                <strong><?php echo htmlspecialchars($lawyer['full_name']); ?></strong>
                                <div class="small text-muted"><?php echo $lawyer['completed_this_month']; ?> completed</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success"><?php echo $lawyer['monthly']; ?> cases</span>
                            </div>
                        </div>
                    </div>
                    <?php $rank++; endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-exclamation-triangle me-2" style="color: #dc3545;"></i>High Workload Lawyers</h5>
                <?php
                $highWorkload = array_filter($lawyerPerformance, function($p) {
                    return $p['active_cases'] > 10 || $p['high_priority'] > 3;
                });
                usort($highWorkload, function($a, $b) {
                    return $b['active_cases'] - $a['active_cases'];
                });
                ?>
                <?php if (empty($highWorkload)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: #198754;"></i>
                    <p class="mt-2 mb-0 text-muted">All lawyers have manageable workload</p>
                </div>
                <?php else: ?>
                <div class="high-workload-list">
                    <?php foreach ($highWorkload as $lawyer): ?>
                    <div class="workload-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="lawyer-avatar"><?php echo strtoupper(substr($lawyer['full_name'], 0, 1)); ?></div>
                                <div>
                                    <strong><?php echo htmlspecialchars($lawyer['full_name']); ?></strong>
                                    <div class="small text-muted">
                                        <?php echo $lawyer['active_cases']; ?> active cases
                                        <?php if ($lawyer['high_priority'] > 0): ?>
                                            | <span class="text-danger"><?php echo $lawyer['high_priority']; ?> urgent</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="badge <?php echo $lawyer['active_cases'] > 15 ? 'bg-danger' : 'bg-warning'; ?>">
                                <?php echo $lawyer['active_cases']; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trendCtx = document.getElementById('trendChart')?.getContext('2d');
    const statusCtx = document.getElementById('statusChart')?.getContext('2d');
    
    const weeklyData = <?php echo json_encode($weeklyTrends); ?>;
    const yearlyData = <?php echo json_encode($yearlyTrends); ?>;
    const casesByStatus = <?php echo json_encode($casesByStatus); ?>;
    const statusLabels = Object.keys(casesByStatus);
    const statusValues = Object.values(casesByStatus);

    function renderTrendChart(data, type) {
        if (!trendCtx) return;
        
        let labels, values, label;
        
        if (type === 'weekly') {
            labels = data.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('en-US', { weekday: 'short' });
            });
            values = data.map(d => d.count);
            label = 'Daily Cases';
        } else if (type === 'yearly') {
            labels = data.map(d => {
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return monthNames[parseInt(d.month) - 1];
            });
            values = data.map(d => d.count);
            label = 'Monthly Cases';
        } else {
            labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            const weeks = [[], [], [], []];
            data.forEach(d => {
                const date = new Date(d.date);
                const week = Math.floor((date.getDate() - 1) / 7);
                if (week < 4) weeks[week].push(d.count);
            });
            values = weeks.map(w => w.reduce((a, b) => a + b, 0));
            label = 'Weekly Cases';
        }

        if (window.trendChartInstance) {
            window.trendChartInstance.destroy();
        }

        window.trendChartInstance = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    borderColor: '#d4af37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    renderTrendChart(weeklyData, 'monthly');

    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#ffc107', '#0d6efd', '#17a2b8', '#198754', '#6c757d'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: { legend: { display: false } }
            }
        });
    }

    document.getElementById('periodFilter')?.addEventListener('change', function() {
        const period = this.value;
        if (period === 'weekly') {
            renderTrendChart(weeklyData, 'weekly');
        } else if (period === 'monthly') {
            renderTrendChart(weeklyData, 'monthly');
        } else {
            renderTrendChart(yearlyData, 'yearly');
        }
    });
});
</script>

<style>
.analytics-header {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: white;
    padding: 1.5rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

.analytics-header h2 {
    color: white;
}

.analytics-header .form-select {
    background: rgba(255,255,255,0.9);
    border: none;
}

.performer-item, .workload-item {
    padding: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.performer-item:hover, .workload-item:hover {
    background: #fafafa;
}

.performer-item:last-child, .workload-item:last-child {
    border-bottom: none;
}

.rank-badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background: #e9ecef;
    color: #6c757d;
}

.rank-badge.top-1 {
    background: linear-gradient(135deg, #ffd700, #ffb700);
    color: #1a1a2e;
}

.rank-badge.top-2 {
    background: linear-gradient(135deg, #c0c0c0, #a0a0a0);
    color: #1a1a2e;
}

.rank-badge.top-3 {
    background: linear-gradient(135deg, #cd7f32, #b06c28);
    color: white;
}

.top-performers, .high-workload-list {
    max-height: 400px;
    overflow-y: auto;
}
</style>
