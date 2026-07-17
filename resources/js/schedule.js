/**
 * Schedule UI — modal + create/delete wired to /schedules.
 */

function csrfToken() {
    const app = document.getElementById('schedule-app');
    return app?.dataset.csrf
        || document.querySelector('meta[name="csrf-token"]')?.content
        || '';
}

function createUrl() {
    return document.getElementById('schedule-app')?.dataset.createUrl || '/schedules';
}

async function postShift(form) {
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    const response = await fetch(form.action || createUrl(), {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
    });

    if (!response.ok) {
        let message = 'Could not create shift.';
        try {
            const data = await response.json();
            if (data.errors) {
                message = Object.values(data.errors).flat().join(' ');
            } else if (data.message) {
                message = data.message;
            }
        } catch (_) {
            // keep default
        }
        throw new Error(message);
    }

    return response;
}

async function deleteShift(id) {
    const response = await fetch(`${createUrl()}/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok && response.status !== 204) {
        throw new Error('Could not delete shift.');
    }
}

function initShiftModal() {
    const modal = document.getElementById('add-shift-modal');
    if (!modal) return;

    const openers = document.querySelectorAll('[data-open-shift-modal]');
    const closers = document.querySelectorAll('[data-close-shift-modal]');
    const form = document.getElementById('add-shift-form');
    const errorEl = form?.querySelector('[data-shift-form-error]');

    const open = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }
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

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }

        try {
            await postShift(form);
            close();
            window.location.reload();
        } catch (err) {
            if (errorEl) {
                errorEl.textContent = err.message || 'Could not create shift.';
                errorEl.classList.remove('hidden');
            } else {
                window.alert(err.message || 'Could not create shift.');
            }
        }
    });
}

function initCreateShiftForm() {
    const form = document.getElementById('create-shift-form');
    if (!form) return;

    // Native form submit to schedules.store — only intercept if explicitly ajax
    if (form.dataset.ajax === 'true') {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                await postShift(form);
                window.location.href = form.dataset.redirect || '/schedule';
            } catch (err) {
                window.alert(err.message || 'Could not save shift.');
            }
        });
    }
}

function initDeleteButtons() {
    document.querySelectorAll('[data-delete-schedule]').forEach((button) => {
        button.addEventListener('click', async () => {
            const id = button.getAttribute('data-delete-schedule');
            if (!id || !window.confirm('Delete this shift?')) return;

            try {
                await deleteShift(id);
                button.closest('tr')?.remove();
            } catch (err) {
                window.alert(err.message || 'Could not delete shift.');
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initShiftModal();
    initCreateShiftForm();
    initDeleteButtons();
});
