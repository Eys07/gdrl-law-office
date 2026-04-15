<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <?php if (isset($lawyer_id)): ?>
                    <input type="hidden" name="lawyer_id" value="<?php echo $lawyer_id; ?>">
                    <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label">Attorney *</label>
                        <select name="lawyer_id" class="form-select" required>
                            <option value="">Select Attorney</option>
                            <?php foreach($lawyers as $lawyer): ?>
                            <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="schedule_date" class="form-control" value="<?php echo $selected_date ?? date('Y-m-d'); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Start Time *</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">End Time *</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="event_title" class="form-control" placeholder="e.g., Court Hearing - Smith vs Jones" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Type *</label>
                        <select name="event_type" class="form-select" required>
                            <option value="Court Hearing">Court Hearing</option>
                            <option value="Client Meeting">Client Meeting</option>
                            <option value="Filing Deadline">Filing Deadline</option>
                            <option value="Deposition">Deposition</option>
                            <option value="Mediation">Mediation</option>
                            <option value="Research">Research</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g., Room 101, Main Court">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_schedule" class="btn btn-gold">Add Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="schedule_id" id="edit_schedule_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Attorney *</label>
                        <select name="lawyer_id" id="edit_lawyer_id" class="form-select" required>
                            <option value="">Select Attorney</option>
                            <?php foreach($lawyers as $lawyer): ?>
                            <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="schedule_date" id="edit_schedule_date" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Start Time *</label>
                            <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">End Time *</label>
                            <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="event_title" id="edit_event_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Type *</label>
                        <select name="event_type" id="edit_event_type" class="form-select" required>
                            <option value="Court Hearing">Court Hearing</option>
                            <option value="Client Meeting">Client Meeting</option>
                            <option value="Filing Deadline">Filing Deadline</option>
                            <option value="Deposition">Deposition</option>
                            <option value="Mediation">Mediation</option>
                            <option value="Research">Research</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" id="edit_location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_schedule" class="btn btn-gold">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
