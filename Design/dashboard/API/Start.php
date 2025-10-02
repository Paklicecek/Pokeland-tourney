<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start') {
    echo "PHP script executed successfully!";
    $_SESSION['Response'] = true;
    
    $username = 'Paklicek';
    $API = include __DIR__ . '../../../../config/api.php';
    $apiKey = $API['apiKey'];
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

$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    }
    else{
        $res = $conn->query("SELECT 'counter'");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $titles[] = $row['title'];
            }
            $res->free();
        }
    }
    $conn->close();

} else {
    echo "Invalid request.";
}
?>
