// Student Portal JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeNavigation();
    initializeSearch();
    initializePostCards();
});

// Navigation functionality
function initializeNavigation() {
    const navButtons = document.querySelectorAll('.nav-btn');

    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            navButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Get the category
            const category = this.getAttribute('data-category');

            // Update page content based on category
            updatePageContent(category);
        });
    });
}

// Update page content based on selected category
function updatePageContent(category) {
    const pageTitle = document.querySelector('.page-title');
    const searchInput = document.querySelector('.search-input');

    if (category === 'students') {
        pageTitle.textContent = 'Students';
        searchInput.placeholder = 'Search students...';
        loadStudentsContent();
    } else if (category === 'stuff') {
        pageTitle.textContent = 'Stuff';
        searchInput.placeholder = 'Search stuff...';
        loadStuffContent();
    }
}

// Load students content
function loadStudentsContent() {
    const postsGrid = document.querySelector('.posts-grid');

    const studentsContent = `
        <div class="post-card">
            <div class="post-image">
                <img src="https://i.pinimg.com/736x/fe/e8/d7/fee8d7bf21002f104adc21dd78139e6b.jpg" alt="Register Admissions">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Register<br>Admissions</h4>
                        <p class="post-subtitle">Invest into your future<br>Learn UHU</p>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://southstinger.com/wp-content/uploads/2020/08/Brazil_mens_football_team_2016_Olympics-900x599.jpg" alt="Sports Team">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Sports tutor<br>League match</h4>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://www.pace.edu/sites/default/files/styles/16_9_1600x900/public/2023-02/commencement-interior-hero-ceremony.jpg?h=993b43e0&itok=1QMHrqV6" alt="Graduation Ceremony">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Graduation<br>Ceremony</h4>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://www.sandiego.edu/dining/images/site-banner-1.jpg" alt="Campus Dining">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Campus<br>Dining</h4>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>
    `;

    postsGrid.innerHTML = studentsContent;
    initializePostCards();
}

// Load stuff content
function loadStuffContent() {
    const postsGrid = document.querySelector('.posts-grid');

    const stuffContent = `
        <div class="post-card">
            <div class="post-image">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400" alt="Office Supplies">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Office<br>Supplies</h4>
                        <p class="post-subtitle">Essential items for<br>your studies</p>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400" alt="Books">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Text<br>Books</h4>
                        <p class="post-subtitle">Academic resources<br>and materials</p>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400" alt="Laptops">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Tech<br>Equipment</h4>
                        <p class="post-subtitle">Laptops and<br>accessories</p>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>

        <div class="post-card">
            <div class="post-image">
                <img src="https://images.unsplash.com/photo-1434494878577-86c23bcb06b9?w=400" alt="Furniture">
                <div class="post-overlay">
                    <div class="post-content">
                        <h4 class="post-title">Dorm<br>Furniture</h4>
                        <p class="post-subtitle">Comfortable living<br>essentials</p>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <a href="#" class="view-details-btn">View details</a>
            </div>
        </div>
    `;

    postsGrid.innerHTML = stuffContent;
    initializePostCards();
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchIcon = document.querySelector('.search-icon');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterPosts(searchTerm);
    });

    searchIcon.addEventListener('click', function() {
        searchInput.focus();
    });

    // Handle Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch(this.value);
        }
    });
}

// Filter posts based on search term
function filterPosts(searchTerm) {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(card => {
        const title = card.querySelector('.post-title').textContent.toLowerCase();
        const subtitle = card.querySelector('.post-subtitle');
        const subtitleText = subtitle ? subtitle.textContent.toLowerCase() : '';

        if (title.includes(searchTerm) || subtitleText.includes(searchTerm)) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.3s ease';
        } else {
            card.style.display = 'none';
        }
    });
}

// Perform search action
function performSearch(searchTerm) {
    if (searchTerm.trim()) {
        console.log('Searching for:', searchTerm);
        // Here you would typically make an API call or filter results
        showNotification(`Searching for "${searchTerm}"...`);
    }
}

// Initialize post card interactions
function initializePostCards() {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(card => {
        const viewDetailsBtn = card.querySelector('.view-details-btn');

        viewDetailsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const title = card.querySelector('.post-title').textContent.replace('\n', ' ');
            showPostDetails(title);
        });

        // Add click event to entire card
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.view-details-btn')) {
                const title = card.querySelector('.post-title').textContent.replace('\n', ' ');
                showPostDetails(title);
            }
        });
    });
}

// Show post details
function showPostDetails(title) {
    showNotification(`Opening details for: ${title}`);
    // Here you would typically navigate to a details page or open a modal
}

// Floating add button functionality
function showAddDialog() {
    const currentCategory = document.querySelector('.nav-btn.active').getAttribute('data-category');

    if (currentCategory === 'students') {
        showNotification('Add new student post');
    } else {
        showNotification('Add new stuff post');
    }

    // Here you would typically open a modal or form to add new content
}

// Notification system
function showNotification(message) {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #333;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        z-index: 2000;
        animation: slideIn 0.3s ease;
        font-size: 14px;
        max-width: 300px;
    `;

    // Add animation keyframes
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => notification.remove(), 300);
        }
    }, 3000);
}

// Bell notification click
document.addEventListener('click', function(e) {
    if (e.target.closest('.notification-bell')) {
        showNotification('You have 3 new notifications');
    }
});

// Smooth scrolling for view all link
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-all-link')) {
        e.preventDefault();
        showNotification('Loading all posts...');
    }
});

// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.post-card');
    cards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.5s ease ${index * 0.1}s both`;
    });
});
