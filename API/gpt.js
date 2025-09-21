document.getElementById('delete-players').addEventListener('click', function() {
    fetchAndUpdate('php/remove.php', { action: 'run' });
});

document.getElementById('start-tournament').addEventListener('click', function() {
    fetchAndUpdate('php/start.php', { action: 'start' });
});

document.getElementById('finish-tournament').addEventListener('click', function() {
    fetchAndUpdate('php/finish.php', { action: 'finish' });
});

document.getElementById('reset-tournament').addEventListener('click', function() {
    fetchAndUpdate('php/reset.php', { action: 'reset' });
});

document.getElementById('add-players').addEventListener('click', function() {
    fetchAndUpdate('php/addplayers.php', { action: 'add' });
});

document.getElementById('shuffle-seeds').addEventListener('click', function() {
    fetchAndUpdate('php/shuffleseeds.php', { action: 'shuffle' });
});

document.getElementById('winner').addEventListener('click', function() {
    fetchAndUpdate('php/score.php', { action: 'winner' });
});

document.getElementById('get-users').addEventListener('click', function() {
    fetchAndUpdate('php/getusers.php', { action: 'get' });
});

document.getElementById('get-matches').addEventListener('click', function() {
    fetchAndUpdate('php/matches.php', { action: 'match' });
});

async function fetchAndUpdate(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(data)
        });
        const result = await response.text();
        document.getElementById('result').innerHTML = result;
        // location.reload()
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = 'An error occurred.';
    }
}