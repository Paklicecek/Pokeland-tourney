// Navigation functionality
const navLinks = document.querySelectorAll('.nav-link');

navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        // Don't prevent default for logout or external links
        if (link.closest('.logout') || link.getAttribute('href') !== '#') {
            return; // Allow the link to work normally
        }
        
        e.preventDefault();
        
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
    });
});

// Add click handlers for control buttons
document.querySelectorAll('.control-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Add a subtle feedback animation
        btn.style.transform = 'translateY(-2px) scale(0.98)';
        setTimeout(() => {
            btn.style.transform = '';
        }, 150);
    });
});