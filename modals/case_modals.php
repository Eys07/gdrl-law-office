<div class="modal fade" id="addCaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Case</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=cases">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
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
                            <input type="text" name="contact_no" id="add_contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="messenger" id="add_messenger" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="primary_email" id="add_primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="secondary_email" id="add_secondary_email" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="mb-2 fw-bold">Alternative Contact</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="alt_contact_name" id="add_alt_contact_name" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relationship</label>
                            <input type="text" name="alt_contact_relationship" id="add_alt_contact_relationship" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="alt_contact_no" id="add_alt_contact_no" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="alt_primary_email" id="add_alt_primary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="alt_secondary_email" id="add_alt_secondary_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="alt_messenger" id="add_alt_messenger" class="form-control form-control-sm">
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Case Title</label>
                            <input type="text" name="case_title" id="add_case_title" class="form-control form-control-sm" required>
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
                    <button type="submit" name="add_case" class="btn btn-gold">Add Case</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewCaseModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Case Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewCaseContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCaseModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Case</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=cases" id="editCaseForm">
                <input type="hidden" name="case_id" id="edit_case_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Client Name *</label>
                            <input type="text" name="client_name" id="edit_client_name" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Assign Lawyer</label>
                            <select name="lawyer_id" id="edit_assigned_lawyer" class="form-select">
                                <option value="">-- Unassigned --</option>
                                <?php foreach($lawyers as $lawyer): ?>
                                <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3 fw-bold">Contact Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contact No.</label>
                            <input type="text" name="contact_no" id="edit_contact_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Primary Email</label>
                            <input type="email" name="primary_email" id="edit_primary_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Secondary Email</label>
                            <input type="email" name="secondary_email" id="edit_secondary_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Messenger</label>
                            <input type="text" name="messenger" id="edit_messenger" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3 fw-bold">Alternative Contact</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Alt Name</label>
                            <input type="text" name="alt_contact_name" id="edit_alt_contact_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Relationship</label>
                            <input type="text" name="alt_contact_relationship" id="edit_alt_contact_relationship" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Alt Contact No.</label>
                            <input type="text" name="alt_contact_no" id="edit_alt_contact_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Alt Primary Email</label>
                            <input type="email" name="alt_primary_email" id="edit_alt_primary_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Alt Secondary Email</label>
                            <input type="email" name="alt_secondary_email" id="edit_alt_secondary_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Alt Messenger</label>
                            <input type="text" name="alt_messenger" id="edit_alt_messenger" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3 fw-bold">Case Title</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <input type="text" name="case_title" id="edit_case_title" class="form-control" placeholder="Case Title">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Case No.</label>
                            <input type="text" name="case_no" id="edit_case_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Court (Branch)</label>
                            <input type="text" name="court" id="edit_court" class="form-control">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold">Cause of Action</label>
                            <textarea name="cause_of_action" id="edit_cause_of_action" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold">Stage/Incident</label>
                            <input type="text" name="stage_incident" id="edit_stage_incident" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_case" class="btn btn-gold">Update Case</button>
                </div>
            </form>
        </div>
    </div>
</div>
