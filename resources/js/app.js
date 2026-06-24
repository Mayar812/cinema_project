// Seat selection for the booking page.
// Booked seats are rendered by the server (data-state="booked" + disabled);
// this script only handles choosing one of the remaining open seats.
function initSeatMap(map) {
    const form = map.closest('form');

    if (!form) {
        return;
    }

    // Hidden input that carries the chosen seat with the form submission.
    const seatInput = form.querySelector('[data-seat-input]');

    // Label that shows the currently selected seat to the user.
    const selectedLabel = form.querySelector('[data-selected-seat-label]');

    // Every clickable seat button in the grid.
    const seatButtons = [...map.querySelectorAll('[data-seat]')];

    if (!seatInput || seatButtons.length === 0) {
        return;
    }

    // Chair-type selector decides which seats are eligible (VIP vs Premium).
    const chairSelect = form.querySelector('[name="chair_type"]');

    const normalizeSeat = (value) => (value || '').trim().toUpperCase();

    // Enable only seats whose type matches the chosen chair; dim the rest.
    // Booked seats stay disabled regardless.
    const applyChairType = () => {
        const chairType = chairSelect ? chairSelect.value : null;

        seatButtons.forEach((button) => {
            if (button.dataset.booked === 'true') {
                return;
            }

            const matches = !chairType || button.dataset.seatType === chairType;
            button.dataset.mismatch = matches ? 'false' : 'true';
            button.disabled = !matches;
        });

        // Drop the current selection if it no longer matches the chair type.
        const selectedSeat = normalizeSeat(seatInput.value);
        if (selectedSeat) {
            const selectedButton = seatButtons.find(
                (button) => normalizeSeat(button.dataset.seat) === selectedSeat
            );

            if (selectedButton && selectedButton.dataset.mismatch === 'true') {
                seatInput.value = '';
            }
        }

        render();
    };

    // Repaint the grid so the chosen seat looks "selected" and update the label.
    const render = () => {
        const selectedSeat = normalizeSeat(seatInput.value);

        seatButtons.forEach((button) => {
            // Never touch seats that are already booked.
            if (button.dataset.state === 'booked') {
                return;
            }

            const isSelected = normalizeSeat(button.dataset.seat) === selectedSeat;
            button.dataset.state = isSelected ? 'selected' : 'available';
            button.setAttribute('aria-pressed', String(isSelected));
        });

        if (selectedLabel) {
            selectedLabel.textContent = selectedSeat || 'Choose below';
        }
    };

    seatButtons.forEach((button) => {
        button.addEventListener('click', () => {
            // Booked or wrong-type seats are disabled, but guard anyway.
            if (button.dataset.state === 'booked' || button.dataset.mismatch === 'true' || button.disabled) {
                return;
            }

            seatInput.value = normalizeSeat(button.dataset.seat);
            render();
        });
    });

    if (chairSelect) {
        chairSelect.addEventListener('change', applyChairType);
    }

    // Sync the grid with the chair type selected on load.
    applyChairType();
}

// Live total in the sidebar: ticket price + chair surcharge + snack fee.
function initPriceSummary(summary) {
    const totalEl = summary.querySelector('[data-price-total]');

    if (!totalEl) {
        return;
    }

    const ticketPrice = parseFloat(summary.dataset.ticketPrice) || 0;
    const snackFee = parseFloat(summary.dataset.snackFee) || 0;

    let chairFees = {};
    try {
        chairFees = JSON.parse(summary.dataset.chairFees || '{}');
    } catch (error) {
        chairFees = {};
    }

    const chairSelect = document.querySelector('[name="chair_type"]');
    const snackSelect = document.querySelector('[name="snacks"]');

    const update = () => {
        const chairType = chairSelect ? chairSelect.value : null;
        const chairFee = chairType && chairFees[chairType] != null ? Number(chairFees[chairType]) : 0;
        const snacks = snackSelect ? snackSelect.value : 'None';
        const snacksFee = snacks && snacks !== 'None' ? snackFee : 0;

        const total = ticketPrice + chairFee + snacksFee;
        totalEl.textContent = `$${total.toFixed(2)}`;
    };

    if (chairSelect) {
        chairSelect.addEventListener('change', update);
    }

    if (snackSelect) {
        snackSelect.addEventListener('change', update);
    }

    update();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-seat-map]').forEach(initSeatMap);
    document.querySelectorAll('[data-price-summary]').forEach(initPriceSummary);
});
