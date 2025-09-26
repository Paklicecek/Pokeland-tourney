<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {

    echo "PHP script executed successfully!";

    $username = 'Paklicek';
    $API = include __DIR__ . '../../../../config/api.php';
    $apiKey = $API['apiKey'];
    $tournamentIdentifier = 'myTest92521'; 
    
    $url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/participants/clear.json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
} else {
    echo "Invalid request.";
}
?>
