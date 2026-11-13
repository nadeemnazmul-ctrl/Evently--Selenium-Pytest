// function fillLogin(email) {
//     document.getElementById('loginEmail').value = email;
//     document.getElementById('loginPassword').value = '123456';
// }
function validateLogin() {
    let email = document.getElementById('loginEmail');
    let password = document.getElementById('loginPassword');

    if (!email.value.trim() || !password.value.trim()) {
        alert('Please enter email and password.');
        return false;
    }

    return true;
}

function validateRegister() {
    let password = document.getElementById('registerPassword');
    let confirmPassword = document.getElementById('confirmPassword');

    if (password && password.value.length < 6) {
        alert('Password must contain at least 6 characters.');
        return false;
    }

    if (
        password &&
        confirmPassword &&
        password.value !== confirmPassword.value
    ) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

const events = [
    [
        'Future Tech Summit 2026',
        'Technology',
        '05 Oct 2026',
        'BICC Dhaka',
        850,
        100
    ],
    [
        'City Lights Music Fest',
        'Music',
        '28 Sep 2026',
        'Dhaka Arena',
        1200,
        150
    ],
    [
        'Dhaka Business Connect',
        'Business',
        '12 Oct 2026',
        'Radisson Blu Dhaka',
        650,
        80
    ],
    [
        'Inter-University Sports Day',
        'Sports',
        '20 Oct 2026',
        'National Stadium',
        400,
        150
    ],
    [
        'Creative Makers Workshop',
        'Workshop',
        '02 Nov 2026',
        'Dhanmondi Studio',
        500,
        40
    ],
    [
        'Startup Connect 2026',
        'Technology',
        '15 Nov 2026',
        'ICT Tower',
        1000,
        100
    ]
];

function filterHomeEvents() {
    let eventGrid = document.getElementById('homeEvents');

    // Stop if the event container does not exist
    if (!eventGrid) {
        return;
    }

    let searchInput = document.getElementById('homeSearch');

    let searchQuery = (
        searchInput.value || ''
    ).toLowerCase();

    let category = document.getElementById('homeCategory').value;

    // Filter events according to search text and category
    let filteredEvents = events.filter(event => {
        let matchesSearch =
            event[0].toLowerCase().includes(searchQuery) ||
            event[3].toLowerCase().includes(searchQuery);

        let matchesCategory =
            category === 'All' || event[1] === category;

        return matchesSearch && matchesCategory;
    });

    // Display filtered events
    eventGrid.innerHTML =
        filteredEvents
            .map(event => `
                <article class="card">
                    <div class="cover">${event[1]}</div>

                    <h3>${event[0]}</h3>

                    <p>
                        ${event[2]} · ${event[3]}
                    </p>

                    <p>
                        Available seats:
                        <b>${event[5]}</b>
                    </p>

                    <strong>
                        ৳${event[4].toLocaleString()}
                    </strong>

                    <br>

                </article>
            `)
            .join('') ||
        '<div class="card">No events found.</div>';
}

function updateTotal() {
    let quantity = document.getElementById('quantity');
    let totalPrice = document.getElementById('totalPrice');
    let unitPrice = document.getElementById('unitPrice');

    if (quantity && totalPrice && unitPrice) {
        let total =
            Number(quantity.value) * Number(unitPrice.value);

        totalPrice.textContent = '৳' + total.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    filterHomeEvents();
    updateTotal();
});

