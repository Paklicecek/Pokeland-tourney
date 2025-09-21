<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start') {
    echo "PHP script executed successfully!";

    $username = 'Paklicek';
    $apiKey   = 'MdyRyn07cUcui1creZrt1MWt15eLynHJHLJ0hcRK';
    $tournamentIdentifier = 'myTest92521';  
    
    $url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/start.json";
    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);


$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);
} else {
    echo "Invalid request.";
}
?>
