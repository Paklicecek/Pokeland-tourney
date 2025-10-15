document.querySelector('.start').addEventListener('click', function() {
    fetchAndUpdate('../API/Start.php', { action: 'start' });
});

document.querySelector('.removeDB').addEventListener('click', function() {
    fetchAndUpdate('../API/removeDB.php', { action: 'removeDB' });
});

document.querySelector('.matches').addEventListener('click', function() {
    fetchAndUpdate('../API/Generate.php', { action: 'matches' });
});

document.querySelector('.reset').addEventListener('click', function() {
    fetchAndUpdate('../API/Reset.php', { action: 'reset' });
});

document.querySelector('.remove').addEventListener('click', function() {
    fetchAndUpdate('../API/Remove.php', { action: 'remove' });
});

document.querySelector('.shuffle').addEventListener('click', function() {
    fetchAndUpdate('../API/Shuffle.php', { action: 'shuffle' });
});

document.querySelector('.submit-player').addEventListener('click', function(e) {
    e.preventDefault(); // Stop the form from submitting
    
    const form = this.closest('form');
    const formData = new FormData(form);
    const winner = formData.get('winner');
    
    if (!winner) {
        alert('Please select a player first');
        return;
    }
    
    // First, manually submit to save in session
    fetch(`dashboard.php?winner=${encodeURIComponent(winner)}`)
        .then(() => {
            // Then call winner.php
            return fetchAndUpdate('winner.php', { action: 'report_winner' });
        });
});

async function fetchAndUpdate(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(data)
        });
        const result = await response.text();
        
        const resultElement = document.getElementById('result');
        if (resultElement) {
            resultElement.innerHTML = result;
        } else {
            console.log('Result:', result); 
        }
        
        setTimeout(() => {
            location.reload();
        }, 2000);
    } catch (error) {
        console.error('Error:', error);
        const resultElement = document.getElementById('result');
        if (resultElement) {
            resultElement.innerHTML = 'An error occurred.';
        }
    }
}