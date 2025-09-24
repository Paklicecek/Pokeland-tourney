// Navigation functionality
const navLinks = document.querySelectorAll('.nav-link');

navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        
        navLinks.forEach(l => l.classList.remove('active'));
        
        if (!link.closest('.logout')) {
            link.classList.add('active');
        }
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
