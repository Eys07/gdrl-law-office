<div id="schedule-section" class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <h2 class="mb-0"><i class="bi bi-calendar-week me-2" style="color: #d4af37;"></i>Attorney Schedule</h2>
            <div class="live-clock">
                <i class="bi bi-clock-history me-1"></i>
                <span id="currentDate"><?php echo date('F d, Y'); ?></span>
                <span class="time-display" id="currentTime"><?php echo date('h:i:s A'); ?></span>
            </div>
        </div>
        <div class="d-flex gap-3 flex-wrap align-items-center">
            <select id="scheduleLawyerFilter" class="form-select" style="width: auto; min-width: 200px;">
                <option value="0">All Lawyers</option>
                <?php foreach($lawyers as $lawyer): ?>
                    <option value="<?php echo $lawyer['user_id']; ?>" <?php echo $selected_lawyer_id == $lawyer['user_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lawyer['full_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="view-toggle">
                <button class="btn btn-outline-gold active" id="monthViewBtn">
                    <i class="bi bi-calendar-month"></i> Month
                </button>
                <button class="btn btn-outline-gold" id="weekViewBtn">
                    <i class="bi bi-calendar-week"></i> Week
                </button>
                <button class="btn btn-outline-gold" id="dayViewBtn">
                    <i class="bi bi-calendar-day"></i> Day
                </button>
            </div>
        </div>
    </div>

    <!-- Large Google Calendar Style View -->
    <div class="google-calendar-container">
        <div class="calendar-nav">
            <button class="btn btn-sm btn-outline-secondary" id="todayBtn">Today</button>
            <div class="calendar-nav-buttons">
                <button class="btn btn-sm btn-outline-secondary" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                <button class="btn btn-sm btn-outline-secondary" id="nextBtn"><i class="bi bi-chevron-right"></i></button>
            </div>
            <h3 id="calendarTitle" class="calendar-title"><?php echo date('F Y'); ?></h3>
        </div>
        
        <div id="googleCalendar" class="google-calendar"></div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Schedule / Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=schedule" id="addScheduleForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select name="lawyer_id" class="form-select" required>
                                <option value="">Select Lawyer</option>
                                <?php foreach($lawyers as $lawyer): ?>
                                    <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="schedule_date" id="modal_schedule_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Type <span class="text-danger">*</span></label>
                            <select name="event_type" class="form-select" required>
                                <option value="Court Hearing">Court Hearing</option>
                                <option value="Appointment">Client Meeting</option>
                                <option value="Client Meeting">Client Meeting</option>
                                <option value="Filing Deadline">Filing Deadline</option>
                                <option value="Deposition">Deposition</option>
                                <option value="Mediation">Mediation</option>
                                <option value="Research">Research</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Case Title <span class="text-danger">*</span></label>
                            <input type="text" name="event_title" class="form-control" placeholder="e.g., Smith vs. Johnson" required>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    <h6><i class="bi bi-person-badge me-2"></i>Client Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="client_name" class="form-control" placeholder="Full name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="(555) 123-4567">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="client@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Office address or virtual meeting link">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Notes</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Additional details about the appointment..."></textarea>
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

<!-- Schedule Details Modal -->
<div class="modal fade" id="scheduleDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e, #16213e); color: white;">
                <h5 class="modal-title"><i class="bi bi-calendar-event me-2"></i>Schedule Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="scheduleDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="editFromDetailsBtn">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button type="button" class="btn btn-danger" id="deleteFromDetailsBtn">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Schedule / Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=schedule" id="editScheduleForm">
                <input type="hidden" name="schedule_id" id="edit_schedule_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select name="lawyer_id" id="edit_lawyer_id" class="form-select" required>
                                <option value="">Select Lawyer</option>
                                <?php foreach($lawyers as $lawyer): ?>
                                    <option value="<?php echo $lawyer['user_id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="schedule_date" id="edit_schedule_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Type <span class="text-danger">*</span></label>
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Case Title <span class="text-danger">*</span></label>
                            <input type="text" name="event_title" id="edit_event_title" class="form-control" required>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    <h6><i class="bi bi-person-badge me-2"></i>Client Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="client_name" id="edit_client_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" id="edit_phone" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="edit_location" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Notes</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_schedule" class="btn btn-gold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this schedule?</p>
                <p class="text-muted" id="deleteScheduleInfo"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="GET" action="?section=schedule" id="deleteScheduleForm">
                    <input type="hidden" name="delete_schedule_id" id="delete_schedule_id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Check if we're on the schedule page
const isSchedulePage = document.getElementById('schedule-section') !== null;

// Lawyer colors mapping from database
const lawyerColors = <?php 
    $colorMap = [];
    foreach($lawyers as $lawyer) {
        $colorMap[$lawyer['user_id']] = $lawyer['color_code'] ?? '#4285F4';
    }
    echo json_encode($colorMap);
?>;

// Store schedules data
let allSchedules = <?php echo json_encode($schedules); ?>;
let currentDate = new Date(<?php echo date('Y'); ?>, <?php echo date('m') - 1; ?>, <?php echo date('d'); ?>);
let currentView = 'month';
let currentScheduleId = null;

function getEventColor(type) {
    const colors = {
        'Court Hearing': '#dc3545',
        'Client Meeting': '#0d6efd',
        'Filing Deadline': '#ffc107',
        'Deposition': '#17a2b8',
        'Mediation': '#28a745',
        'Research': '#6c757d',
        'Consultation': '#343a40',
        'Other': '#d4af37'
    };
    return colors[type] || '#d4af37';
}

function addOneHour(timeString) {
    if (!timeString) return '';
    const [hours, minutes] = timeString.split(':').map(Number);
    let newHours = hours + 1;
    const newMinutes = minutes;
    if (newHours >= 24) newHours = newHours - 24;
    return `${newHours.toString().padStart(2, '0')}:${newMinutes.toString().padStart(2, '0')}`;
}

function parseTime(timeStr) {
    const [hours, minutes] = timeStr.split(':').map(Number);
    return hours * 60 + minutes;
}

function formatTime(minutes) {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHour = hours % 12 || 12;
    return `${displayHour}:${mins.toString().padStart(2, '0')} ${ampm}`;
}

function formatDisplayTime(timeStr) {
    if (!timeStr) return '';
    const [hours, minutes] = timeStr.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

function calculateEventPositions(events) {
    const sortedEvents = [...events].sort((a, b) => {
        const aStart = parseTime(a.start_time);
        const bStart = parseTime(b.start_time);
        if (aStart !== bStart) return aStart - bStart;
        return parseTime(a.end_time) - parseTime(b.end_time);
    });
    
    const columns = [];
    
    sortedEvents.forEach(event => {
        const eventStart = parseTime(event.start_time);
        const eventEnd = parseTime(event.end_time);
        
        let placed = false;
        for (let i = 0; i < columns.length; i++) {
            const column = columns[i];
            const lastEventInColumn = column[column.length - 1];
            const lastEventEnd = parseTime(lastEventInColumn.end_time);
            
            if (eventStart >= lastEventEnd) {
                column.push(event);
                placed = true;
                break;
            }
        }
        
        if (!placed) {
            columns.push([event]);
        }
    });
    
    const totalColumns = columns.length;
    const eventPositions = {};
    
    columns.forEach((column, colIndex) => {
        column.forEach(event => {
            const eventStart = parseTime(event.start_time);
            const eventEnd = parseTime(event.end_time);
            
            let span = 1;
            for (let i = colIndex + 1; i < columns.length; i++) {
                const otherColumn = columns[i];
                const overlaps = otherColumn.some(otherEvent => {
                    const otherStart = parseTime(otherEvent.start_time);
                    const otherEnd = parseTime(otherEvent.end_time);
                    return (eventStart < otherEnd && eventEnd > otherStart);
                });
                if (overlaps) {
                    span++;
                } else {
                    break;
                }
            }
            
            eventPositions[event.schedule_id] = {
                column: colIndex,
                totalColumns: totalColumns,
                span: span,
                width: (span / totalColumns) * 100,
                left: (colIndex / totalColumns) * 100
            };
        });
    });
    
    return eventPositions;
}

function getSchedulesForDate(dateStr) {
    const lawyerFilter = document.getElementById('scheduleLawyerFilter');
    let filtered = allSchedules.filter(s => s.schedule_date === dateStr);
    if (lawyerFilter && lawyerFilter.value > 0) {
        filtered = filtered.filter(s => s.lawyer_id == lawyerFilter.value);
    }
    return filtered;
}

function getSchedulesForRange(startDate, endDate) {
    const lawyerFilter = document.getElementById('scheduleLawyerFilter');
    let filtered = allSchedules.filter(s => s.schedule_date >= startDate && s.schedule_date <= endDate);
    if (lawyerFilter && lawyerFilter.value > 0) {
        filtered = filtered.filter(s => s.lawyer_id == lawyerFilter.value);
    }
    return filtered;
}

function showScheduleDetails(schedule) {
    currentScheduleId = schedule.schedule_id;
    const eventColor = getEventColor(schedule.event_type);
    const lawyerColor = lawyerColors[schedule.lawyer_id] || '#4285F4';
    
    const detailsHtml = `
        <div style="background: linear-gradient(135deg, ${lawyerColor}10, ${lawyerColor}05); border-radius: 12px; padding: 1.5rem;">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4 style="color: ${eventColor}; border-left: 4px solid ${eventColor}; padding-left: 1rem;">
                        ${escapeHtml(schedule.event_title)}
                    </h4>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge" style="background-color: ${eventColor}; font-size: 0.9rem; padding: 0.5rem 1rem;">
                        ${escapeHtml(schedule.event_type)}
                    </span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <i class="bi bi-person-badge" style="color: ${lawyerColor};"></i>
                        <strong>Assigned Lawyer:</strong>
                        <span>${escapeHtml(schedule.lawyer_name)}</span>
                    </div>
                    <div class="detail-item mt-3">
                        <i class="bi bi-calendar-date" style="color: ${lawyerColor};"></i>
                        <strong>Date:</strong>
                        <span>${formatDate(schedule.schedule_date)}</span>
                    </div>
                    <div class="detail-item mt-3">
                        <i class="bi bi-clock" style="color: ${lawyerColor};"></i>
                        <strong>Time:</strong>
                        <span>${formatDisplayTime(schedule.start_time)} - ${formatDisplayTime(schedule.end_time)}</span>
                    </div>
                    ${schedule.location ? `
                    <div class="detail-item mt-3">
                        <i class="bi bi-geo-alt" style="color: ${lawyerColor};"></i>
                        <strong>Location:</strong>
                        <span>${escapeHtml(schedule.location)}</span>
                    </div>
                    ` : ''}
                </div>
                <div class="col-md-6">
                    ${schedule.client_name ? `
                    <div class="detail-item">
                        <i class="bi bi-person" style="color: ${lawyerColor};"></i>
                        <strong>Client Name:</strong>
                        <span>${escapeHtml(schedule.client_name)}</span>
                    </div>
                    ` : ''}
                    ${schedule.phone ? `
                    <div class="detail-item mt-3">
                        <i class="bi bi-telephone" style="color: ${lawyerColor};"></i>
                        <strong>Phone:</strong>
                        <span>${escapeHtml(schedule.phone)}</span>
                    </div>
                    ` : ''}
                    ${schedule.email ? `
                    <div class="detail-item mt-3">
                        <i class="bi bi-envelope" style="color: ${lawyerColor};"></i>
                        <strong>Email:</strong>
                        <span><a href="mailto:${escapeHtml(schedule.email)}" style="color: ${lawyerColor};">${escapeHtml(schedule.email)}</a></span>
                    </div>
                    ` : ''}
                </div>
            </div>
            
            ${schedule.description ? `
            <div class="mt-4 pt-3 border-top">
                <strong><i class="bi bi-card-text"></i> Description / Notes:</strong>
                <p class="mt-2 mb-0" style="white-space: pre-wrap;">${escapeHtml(schedule.description)}</p>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('scheduleDetailsContent').innerHTML = detailsHtml;
    new bootstrap.Modal(document.getElementById('scheduleDetailsModal')).show();
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

function renderMonthView(year, month) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay();
    const daysInMonth = lastDay.getDate();
    const today = new Date().toISOString().split('T')[0];
    
    let html = '<div class="calendar-month-grid">';
    
    const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    html += '<div class="calendar-weekdays">';
    weekdays.forEach(day => {
        html += `<div class="calendar-weekday">${day.substring(0, 3)}</div>`;
    });
    html += '</div>';
    
    html += '<div class="calendar-days">';
    
    for (let i = 0; i < startDay; i++) {
        html += '<div class="calendar-day empty"></div>';
    }
    
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const schedules = getSchedulesForDate(dateStr);
        const isToday = dateStr === today;
        
        html += `<div class="calendar-day ${isToday ? 'today' : ''}" data-date="${dateStr}">`;
        html += `<div class="calendar-day-number">${d}</div>`;
        html += '<div class="calendar-events">';
        
        schedules.slice(0, 3).forEach(schedule => {
            const lawyerColor = lawyerColors[schedule.lawyer_id] || '#4285F4';
            html += `
                <div class="calendar-event" style="background-color: ${lawyerColor}; border-left-color: ${getEventColor(schedule.event_type)};" 
                     data-schedule-id="${schedule.schedule_id}">
                    <div class="calendar-event-title">
                        <span class="calendar-event-time">${formatDisplayTime(schedule.start_time)}</span>
                        ${escapeHtml(schedule.event_title)}
                    </div>
                    <div class="calendar-event-lawyer">${escapeHtml(schedule.lawyer_name)}</div>
                </div>
            `;
        });
        
        if (schedules.length > 3) {
            html += `<div class="calendar-event-more">+${schedules.length - 3} more</div>`;
        }
        
        html += '</div></div>';
    }
    
    const remaining = 42 - (startDay + daysInMonth);
    for (let i = 0; i < remaining; i++) {
        html += '<div class="calendar-day empty"></div>';
    }
    
    html += '</div></div>';
    return html;
}

function renderWeekView(baseDate) {
    const startOfWeek = new Date(baseDate);
    startOfWeek.setDate(baseDate.getDate() - baseDate.getDay());
    const weekDates = [];
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(startOfWeek);
        date.setDate(startOfWeek.getDate() + i);
        weekDates.push(date);
    }
    
    const hours = Array.from({length: 13}, (_, i) => i + 8);
    
    let html = '<div class="calendar-week-view">';
    html += '<div class="week-header">';
    html += '<div class="time-column"></div>';
    
    weekDates.forEach(date => {
        const isToday = date.toDateString() === new Date().toDateString();
        html += `<div class="week-day-header ${isToday ? 'today' : ''}">
                        <div class="week-day-name">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                        <div class="week-day-date">${date.getDate()}</div>
                     </div>`;
    });
    html += '</div>';
    
    html += '<div class="week-body">';
    html += '<div class="time-column">';
    hours.forEach(hour => {
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        html += `<div class="time-slot">${displayHour}:00 ${ampm}</div>`;
    });
    html += '</div>';
    
    weekDates.forEach(date => {
        const dateStr = date.toISOString().split('T')[0];
        const schedules = getSchedulesForDate(dateStr);
        
        html += '<div class="week-day-column">';
        
        hours.forEach(hour => {
            const slotSchedules = schedules.filter(s => {
                const startHour = parseInt(s.start_time.split(':')[0]);
                return startHour === hour;
            });
            
            html += `<div class="week-time-slot" data-date="${dateStr}" data-time="${String(hour).padStart(2, '0')}:00">`;
            
            slotSchedules.forEach(schedule => {
                const lawyerColor = lawyerColors[schedule.lawyer_id] || '#4285F4';
                html += `
                    <div class="week-event" style="background-color: ${lawyerColor};" data-schedule-id="${schedule.schedule_id}">
                        <div class="week-event-time">${formatDisplayTime(schedule.start_time)}</div>
                        <div class="week-event-title">${escapeHtml(schedule.event_title)}</div>
                    </div>
                `;
            });
            
            html += '</div>';
        });
        
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function renderDayView(date) {
    const dateStr = date.toISOString().split('T')[0];
    const schedules = getSchedulesForDate(dateStr);
    
    let minTime = 480;
    let maxTime = 1200;
    
    schedules.forEach(schedule => {
        const start = parseTime(schedule.start_time);
        const end = parseTime(schedule.end_time);
        minTime = Math.min(minTime, start);
        maxTime = Math.max(maxTime, end);
    });
    
    minTime = Math.floor(minTime / 60) * 60;
    maxTime = Math.ceil(maxTime / 60) * 60;
    
    const totalMinutes = maxTime - minTime;
    const hourHeight = 60;
    
    const eventPositions = calculateEventPositions(schedules);
    
    let html = `<div class="calendar-day-view">
                    <div class="day-view-header">
                        <h3>${date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })}</h3>
                        <div class="day-view-stats">${schedules.length} appointment${schedules.length !== 1 ? 's' : ''}</div>
                    </div>
                    <div class="day-view-scrollable">
                        <div class="day-view-body" style="min-height: ${(totalMinutes / 60) * hourHeight + 60}px;">`;
    
    html += '<div class="day-time-column">';
    for (let minutes = minTime; minutes <= maxTime; minutes += 30) {
        const hour = Math.floor(minutes / 60);
        const minute = minutes % 60;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
        
        if (minute === 0) {
            html += `<div class="day-time-slot-label" style="top: ${((minutes - minTime) / totalMinutes) * 100}%;">
                            ${displayHour}:00 ${ampm}
                         </div>`;
        } else {
            html += `<div class="day-time-slot-label half-hour" style="top: ${((minutes - minTime) / totalMinutes) * 100}%;">
                            ${displayHour}:30
                         </div>`;
        }
    }
    html += '</div>';
    
    html += '<div class="day-events-column">';
    
    for (let minutes = minTime; minutes < maxTime; minutes += 30) {
        const hour = Math.floor(minutes / 60);
        const minute = minutes % 60;
        const timeString = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
        const topPercent = ((minutes - minTime) / totalMinutes) * 100;
        const heightPercent = (30 / totalMinutes) * 100;
        
        html += `<div class="day-time-slot" style="top: ${topPercent}%; height: ${heightPercent}%;" 
                       data-date="${dateStr}" data-time="${timeString}"></div>`;
    }
    
    schedules.forEach(schedule => {
        const startMinutes = parseTime(schedule.start_time);
        const endMinutes = parseTime(schedule.end_time);
        const topPercent = ((startMinutes - minTime) / totalMinutes) * 100;
        const heightPercent = ((endMinutes - startMinutes) / totalMinutes) * 100;
        
        const pos = eventPositions[schedule.schedule_id];
        const lawyerColor = lawyerColors[schedule.lawyer_id] || '#4285F4';
        const eventColor = getEventColor(schedule.event_type);
        
        let widthPercent = 95;
        let leftPercent = 0;
        
        if (pos && pos.totalColumns > 1) {
            widthPercent = pos.width * 0.95;
            leftPercent = pos.left;
        }
        
        html += `
            <div class="day-event" style="background-color: ${lawyerColor}20; border-left-color: ${eventColor}; top: ${topPercent}%; height: ${heightPercent}%; width: ${widthPercent}%; left: ${leftPercent}%;"
                 data-schedule-id="${schedule.schedule_id}">
                <div class="day-event-time">${formatDisplayTime(schedule.start_time)} - ${formatDisplayTime(schedule.end_time)}</div>
                <div class="day-event-title"><strong>${escapeHtml(schedule.event_title)}</strong></div>
                <div class="day-event-lawyer"><i class="bi bi-person-badge"></i> ${escapeHtml(schedule.lawyer_name)}</div>
                ${schedule.client_name ? `<div class="day-event-client"><i class="bi bi-person"></i> ${escapeHtml(schedule.client_name)}</div>` : ''}
                ${schedule.location ? `<div class="day-event-location"><i class="bi bi-geo-alt"></i> ${escapeHtml(schedule.location)}</div>` : ''}
                ${schedule.description ? `<div class="day-event-description"><i class="bi bi-card-text"></i> ${escapeHtml(schedule.description.substring(0, 100))}${schedule.description.length > 100 ? '...' : ''}</div>` : ''}
            </div>
        `;
    });
    
    html += '</div></div></div>';
    
    return html;
}

function renderCalendar() {
    const container = document.getElementById('googleCalendar');
    const titleEl = document.getElementById('calendarTitle');
    
    if (currentView === 'month') {
        titleEl.textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        container.innerHTML = renderMonthView(currentDate.getFullYear(), currentDate.getMonth());
    } else if (currentView === 'week') {
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        titleEl.textContent = `${startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
        container.innerHTML = renderWeekView(currentDate);
    } else if (currentView === 'day') {
        titleEl.textContent = currentDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        container.innerHTML = renderDayView(currentDate);
    }
    
    attachEventListeners();
}

function attachEventListeners() {
    document.querySelectorAll('.calendar-event, .week-event, .day-event').forEach(el => {
        el.removeEventListener('click', handleEventClick);
        el.addEventListener('click', handleEventClick);
    });
    
    document.querySelectorAll('.calendar-day:not(.empty)').forEach(el => {
        el.removeEventListener('click', handleDayClick);
        el.addEventListener('click', handleDayClick);
    });
    
    document.querySelectorAll('.week-time-slot, .day-time-slot').forEach(el => {
        el.removeEventListener('click', handleTimeSlotClick);
        el.addEventListener('click', handleTimeSlotClick);
    });
}

function handleEventClick(e) {
    e.stopPropagation();
    const scheduleId = this.dataset.scheduleId;
    const schedule = allSchedules.find(s => s.schedule_id == scheduleId);
    if (schedule) {
        showScheduleDetails(schedule);
    }
}

function handleDayClick(e) {
    if (!e.target.closest('.calendar-event') && !e.target.closest('.calendar-event-more')) {
        const date = this.dataset.date;
        if (date) {
            document.getElementById('modal_schedule_date').value = date;
            new bootstrap.Modal(document.getElementById('addScheduleModal')).show();
        }
    }
}

function handleTimeSlotClick(e) {
    if (!e.target.closest('.week-event, .day-event')) {
        const date = this.dataset.date;
        const time = this.dataset.time;
        if (date && time) {
            document.getElementById('modal_schedule_date').value = date;
            document.getElementById('start_time').value = time;
            document.getElementById('end_time').value = addOneHour(time);
            new bootstrap.Modal(document.getElementById('addScheduleModal')).show();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() - 1);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() - 7);
        } else if (currentView === 'day') {
            currentDate.setDate(currentDate.getDate() - 1);
        }
        if (isSchedulePage) {
            const params = new URLSearchParams(window.location.search);
            params.set('year', currentDate.getFullYear());
            params.set('month', currentDate.getMonth() + 1);
            params.set('section', 'schedule');
            window.history.pushState({}, '', '?' + params.toString());
        }
        renderCalendar();
    });
    
    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + 1);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() + 7);
        } else if (currentView === 'day') {
            currentDate.setDate(currentDate.getDate() + 1);
        }
        if (isSchedulePage) {
            const params = new URLSearchParams(window.location.search);
            params.set('year', currentDate.getFullYear());
            params.set('month', currentDate.getMonth() + 1);
            params.set('section', 'schedule');
            window.history.pushState({}, '', '?' + params.toString());
        }
        renderCalendar();
    });
    
    document.getElementById('todayBtn').addEventListener('click', () => {
        currentDate = new Date();
        if (isSchedulePage) {
            const params = new URLSearchParams(window.location.search);
            params.set('year', currentDate.getFullYear());
            params.set('month', currentDate.getMonth() + 1);
            params.set('section', 'schedule');
            window.history.pushState({}, '', '?' + params.toString());
        }
        renderCalendar();
    });
    
    document.getElementById('monthViewBtn').addEventListener('click', () => {
        currentView = 'month';
        document.querySelectorAll('.view-toggle .btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('monthViewBtn').classList.add('active');
        renderCalendar();
    });
    
    document.getElementById('weekViewBtn').addEventListener('click', () => {
        currentView = 'week';
        document.querySelectorAll('.view-toggle .btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('weekViewBtn').classList.add('active');
        renderCalendar();
    });
    
    document.getElementById('dayViewBtn').addEventListener('click', () => {
        currentView = 'day';
        document.querySelectorAll('.view-toggle .btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('dayViewBtn').classList.add('active');
        renderCalendar();
    });
    
    document.getElementById('scheduleLawyerFilter').addEventListener('change', () => {
        if (isSchedulePage) {
            const params = new URLSearchParams(window.location.search);
            params.set('lawyer_filter', this.value);
            params.set('section', 'schedule');
            window.history.pushState({}, '', '?' + params.toString());
        }
        renderCalendar();
    });
    
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    let endTimeManuallyEdited = false;
    
    if (startTimeInput && endTimeInput) {
        startTimeInput.addEventListener('change', () => {
            if (startTimeInput.value && !endTimeManuallyEdited) {
                endTimeInput.value = addOneHour(startTimeInput.value);
            }
        });
        endTimeInput.addEventListener('focus', () => { endTimeManuallyEdited = true; });
        
        document.getElementById('addScheduleModal').addEventListener('hidden.bs.modal', () => {
            endTimeManuallyEdited = false;
            startTimeInput.value = '';
            endTimeInput.value = '';
        });
    }
    
    document.getElementById('editFromDetailsBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('scheduleDetailsModal')).hide();
        const schedule = allSchedules.find(s => s.schedule_id == currentScheduleId);
        if (schedule) {
            document.getElementById('edit_schedule_id').value = schedule.schedule_id;
            document.getElementById('edit_lawyer_id').value = schedule.lawyer_id;
            document.getElementById('edit_schedule_date').value = schedule.schedule_date;
            document.getElementById('edit_start_time').value = schedule.start_time;
            document.getElementById('edit_end_time').value = schedule.end_time;
            document.getElementById('edit_event_title').value = schedule.event_title;
            document.getElementById('edit_event_type').value = schedule.event_type;
            document.getElementById('edit_client_name').value = schedule.client_name || '';
            document.getElementById('edit_phone').value = schedule.phone || '';
            document.getElementById('edit_email').value = schedule.email || '';
            document.getElementById('edit_location').value = schedule.location || '';
            document.getElementById('edit_description').value = schedule.description || '';
            new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
        }
    });
    
    document.getElementById('deleteFromDetailsBtn').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('scheduleDetailsModal')).hide();
        const schedule = allSchedules.find(s => s.schedule_id == currentScheduleId);
        if (schedule) {
            document.getElementById('delete_schedule_id').value = schedule.schedule_id;
            document.getElementById('deleteScheduleInfo').innerHTML = `
                <strong>${escapeHtml(schedule.event_title)}</strong><br>
                ${formatDisplayTime(schedule.start_time)} - ${formatDisplayTime(schedule.end_time)}<br>
                ${formatDate(schedule.schedule_date)}
            `;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }
    });
    
    renderCalendar();
});
</script>

<style>
.google-calendar-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    padding: 2rem;
}

.calendar-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    flex-wrap: wrap;
}

.calendar-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 500;
    flex: 1;
}

.view-toggle .btn {
    border-color: #d4af37;
    color: #d4af37;
}

.view-toggle .btn.active {
    background-color: #d4af37;
    color: #1a1a2e;
}

/* Month View Styles */
.calendar-month-grid {
    padding: 1rem;
    overflow-x: auto;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 0.5rem;
}

.calendar-weekday {
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    color: #666;
    border-bottom: 2px solid #e0e0e0;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: #e0e0e0;
    border: 1px solid #e0e0e0;
}

.calendar-day {
    background-color: white;
    min-height: 120px;
    padding: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #f5f5f5;
}

.calendar-day.empty {
    background-color: #fafafa;
    cursor: default;
}

.calendar-day.today {
    background-color: #fff9e6;
}

.calendar-day-number {
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #333;
}

.calendar-day.today .calendar-day-number {
    background-color: #d4af37;
    color: white;
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 50%;
}

.calendar-events {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.calendar-event {
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: transform 0.1s;
    border-left: 3px solid;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.calendar-event:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.calendar-event-time {
    font-size: 0.65rem;
    opacity: 0.9;
    margin-right: 0.25rem;
}

.calendar-event-lawyer {
    font-size: 0.6rem;
    opacity: 0.8;
}

.calendar-event-more {
    font-size: 0.7rem;
    color: #666;
    padding: 0.2rem;
    text-align: center;
    cursor: pointer;
}

/* Week View Styles */
.calendar-week-view {
    overflow-x: auto;
}

.week-header {
    display: grid;
    grid-template-columns: 80px repeat(7, 1fr);
    border-bottom: 1px solid #e0e0e0;
    min-width: 700px;
}

.time-column {
    border-right: 1px solid #e0e0e0;
}

.week-day-header {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
}

.week-day-header.today {
    background-color: #fff9e6;
}

.week-day-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.week-day-date {
    font-size: 1.2rem;
    font-weight: 500;
}

.week-body {
    display: grid;
    grid-template-columns: 80px repeat(7, 1fr);
    min-width: 700px;
}

.week-day-column {
    border-right: 1px solid #e0e0e0;
}

.time-slot {
    padding: 0.75rem;
    text-align: right;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.75rem;
    color: #666;
    height: 60px;
}

.week-time-slot {
    border-bottom: 1px solid #f0f0f0;
    height: 60px;
    padding: 0.25rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.week-time-slot:hover {
    background-color: #f5f5f5;
}

.week-event {
    color: white;
    padding: 0.25rem;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    margin-bottom: 0.25rem;
}

.week-event-time {
    font-size: 0.6rem;
    opacity: 0.9;
}

/* Day View Styles */
.calendar-day-view {
    display: flex;
    flex-direction: column;
    height: 80vh;
}

.day-view-header {
    padding: 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.day-view-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.day-view-stats {
    color: #666;
    font-size: 0.9rem;
}

.day-view-scrollable {
    flex: 1;
    overflow-y: auto;
    position: relative;
}

.day-view-body {
    display: flex;
    position: relative;
    min-height: 100%;
}

.day-time-column {
    width: 80px;
    flex-shrink: 0;
    position: relative;
    border-right: 1px solid #e0e0e0;
    background: white;
    z-index: 5;
}

.day-time-slot-label {
    position: absolute;
    right: 8px;
    font-size: 0.7rem;
    color: #666;
    transform: translateY(-50%);
    white-space: nowrap;
}

.day-time-slot-label.half-hour {
    font-size: 0.65rem;
    color: #999;
}

.day-events-column {
    flex: 1;
    position: relative;
    background: white;
}

.day-time-slot {
    position: absolute;
    left: 0;
    right: 0;
    cursor: pointer;
    transition: background-color 0.2s;
    border-bottom: 1px solid #f0f0f0;
    z-index: 1;
}

.day-time-slot:hover {
    background-color: rgba(212, 175, 55, 0.1);
}

.day-event {
    position: absolute;
    background-color: rgba(66, 133, 244, 0.15);
    border-left: 4px solid;
    border-radius: 6px;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    overflow-y: auto;
    z-index: 10;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.day-event:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    z-index: 20;
}

.day-event-time {
    font-size: 0.7rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.day-event-title {
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.day-event-lawyer, .day-event-client, .day-event-location, .day-event-description {
    font-size: 0.7rem;
    color: #555;
    margin-top: 0.2rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.day-event i {
    margin-right: 0.25rem;
    font-size: 0.65rem;
}

/* Details Modal Styles */
.detail-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item i {
    width: 24px;
    margin-right: 8px;
}

.detail-item strong {
    display: inline-block;
    width: 110px;
    color: #555;
}

.detail-item span {
    color: #333;
}

.btn-gold {
    background-color: #d4af37;
    color: #1a1a2e;
    border: none;
}

.btn-gold:hover {
    background-color: #c4a02e;
    color: #1a1a2e;
}

.btn-outline-gold {
    border-color: #d4af37;
    color: #d4af37;
}

.btn-outline-gold:hover {
    background-color: #d4af37;
    color: #1a1a2e;
}

.live-clock {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .calendar-day {
        min-height: 80px;
    }
    
    .calendar-event-title, .calendar-event-lawyer {
        display: none;
    }
    
    .calendar-event {
        width: 8px;
        height: 8px;
        padding: 0;
        border-radius: 50%;
    }
    
    .day-event-title, .day-event-lawyer, .day-event-client {
        font-size: 0.65rem;
    }
    
    .day-view-header h3 {
        font-size: 1rem;
    }
    
    .detail-item strong {
        width: 90px;
        font-size: 0.85rem;
    }
    /* Make all main text bolder */
body, .content-section, .calendar-day-number, .calendar-event-title, 
.calendar-event-lawyer, .week-day-name, .week-day-date, .day-event-title, 
.day-event-time, .detail-item strong, .modal-title, .form-label, 
.calendar-title, .btn, .live-clock span, .view-toggle .btn,
h2, h3, h5, h6, .calendar-weekday, .time-slot, .week-event-time,
.day-view-stats, .badge, .calendar-event-more {
    font-weight: 700 !important;
}

/* Make filter select larger */
#scheduleLawyerFilter {
    min-width: 280px !important;
    padding: 0.6rem 1rem !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
}

/* Ensure no overlay issues - fix z-index and positioning */
.calendar-event, .week-event, .day-event {
    position: relative;
    z-index: 5;
}

.calendar-day:hover, .week-time-slot:hover, .day-time-slot:hover {
    z-index: 1;
}

.day-event {
    z-index: 10;
    overflow: visible;
}

.day-event:hover {
    z-index: 100;
}

/* Fix day view overlapping */
.day-view-scrollable {
    overflow-y: auto;
    overflow-x: hidden;
}

.day-events-column {
    position: relative;
    min-height: 800px;
}

.day-event {
    min-width: 180px;
    backdrop-filter: blur(0px);
}

/* Make calendar more responsive */
@media (max-width: 768px) {
    .calendar-month-grid {
        overflow-x: auto;
    }
    
    .calendar-days {
        min-width: 700px;
    }
    
    .calendar-week-view {
        overflow-x: auto;
    }
    
    .week-header, .week-body {
        min-width: 700px;
    }
    
    .day-event {
        font-size: 0.7rem;
        padding: 0.3rem;
    }
    
    .day-event-title {
        font-size: 0.75rem;
    }
    
    #scheduleLawyerFilter {
        min-width: 200px !important;
        font-size: 0.85rem !important;
    }
}

/* Better visibility for event text */
.calendar-event, .week-event {
    font-weight: 700;
    text-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

/* Make time text bold */
.calendar-event-time, .week-event-time, .day-event-time {
    font-weight: 800 !important;
}

/* Make lawyer names bold */
.calendar-event-lawyer, .day-event-lawyer {
    font-weight: 600 !important;
}

/* Modal text improvements */
.modal-body label {
    font-weight: 700 !important;
}

.modal-body input, .modal-body select, .modal-body textarea {
    font-weight: 500 !important;
}

/* Details modal improvements */
.detail-item {
    font-weight: 600;
    margin-bottom: 12px;
}

.detail-item strong {
    font-weight: 800;
    min-width: 120px;
    display: inline-block;
}
}
</style>