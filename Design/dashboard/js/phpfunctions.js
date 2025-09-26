document.querySelector('.start').addEventListener('click', function() {
    fetchAndUpdate('../API/Start.php', { action: 'start' });
});

document.querySelector('.end').addEventListener('click', function() {
    fetchAndUpdate('../API/End.php', { action: 'end' });
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
document.querySelector('.preset').addEventListener('click', function() {
    fetchAndUpdate('../API/Preset.php', { action: 'preset' });
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
        }, 700);
    } catch (error) {
        console.error('Error:', error);
        const resultElement = document.getElementById('result');
        if (resultElement) {
            resultElement.innerHTML = 'An error occurred.';
        }
    }
}