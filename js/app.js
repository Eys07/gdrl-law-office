const overlay = document.getElementById('overlay');
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
});

let casesTableInitialized = false;
let casesTableInstance = null;

// Navigation handling
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');
    
    navLinks.forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            sections.forEach(s => s.classList.remove('active'));
            document.getElementById(section + '-section').classList.add('active');
            
            window.history.pushState({}, '', '?section=' + section);
            return false;
        };
    });
});

function fetchAllSchedules() {
    const lawyerFilter = document.getElementById('scheduleLawyerFilter');
    const lawyerId = lawyerFilter ? lawyerFilter.value : 0;
    
    fetch(`controllers/schedule_controller.php?ajax_get_all_schedules=1&lawyer_id=${lawyerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allSchedules = data.schedules;
                if (typeof renderCalendar === 'function') {
                    renderCalendar();
                }
            }
        })
        .catch(error => console.error('Error fetching schedules:', error));
}

function initCasesDataTable() {
    const activeCasesColumnDefs = [
        { width: '50px', targets: 0 },
        { width: '150px', targets: 1 },
        { width: '100px', targets: 2 },
        { width: '80px', targets: 3 },
        { width: '100px', targets: 4 },
        { width: '280px', targets: 5 },
        { width: '150px', targets: 6 },
        { width: '130px', targets: 7 },
        { orderable: false, targets: 7 }
    ];
    
    const archivedCasesColumnDefs = [
        { width: '50px', targets: 0 },
        { width: '150px', targets: 1 },
        { width: '100px', targets: 2 },
        { width: '80px', targets: 3 },
        { width: '100px', targets: 4 },
        { width: '280px', targets: 5 },
        { width: '150px', targets: 6 },
        { width: '180px', targets: 7 },
        { width: '130px', targets: 8 },
        { orderable: false, targets: 8 }
    ];

    if (!$.fn.DataTable.isDataTable('#casesTable')) {
        $('#casesTable').css('width', '100%');
        casesTableInstance = $('#casesTable').DataTable({
            dom: 'lrtip',
            pageLength: 15,
            autoWidth: false,
            language: {
                emptyTable: "No cases found",
                zeroRecords: "No matching cases found"
            },
            columnDefs: activeCasesColumnDefs,
            order: [[0, 'desc']]
        });
    }

    if (!$.fn.DataTable.isDataTable('#archivedCasesTable')) {
        $('#archivedCasesTable').css('width', '100%');
        $('#archivedCasesTable').DataTable({
            dom: 'lrtip',
            pageLength: 15,
            autoWidth: false,
            language: {
                emptyTable: "No archived cases found",
                zeroRecords: "No matching archived cases found"
            },
            columnDefs: archivedCasesColumnDefs,
            order: [[0, 'desc']]
        });
    }

    // External search box
    $('#casesSearch').off('input').on('input', function() {
        const v = $(this).val();
        casesTableInstance.search(v).draw();
    });

    // External sort select
    $('#casesSort').off('change').on('change', function() {
        const val = $(this).val();
        switch(val) {
            case 'created_desc': casesTableInstance.order([0, 'desc']).draw(); break;
            case 'created_asc': casesTableInstance.order([0, 'asc']).draw(); break;
            case 'client_asc': casesTableInstance.order([1, 'asc']).draw(); break;
            case 'client_desc': casesTableInstance.order([1, 'desc']).draw(); break;
            case 'status_asc': casesTableInstance.order([6, 'asc']).draw(); break;
            case 'status_desc': casesTableInstance.order([6, 'desc']).draw(); break;
            case 'title_asc': casesTableInstance.order([2, 'asc']).draw(); break;
            default: casesTableInstance.order([0, 'desc']).draw();
        }
    });

    // Archived cases external search box
    $('#archivedCasesSearch').off('input').on('input', function() {
        $('#archivedCasesTable').DataTable().search($(this).val()).draw();
    });

    // Archived cases external sort select
    $('#archivedCasesSort').off('change').on('change', function() {
        const val = $(this).val();
        const dt = $('#archivedCasesTable').DataTable();
        switch(val) {
            case 'created_desc': dt.order([0, 'desc']).draw(); break;
            case 'created_asc': dt.order([0, 'asc']).draw(); break;
            case 'client_asc': dt.order([1, 'asc']).draw(); break;
            case 'client_desc': dt.order([1, 'desc']).draw(); break;
            default: dt.order([0, 'desc']).draw();
        }
    });

    casesTableInitialized = true;
}

$(document).on('change', '.status-update', function() {
    const caseId = $(this).data('id');
    const status = $(this).val();
    
    $.ajax({
        url: 'dashboard.php',
        method: 'POST',
        data: { update_status: true, case_id: caseId, status: status },
        success: function(response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    location.reload();
                }
            } catch (e) {
                location.reload();
            }
        }
    });
});

$('.view-case-details').click(function() {
    const caseData = $(this).data('case');
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted">Client Information</h6>
                <table class="table table-bordered">
                    <tr><th style="width: 40%">Client Name:</th><td><strong class="text-uppercase">${escapeHtml(caseData.client_name)}</strong></td></tr>
                    <tr><th>Primary Email:</th><td>${escapeHtml(caseData.primary_email || 'N/A')}</td></tr>
                    <tr><th>Contact No.:</th><td>${escapeHtml(caseData.contact_no || 'N/A')}</td></tr>
                    <tr><th>Alt Contact No.:</th><td>${escapeHtml(caseData.alt_contact_no || '')}</td></tr>
                    <tr><th>Messenger:</th><td>${escapeHtml(caseData.messenger || 'N/A')}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Case Information</h6>
                <table class="table table-bordered">
                    <tr><th style="width: 40%">Status:</th><td><span class="status-badge status-${(caseData.status || 'Active').replace(/ /g, '-')}">${caseData.status || 'Active'}</span></td></tr>
                    <tr><th>Assigned Lawyer:</th><td>${escapeHtml(caseData.lawyer_name || 'Unassigned')}</td></tr>
                    <tr><th>Created:</th><td>${caseData.created_at}</td></tr>
                    <tr><th>Case Title:</th><td>${escapeHtml(caseData.case_title || '')}</td></tr>
                </table>
            </div>
        </div>
    `;
    
    $('#viewCaseContent').html(html);
    new bootstrap.Modal(document.getElementById('viewCaseModal')).show();
});

$('.edit-user').click(function() {
    const userData = $(this).data('user');
    $('#edit_user_id').val(userData.user_id);
    $('#edit_full_name').val(userData.full_name);
    $('#edit_username').val(userData.username);
    $('#edit_role').val(userData.role);
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
});

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
