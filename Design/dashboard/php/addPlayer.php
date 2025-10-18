<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-player']) && !empty($_POST['add-player'])) {
    $_SESSION['Response'] = true;

    $usernameAPI = 'Paklicek';
    $API = include __DIR__ . '../../../../config/api.php';
    $apiKey = $API['apiKey'];
    $tournamentIdentifier = 'myTest92521';  
    $url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/participants.json";
    $name = $_POST['add-player']; 
    $data = ['participant' => ['name' => $name]];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $usernameAPI . ':' . $apiKey);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
    else {
        $stmt = $conn->prepare("INSERT INTO Players (Name) VALUES (?)");
        if($stmt){
            $stmt->bind_param('s', $name);
            if ($stmt->execute()) {
                $success_message = "Inserted new preset. Insert ID: " . $conn->insert_id;
                $_SESSION['Response'] = true;
                header('Location: dashboard.php');
                exit();
            }
            else {
                $_SESSION['Response'] = false;
            }
            $stmt->close();
        }
    }
    $conn->close();

} else {
    echo "Invalid request.";
}
?>
