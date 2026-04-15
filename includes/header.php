<div class="overlay" id="overlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">GDRL</div>
        <div class="logo-sub">LAW OFFICE</div>
        <div class="mt-2 small text-white-50">Case Inventory Management System</div>
    </div>
    <nav class="nav flex-column mt-3">
        <a class="nav-link active" data-section="dashboard" href="#"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" data-section="cases" href="#"><i class="bi bi-folder2-open"></i> Active Cases</a>
        <a class="nav-link" data-section="archived" href="#"><i class="bi bi-archive"></i> Archived Cases</a>
        <a class="nav-link" data-section="schedule" href="#"><i class="bi bi-calendar-week"></i> Schedule</a>
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
        <a class="nav-link" data-section="users" href="#"><i class="bi bi-people-fill"></i> User Management</a>
        <?php endif; ?>
    </nav>
</div>