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

// Generic dropdown functionality
function initializeDropdown(selectorId, dropdownId, selectedTextId, options, confirmBtnId) {
    const selector = document.getElementById(selectorId);
    const dropdown = document.getElementById(dropdownId);
    const selectedText = document.getElementById(selectedTextId);
    const dropdownOptions = dropdown.querySelectorAll('.dropdown-option');
    const confirmBtn = document.getElementById(confirmBtnId);

    // Toggle dropdown
    selector.addEventListener('click', (e) => {
        e.stopPropagation();
        const isActive = selector.classList.contains('active');
        
        // Close any other open dropdowns first
        document.querySelectorAll('.select-btn.active').forEach(btn => {
            if (btn !== selector) btn.classList.remove('active');
        });
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            if (menu !== dropdown) menu.classList.remove('show');
        });
        
        if (!isActive) {
            selector.classList.add('active');
            dropdown.classList.add('show');
        } else {
            selector.classList.remove('active');
            dropdown.classList.remove('show');
        }
    });

    // Handle option selection
    dropdownOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Remove selected class from all options in this dropdown
            dropdownOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            option.classList.add('selected');
            
            // Update the displayed text
            selectedText.textContent = option.textContent.trim();
            
            // Close dropdown
            selector.classList.remove('active');
            dropdown.classList.remove('show');
            
        });
    });

    // Confirm button functionality
    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {
            const selectedOption = dropdown.querySelector('.dropdown-option.selected');
            if (selectedOption) {
                
                // Add visual feedback
                confirmBtn.style.transform = 'translateY(-1px) scale(0.98)';
                confirmBtn.textContent = '✓ Applied!';
                setTimeout(() => {
                    location.reload()
                }, 1000);
                
            }
        });
    }
}

// Initialize both dropdowns
initializeDropdown('presetSelector', 'presetDropdown', 'selectedPreset', null, 'presetConfirmBtn');
initializeDropdown('playerSelector', 'playerDropdown', 'selectedPlayer', null, 'playerConfirmBtn');

// Close dropdowns when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.select-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
    });
});

// Prevent dropdown from closing when clicking inside it
document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
    dropdown.addEventListener('click', (e) => {
        e.stopPropagation();
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
