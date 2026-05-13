// [WEEK 7: APIs & External Data Handling]
const eventGrid = document.getElementById('event-grid');
const searchInput = document.getElementById('search-input');
const filterButtons = document.querySelectorAll('.filter-btn');
const rsvpModal = document.getElementById('rsvpModal');
const rsvpForm = document.getElementById('rsvpForm');

let allEvents = [];

// Fetch Events
async function fetchEvents() {
    try {
        const response = await fetch('api/events.php');
        if (!response.ok) throw new Error('Network error');
        allEvents = await response.json();
        renderEvents(allEvents);
    } catch (error) {
        eventGrid.innerHTML = "<p>Failed to load events.</p>";
    }
}

// Render with Artsy Cards
function renderEvents(events) {
    eventGrid.innerHTML = '';
    if (events.length === 0) {
        eventGrid.innerHTML = "<p>No events found.</p>";
        return;
    }

    events.forEach(event => {
        const card = document.createElement('div');
        card.className = 'event-card';
        const imgSrc = event.image_path ? event.image_path : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80'; // Artsy fallback image

        card.innerHTML = `
            <div class="card-image-wrapper">
                <img src="${imgSrc}" alt="${event.title}" class="event-img">
                <span class="badge">${event.category.toUpperCase()}</span>
            </div>
            <div class="event-content">
                <h3>${event.title}</h3>
                <div class="event-meta">
                    <span>📅 ${formatDate(event.event_date)}</span>
                    <span>📍 ${event.location}</span>
                </div>
                <p class="event-desc">${event.description}</p>
                <button class="rsvp-btn" data-id="${event.id}" onclick="openModal(${event.id}, this)">Reserve Your Seat</button>
            </div>
        `;
        eventGrid.appendChild(card);
    });
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

// --- MODAL & RSVP LOGIC ---
function openModal(eventId, btnElement) {
    if(btnElement.classList.contains('rsvped')) return; // Already RSVPed
    document.getElementById('modalEventId').value = eventId;
    rsvpModal.classList.add('active');
}

function closeModal() {
    rsvpModal.classList.remove('active');
}

// Close modal if clicking outside the box
window.onclick = function(e) {
    if (e.target == rsvpModal) closeModal();
}

// Handle RSVP Form Submission via API
rsvpForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(rsvpForm);
    const eventId = document.getElementById('modalEventId').value;

    try {
        const response = await fetch('api/rsvp.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if(result.success) {
            // Find the button and change its state
            const btn = document.querySelector(`.rsvp-btn[data-id="${eventId}"]`);
            btn.innerText = "✓ You are going!";
            btn.classList.add('rsvped');
            closeModal();
            rsvpForm.reset();
        } else {
            alert(result.message); // Shows duplicate error
        }
    } catch (error) {
        alert("Failed to connect to server.");
    }
});

// --- SEARCH & FILTER LOGIC ---
searchInput.addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    const filtered = allEvents.filter(ev => 
        ev.title.toLowerCase().includes(term) || ev.location.toLowerCase().includes(term)
    );
    renderEvents(filtered);
});

filterButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        filterButtons.forEach(b => b.classList.remove('active'));
        e.target.classList.add('active');
        const cat = e.target.dataset.category;
        renderEvents(cat === 'All' ? allEvents : allEvents.filter(ev => ev.category === cat));
    });
});

document.addEventListener('DOMContentLoaded', fetchEvents);