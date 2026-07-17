/**
 * Schedule UI interactions (modal open/close, form demo submit).
 * Backend endpoints (placeholders):
 *   POST /api/schedule  — create shift
 *   GET  /api/schedule  — list shifts
 */

function initShiftModal() {
    const modal = document.getElementById('add-shift-modal');
    if (!modal) return;

    const openers = document.querySelectorAll('[data-open-shift-modal]');
    const closers = document.querySelectorAll('[data-close-shift-modal]');
    const form = document.getElementById('add-shift-form');

    const open = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    };

    openers.forEach((btn) => btn.addEventListener('click', (e) => {
        e.preventDefault();
        open();
    }));

    closers.forEach((btn) => btn.addEventListener('click', close));

    modal.addEventListener('click', (e) => {
        if (e.target === modal) close();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            close();
        }
    });

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        // Demo only — wire to POST /api/schedule when backend is ready
        close();
        window.alert('Shift created (demo). Connect to POST /api/schedule when backend is ready.');
    });
}

function initCreateShiftForm() {
    const form = document.getElementById('create-shift-form');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        window.alert('Shift saved (demo). Connect to POST /api/schedule when backend is ready.');
        window.location.href = form.dataset.redirect || '/schedule';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initShiftModal();
    initCreateShiftForm();
});
