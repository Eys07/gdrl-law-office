<?php
// archived_cases_view.php
// This file displays archived cases.
?>
<div id="archived-section" class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2><i class="bi bi-archive me-2" style="color: #d4af37;"></i>Archived Case Inventory</h2>
        <?php if ($isSuperAdmin): ?>
        <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addArchivedModal">
            <i class="bi bi-plus-circle"></i> Add Archived
        </button>
        <?php endif; ?>
    </div>

    <?php if ($isSuperAdmin): ?>
    <div class="lawyer-filter-card">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <i class="bi bi-funnel-fill" style="color: #d4af37; font-size: 1.2rem;"></i>
            <span class="fw-semibold">Filter by Lawyer:</span>
            <select id="archivedLawyerFilter" class="lawyer-filter-select" onchange="window.location.href='?section=archived&lawyer_filter=' + this.value">
                <option value="0">-- All Lawyers --</option>
                <?php foreach($lawyers as $lawyer): ?>
                    <option value="<?php echo $lawyer['user_id']; ?>" <?php echo $selected_lawyer_id == $lawyer['user_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lawyer['full_name']); ?> 
                        (<?php echo $archived_case_counts[$lawyer['user_id']] ?? 0; ?> archived)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if($selected_lawyer_id > 0): ?>
                <a href="?section=archived&lawyer_filter=0" class="clear-filter"><i class="bi bi-x-circle"></i> Clear Filter</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
            <div style="min-width:350px; max-width:60%">
                <input id="archivedCasesSearch" class="form-control" placeholder="Search archived cases by name, title, contact...">
            </div>
            <div style="min-width:220px">
                <select id="archivedCasesSort" class="form-select">
                    <option value="created_desc">Sort: Newest First</option>
                    <option value="created_asc">Sort: Oldest First</option>
                    <option value="client_asc">Client Name A→Z</option>
                    <option value="client_desc">Client Name Z→A</option>
                    <option value="title_asc">Case Title A→Z</option>
                </select>
            </div>
        </div>
        <table class="table table-hover" id="archivedCasesTable" style="vertical-align: top;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No.</th>
                    <th style="width: 280px;">Client's Information</th>
                    <th style="width: 150px;">Assigned Lawyer</th>
                    <th style="width: 100px;">Case Title</th>
                    <th class="text-center" style="width: 90px;">Case Number</th>
                    <th style="width: 120px;">Court</th>
                    <th style="width: 180px;">Cause of Action</th>
                    <th style="width: 160px;">Stage/Incident</th>
                    <th class="text-center" style="width: 140px;">Archived Date/Time/By</th>
                    <th class="text-center" style="width: 110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($archived_cases)): ?>
                    <?php 
                    $counter = 1;
                    foreach($archived_cases as $case): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $counter; ?></td>
                        <td>
                            <div><strong class="text-uppercase">NAME: <?php echo htmlspecialchars($case['client_name']); ?></strong></div>
                            <div class="small" style="color: #333;">
                                <div><strong>CONTACT NUMBER:</strong> <?php echo htmlspecialchars($case['contact_no'] ?? 'N/A'); ?></div>
                                <div><strong>PRIMARY EMAIL:</strong> <?php echo $case['primary_email'] ? '<a href="mailto:' . htmlspecialchars($case['primary_email']) . '" title="Click to send email" style="color: #0d6efd; text-decoration: underline;">' . htmlspecialchars($case['primary_email']) . '</a>' : 'N/A'; ?></div>
                                <div><strong>SECONDARY EMAIL:</strong> <?php echo $case['secondary_email'] ? '<a href="mailto:' . htmlspecialchars($case['secondary_email']) . '" title="Click to send email" style="color: #0d6efd; text-decoration: underline;">' . htmlspecialchars($case['secondary_email']) . '</a>' : ''; ?></div>
                                <div><strong>MESSENGER:</strong> <?php echo htmlspecialchars($case['messenger'] ?? ''); ?></div>
                            </div>
                            <?php if(!empty($case['alt_contact_name']) || !empty($case['alt_contact_no'])): ?>
                            <div class="small" style="color: #333; margin-top: 4px;">
                                <strong style="text-decoration: underline;">ALTERNATIVE CONTACT</strong>
                                <div><strong>CONTACT NAME:</strong> <?php echo htmlspecialchars($case['alt_contact_name'] ?? ''); ?></div>
                                <div><strong>RELATIONSHIP:</strong> <?php echo htmlspecialchars($case['alt_contact_relationship'] ?? ''); ?></div>
                                <div><strong>CONTACT NUMBER:</strong> <?php echo htmlspecialchars($case['alt_contact_no'] ?? ''); ?></div>
                                <div><strong>PRIMARY EMAIL:</strong> <?php echo $case['alt_primary_email'] ? '<a href="mailto:' . htmlspecialchars($case['alt_primary_email']) . '" title="Click to send email" style="color: #0d6efd; text-decoration: underline;">' . htmlspecialchars($case['alt_primary_email']) . '</a>' : ''; ?></div>
                                <div><strong>SECONDARY EMAIL:</strong> <?php echo $case['alt_secondary_email'] ? '<a href="mailto:' . htmlspecialchars($case['alt_secondary_email']) . '" title="Click to send email" style="color: #0d6efd; text-decoration: underline;">' . htmlspecialchars($case['alt_secondary_email']) . '</a>' : ''; ?></div>
                                <div><strong>MESSENGER:</strong> <?php echo htmlspecialchars($case['alt_messenger'] ?? ''); ?></div>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 6px 4px; word-break: break-word;"><?php echo htmlspecialchars($case['lawyer_name'] ?? 'Unassigned'); ?></td>
                        <td style="padding: 6px 4px; word-break: break-word;"><?php echo htmlspecialchars($case['case_title'] ?? ''); ?></td>
                        <td class="text-center" style="padding: 6px 4px;"><?php echo htmlspecialchars($case['case_no'] ?? ''); ?></td>
                        <td style="padding: 6px 4px; word-break: break-word;"><?php echo htmlspecialchars($case['court'] ?? ''); ?></td>
                        <td style="padding: 6px 4px; word-break: break-word; white-space: normal;"><?php echo htmlspecialchars($case['cause_of_action'] ?? ''); ?></td>
                        <td style="padding: 6px 4px; word-break: break-word; white-space: normal;"><?php echo htmlspecialchars($case['stage_incident'] ?? ''); ?></td>
                        <td class="text-center" style="padding: 6px 4px;"><?php 
    if (!empty($case['archived_at'])) {
        echo date('M d, Y', strtotime($case['archived_at'])) . '<br>';
        echo '<small style="color: #666; font-size: 0.65rem;">' . date('h:i A', strtotime($case['archived_at'])) . '</small>';
        if (!empty($case['archived_by_name'])) {
            echo '<br><small style="color: #d4af37; font-weight: bold; font-size: 0.65rem;">by ' . htmlspecialchars($case['archived_by_name']) . '</small>';
        }
    } else {
        echo '-';
    }
?></td>
                        <td class="text-center" style="padding: 6px 4px;">
                            <div class="action-buttons" style="display: flex; flex-direction: column; gap: 2px; align-items: center; justify-content: center; height: 100%;">
                                <button class="btn btn-sm btn-outline-gold view-archived-case" data-case='<?php echo htmlspecialchars(json_encode($case), ENT_QUOTES); ?>' title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-gold edit-archived-case" data-case='<?php echo htmlspecialchars(json_encode($case), ENT_QUOTES); ?>' title="Edit Case">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?section=archived&restore_case=<?php echo $case['case_id']; ?>&lawyer_filter=<?php echo $selected_lawyer_id; ?>" class="btn btn-sm btn-outline-success" data-confirm="Restore this case to Active?" title="Restore to Active">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                <?php if($isSuperAdmin): ?>
                                <a href="?section=archived&delete_archived=<?php echo $case['case_id']; ?>" class="btn btn-sm btn-outline-danger" data-confirm="Permanently delete this case? This action cannot be undone." title="Delete Permanently">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                    $counter++;
                    endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">No archived cases found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- View Archived Case Modal -->
<div class="modal fade" id="viewArchivedModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Archived Case Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewArchivedContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Archived Case Modal -->
<div class="modal fade" id="addArchivedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-archive me-2"></i>Add Archived Case</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=archived">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Client Name *</label>
                            <input type="text" name="client_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Assign Lawyer</label>
                            <select name="lawyer_id" class="form-select form-select-sm">
                                <option value="">-- Unassigned --</option>
                                <?php foreach($lawyers as $lawyer): ?>
                                <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="mb-2 fw-bold">Contact Details</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="messenger" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="secondary_email" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="mb-2 fw-bold">Alternative Contact</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="alt_contact_name" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relationship</label>
                            <input type="text" name="alt_contact_relationship" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="alt_contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="alt_primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="alt_secondary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="alt_messenger" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Case Title</label>
                            <input type="text" name="case_title" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Case No.</label>
                            <input type="text" name="case_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Court (Branch)</label>
                            <input type="text" name="court" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Cause of Action</label>
                            <textarea name="cause_of_action" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Stage/Incident</label>
                            <input type="text" name="stage_incident" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_archived" class="btn btn-gold">Add Archived</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Archived Case Modal -->
<div class="modal fade" id="editArchivedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Archived Case</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=archived" id="editArchivedForm">
                <input type="hidden" name="case_id" id="edit_archived_case_id">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Client Name *</label>
                            <input type="text" name="client_name" id="edit_archived_client_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Assign Lawyer</label>
                            <select name="lawyer_id" id="edit_archived_assigned_lawyer" class="form-select form-select-sm">
                                <option value="">-- Unassigned --</option>
                                <?php foreach($lawyers as $lawyer): ?>
                                <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="mb-2 fw-bold">Contact Details</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="contact_no" id="edit_archived_contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="messenger" id="edit_archived_messenger" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="primary_email" id="edit_archived_primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="secondary_email" id="edit_archived_secondary_email" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="mb-2 fw-bold">Alternative Contact</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="alt_contact_name" id="edit_archived_alt_contact_name" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relationship</label>
                            <input type="text" name="alt_contact_relationship" id="edit_archived_alt_contact_relationship" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="alt_contact_no" id="edit_archived_alt_contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="alt_primary_email" id="edit_archived_alt_primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="alt_secondary_email" id="edit_archived_alt_secondary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="alt_messenger" id="edit_archived_alt_messenger" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Case Title</label>
                            <input type="text" name="case_title" id="edit_archived_case_title" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Case No.</label>
                            <input type="text" name="case_no" id="edit_archived_case_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Court (Branch)</label>
                            <input type="text" name="court" id="edit_archived_court" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Cause of Action</label>
                            <textarea name="cause_of_action" id="edit_archived_cause_of_action" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Stage/Incident</label>
                            <input type="text" name="stage_incident" id="edit_archived_stage_incident" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea name="notes" id="edit_archived_notes" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_archived_case" class="btn btn-gold">Update Case</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// View archived case functionality
document.querySelectorAll('.view-archived-case').forEach(btn => {
    btn.addEventListener('click', function() {
        const caseData = JSON.parse(this.dataset.case);
        let html = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Case Information</h6>
                    <table class="table table-bordered">
                        <tr><th style="width: 40%;">Client Name:</th><td><strong>${escapeHtml(caseData.client_name || '')}</strong></td></tr>
                        <tr><th>Case Title:</th><td>${escapeHtml(caseData.case_title || '')}</td></tr>
                        <tr><th>Case No.:</th><td>${escapeHtml(caseData.case_no || '')}</td></tr>
                        <tr><th>Court:</th><td>${escapeHtml(caseData.court || '')}</td></tr>
                        <tr><th>Cause of Action:</th><td>${escapeHtml(caseData.cause_of_action || '')}</td></tr>
                        <tr><th>Stage/Incident:</th><td>${escapeHtml(caseData.stage_incident || '')}</td></tr>
                        <tr><th>Assigned To:</th><td>${escapeHtml(caseData.lawyer_name || 'Unassigned')}</td></tr>
                        <tr><th>Archived Date:</th><td>${caseData.archived_at ? new Date(caseData.archived_at).toLocaleString() : 'N/A'}</td></tr>
                        <tr><th>Archived By:</th><td>${escapeHtml(caseData.archived_by_name || 'N/A')}</td></tr>
                        <tr><th>Notes:</th><td>${escapeHtml(caseData.notes || '')}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Contact Details</h6>
                    <table class="table table-bordered">
                        <tr><th style="width: 40%;">Contact No.:</th><td>${escapeHtml(caseData.contact_no || 'N/A')}</td></tr>
                        <tr><th>Primary Email:</th><td>${escapeHtml(caseData.primary_email || '')}</td></tr>
                        <tr><th>Secondary Email:</th><td>${escapeHtml(caseData.secondary_email || '')}</td></tr>
                        <tr><th>Messenger:</th><td>${escapeHtml(caseData.messenger || '')}</td></tr>
                    </table>
                    <h6 class="text-muted mt-3">Alternative Contact</h6>
                    <table class="table table-bordered">
                        <tr><th style="width: 40%;">Name:</th><td>${escapeHtml(caseData.alt_contact_name || '')}</td></tr>
                        <tr><th>Relationship:</th><td>${escapeHtml(caseData.alt_contact_relationship || '')}</td></tr>
                        <tr><th>Contact No.:</th><td>${escapeHtml(caseData.alt_contact_no || '')}</td></tr>
                        <tr><th>Primary Email:</th><td>${escapeHtml(caseData.alt_primary_email || '')}</td></tr>
                        <tr><th>Secondary Email:</th><td>${escapeHtml(caseData.alt_secondary_email || '')}</td></tr>
                        <tr><th>Messenger:</th><td>${escapeHtml(caseData.alt_messenger || '')}</td></tr>
                    </table>
                </div>
            </div>`;
        document.getElementById('viewArchivedContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('viewArchivedModal')).show();
    });
});

// Edit archived case functionality
document.querySelectorAll('.edit-archived-case').forEach(btn => {
    btn.addEventListener('click', function() {
        const caseData = JSON.parse(this.dataset.case);
        
        document.getElementById('edit_archived_case_id').value = caseData.case_id || '';
        document.getElementById('edit_archived_client_name').value = caseData.client_name || '';
        document.getElementById('edit_archived_contact_no').value = caseData.contact_no || '';
        document.getElementById('edit_archived_primary_email').value = caseData.primary_email || '';
        document.getElementById('edit_archived_secondary_email').value = caseData.secondary_email || '';
        document.getElementById('edit_archived_messenger').value = caseData.messenger || '';
        document.getElementById('edit_archived_assigned_lawyer').value = caseData.assigned_lawyer_id || '';
        document.getElementById('edit_archived_case_no').value = caseData.case_no || '';
        document.getElementById('edit_archived_court').value = caseData.court || '';
        document.getElementById('edit_archived_cause_of_action').value = caseData.cause_of_action || '';
        document.getElementById('edit_archived_stage_incident').value = caseData.stage_incident || '';
        document.getElementById('edit_archived_notes').value = caseData.notes || '';
        document.getElementById('edit_archived_alt_contact_name').value = caseData.alt_contact_name || '';
        document.getElementById('edit_archived_alt_contact_relationship').value = caseData.alt_contact_relationship || '';
        document.getElementById('edit_archived_alt_contact_no').value = caseData.alt_contact_no || '';
        document.getElementById('edit_archived_alt_primary_email').value = caseData.alt_primary_email || '';
        document.getElementById('edit_archived_alt_secondary_email').value = caseData.alt_secondary_email || '';
        document.getElementById('edit_archived_alt_messenger').value = caseData.alt_messenger || '';
        document.getElementById('edit_archived_case_title').value = caseData.case_title || '';
        
        new bootstrap.Modal(document.getElementById('editArchivedModal')).show();
    });
});

// Reset modal on hidden
document.getElementById('editArchivedModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('editArchivedForm').reset();
});

// Search functionality for archived cases
document.getElementById('archivedCasesSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('archivedCasesTable');
    const rows = table.getElementsByTagName('tr');
    
    for(let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for(let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent.toLowerCase();
            if(cellText.indexOf(searchTerm) > -1) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// Sort functionality for archived cases
document.getElementById('archivedCasesSort').addEventListener('change', function() {
    const sortValue = this.value;
    const table = document.getElementById('archivedCasesTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    
    rows.sort(function(a, b) {
        let aValue, bValue;
        
        switch(sortValue) {
            case 'client_asc':
                aValue = a.cells[1].textContent.toLowerCase();
                bValue = b.cells[1].textContent.toLowerCase();
                break;
            case 'client_desc':
                aValue = a.cells[1].textContent.toLowerCase();
                bValue = b.cells[1].textContent.toLowerCase();
                return bValue.localeCompare(aValue);
            case 'title_asc':
                aValue = a.cells[2].textContent.toLowerCase();
                bValue = b.cells[2].textContent.toLowerCase();
                break;
            case 'created_asc':
                // For archived date sorting (oldest first)
                const aDateText = a.cells[8].textContent;
                const bDateText = b.cells[8].textContent;
                const aDate = new Date(aDateText);
                const bDate = new Date(bDateText);
                return aDate - bDate;
            case 'created_desc':
                // For archived date sorting (newest first)
                const aDateTextDesc = a.cells[8].textContent;
                const bDateTextDesc = b.cells[8].textContent;
                const aDateDesc = new Date(aDateTextDesc);
                const bDateDesc = new Date(bDateTextDesc);
                return bDateDesc - aDateDesc;
            default:
                return 0;
        }
        
        return aValue.localeCompare(bValue);
    });
    
    // Reorder the rows
    for(let i = 0; i < rows.length; i++) {
        rows[i].cells[0].textContent = i + 1;
        tbody.appendChild(rows[i]);
    }
});

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>