const navLinks = document.querySelectorAll('.nav-link');

navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        if (link.closest('.logout') || link.getAttribute('href') !== '#') {
            return; 
        }
        
        e.preventDefault();
        
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
    });
});

document.querySelectorAll('.control-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        
        btn.style.transform = 'translateY(-2px) scale(0.98)';
        setTimeout(() => {
            btn.style.transform = '';
        }, 150);
    });
});