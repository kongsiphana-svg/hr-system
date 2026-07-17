const filterButtons = document.querySelectorAll('[data-leave-filter]');
const requestRows = document.querySelectorAll('[data-leave-request-row]');
const emptyRow = document.querySelector('[data-leave-empty]');
const detailsPanel = document.querySelector('[data-leave-details]');
const detailsTitle = document.querySelector('[data-leave-details-title]');
const detailsNote = document.querySelector('[data-leave-details-note]');
const detailsActions = document.querySelector('[data-leave-details-actions]');

const matchesFilter = (status, filter) => {
    if (filter === 'history') {
        return status !== 'pending';
    }

    return status === filter;
};

const updateDetails = (row) => {
    if (!detailsPanel || !detailsTitle || !detailsNote) {
        return;
    }

    detailsPanel.hidden = !row;

    if (!row) {
        return;
    }

    detailsTitle.textContent = `Request Details: ${row.dataset.employee}`;
    detailsNote.textContent = `“${row.dataset.note}”`;

    if (detailsActions) {
        detailsActions.hidden = row.dataset.status !== 'pending';
        const approveForm = detailsActions.querySelector('form[action*="Approved"]');
        const rejectForm = detailsActions.querySelector('form[action*="Rejected"]');
        const id = row.dataset.id;
        if (id && approveForm) {
            approveForm.action = approveForm.action.replace(/\/leaves\/\d+\//, `/leaves/${id}/`);
        }
        if (id && rejectForm) {
            rejectForm.action = rejectForm.action.replace(/\/leaves\/\d+\//, `/leaves/${id}/`);
        }
    }
};

const applyFilter = (filter) => {
    const visibleRows = [];

    requestRows.forEach((row) => {
        const isVisible = matchesFilter(row.dataset.status, filter);

        row.hidden = !isVisible;

        if (isVisible) {
            visibleRows.push(row);
        }
    });

    filterButtons.forEach((button) => {
        const isActive = button.dataset.leaveFilter === filter;

        button.classList.toggle('active', isActive);
        button.setAttribute('aria-pressed', String(isActive));
    });

    if (emptyRow) {
        emptyRow.hidden = visibleRows.length > 0;
    }

    updateDetails(visibleRows[0] || null);
};

filterButtons.forEach((button) => {
    button.addEventListener('click', () => applyFilter(button.dataset.leaveFilter));
});

requestRows.forEach((row) => {
    row.addEventListener('click', (event) => {
        if (event.target.closest('button, form, a')) {
            return;
        }
        updateDetails(row);
    });
});

if (filterButtons.length && requestRows.length) {
    applyFilter(document.querySelector('[data-leave-filter].active')?.dataset.leaveFilter ?? 'pending');
}
