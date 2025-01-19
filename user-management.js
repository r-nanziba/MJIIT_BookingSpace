// Global variables to track current state
let allUsers = [];
let filteredUsers = [];
let activeFilters = [];

// Function to initialize user data from the table
function initializeUserData() {
    const rows = document.querySelectorAll('table tbody tr');
    allUsers = Array.from(rows).map(row => ({
        element: row,
        name: row.querySelector('strong').textContent.trim(),
        email: row.querySelector('td div br + text').textContent.trim(),
        access: Array.from(row.querySelectorAll('.access-tag')).map(tag => tag.textContent.trim()),
        lastActive: row.querySelectorAll('td')[3].textContent.trim(),
        dateAdded: row.querySelectorAll('td')[4].textContent.trim()
    }));
    filteredUsers = [...allUsers];
}

// Function to apply search filter
function applySearch(searchTerm) {
    const normalizedSearch = searchTerm.toLowerCase().trim();
    filteredUsers = allUsers.filter(user => 
        user.name.toLowerCase().includes(normalizedSearch) || 
        user.email.toLowerCase().includes(normalizedSearch)
    );
    updateTableDisplay();
}

// Function to apply access level filters
function applyAccessFilters(selectedFilters) {
    activeFilters = selectedFilters;
    
    if (selectedFilters.length === 0) {
        filteredUsers = [...allUsers];
    } else {
        filteredUsers = allUsers.filter(user => 
            selectedFilters.every(filter => 
                user.access.some(access => access.toLowerCase().replace(' ', '') === filter)
            )
        );
    }
    
    updateTableDisplay();
    updateFilterButtonState();
}

// Function to update table display
function updateTableDisplay() {
    const tbody = document.querySelector('table tbody');
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    // Add filtered rows back
    filteredUsers.forEach(user => {
        tbody.appendChild(user.element);
    });
    
    // Update user count
    const userCountSpan = document.querySelector('.user-count');
    userCountSpan.textContent = filteredUsers.length;
    
    // Update pagination
    updatePagination();
}

// Function to update pagination
function updatePagination() {
    const paginationContainer = document.querySelector('.pagination');
    const totalPages = Math.ceil(filteredUsers.length / 8); // Assuming 8 users per page
    const currentPage = 1;
    
    paginationContainer.innerHTML = `
        <button class="prev-btn" ${currentPage === 1 ? 'disabled' : ''}>&lt;</button>
        <span>${currentPage}</span>
        <span>of</span>
        <span>${totalPages}</span>
        <button class="next-btn" ${currentPage === totalPages ? 'disabled' : ''}>&gt;</button>
    `;
}

// Function to update filter button state
function updateFilterButtonState() {
    const filterBtn = document.querySelector('.filter-btn');
    const filterCount = activeFilters.length;
    
    if (filterCount > 0) {
        filterBtn.classList.add('active');
        
        // Remove existing badge if it exists
        const existingBadge = filterBtn.querySelector('.filter-count');
        if (existingBadge) {
            existingBadge.remove();
        }
        
        // Add filter count badge
        const badge = document.createElement('span');
        badge.classList.add('filter-count');
        badge.textContent = filterCount;
        filterBtn.appendChild(badge);
    } else {
        filterBtn.classList.remove('active');
    }
}

// Function to create filter modal
function createFilterModal() {
    const modalContainer = document.createElement('div');
    modalContainer.id = 'filterModal';
    modalContainer.classList.add('modal');
    
    modalContainer.innerHTML = `
        <div class="modal-content filter-modal-content">
            <span class="close">&times;</span>
            <h2>Filter Users</h2>
            <div class="filter-section">
                <h3>Access Levels</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="filterAdmin" value="admin">
                        <label for="filterAdmin">Admin</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filterDataImport" value="dataimport">
                        <label for="filterDataImport">Data Import</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filterDataExport" value="dataexport">
                        <label for="filterDataExport">Data Export</label>
                    </div>
                </div>
            </div>
            <div class="filter-actions">
                <button class="clear-filters">Clear Filters</button>
                <button class="apply-filters">Apply Filters</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modalContainer);
    
    // Close modal functionality
    const closeBtn = modalContainer.querySelector('.close');
    closeBtn.onclick = () => modalContainer.style.display = 'none';
    
    // Apply filters functionality
    const applyFiltersBtn = modalContainer.querySelector('.apply-filters');
    applyFiltersBtn.onclick = () => {
        const selectedFilters = Array.from(
            modalContainer.querySelectorAll('input[type="checkbox"]:checked')
        ).map(checkbox => checkbox.value);
        
        applyAccessFilters(selectedFilters);
        modalContainer.style.display = 'none';
    };
    
    // Clear filters functionality
    const clearFiltersBtn = modalContainer.querySelector('.clear-filters');
    clearFiltersBtn.onclick = () => {
        const checkboxes = modalContainer.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        applyAccessFilters([]);
        modalContainer.style.display = 'none';
    };
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modalContainer) {
            modalContainer.style.display = 'none';
        }
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize user data
    initializeUserData();
    
    // Create filter modal
    createFilterModal();
    
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    const searchBtn = document.querySelector('.search-btn');
    
    searchInput.addEventListener('input', function() {
        applySearch(this.value);
    });
    
    searchBtn.addEventListener('click', function() {
        applySearch(searchInput.value);
    });
    
    // Filter button functionality
    const filterBtn = document.querySelector('.filter-btn');
    const filterModal = document.getElementById('filterModal');
    
    filterBtn.addEventListener('click', function() {
        filterModal.style.display = 'block';
    });
});