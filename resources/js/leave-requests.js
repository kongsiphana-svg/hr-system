const filterButtons = document.querySelectorAll('[data-leave-filter]');
const requestRows = document.querySelectorAll('[data-leave-request-row]');
const emptyRow = document.querySelector('[data-leave-empty]');
const detailsPanel = document.querySelector('[data-leave-details]');
const detailsTitle = document.querySelector('[data-leave-details-title]');
const detailsNote = document.querySelector('[data-leave-details-note]');

/**
 * Checks whether a leave request status belongs to the selected filter.
 * The history filter includes every request that is no longer pending.
 */
const matchesFilter = (status, filter) => {
    if (filter === 'history') {
        return status !== 'pending';
    }

    return status === filter;
};

/**
 * Shows details for the first visible request, or hides the details panel
 * when the current filter has no matching requests.
 */
const updateDetails = (visibleRows) => {
    const firstVisibleRow = visibleRows[0];

    if (!detailsPanel || !detailsTitle || !detailsNote) {
        return;
    }

    detailsPanel.hidden = !firstVisibleRow;

    if (!firstVisibleRow) {
        return;
    }

    detailsTitle.textContent = `Request Details: ${firstVisibleRow.dataset.employee}`;
    detailsNote.textContent = `“${firstVisibleRow.dataset.note}”`;
};

/**
 * Filters the request rows, updates the active filter button and empty state,
 * then refreshes the request details panel.
 */
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

    updateDetails(visibleRows);
};

// Applies the selected filter whenever a filter button is clicked.
filterButtons.forEach((button) => {
    button.addEventListener('click', () => applyFilter(button.dataset.leaveFilter));
});

// Displays the active filter on initial page load, defaulting to pending requests.
if (filterButtons.length && requestRows.length) {
    applyFilter(document.querySelector('[data-leave-filter].active')?.dataset.leaveFilter ?? 'pending');
}
