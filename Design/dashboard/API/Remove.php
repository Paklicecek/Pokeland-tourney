<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {

    echo "PHP script executed successfully!";
    $_SESSION['Response'] = true;

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

$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];
$success_message = "";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("DELETE FROM Players");
        if($stmt){
            if ($stmt->execute()) {
                $affected_rows = $stmt->affected_rows;
                if ($affected_rows > 0) {
                    $success_message .= "<br>Cleared previous active preset.";
                }
            } 
            $stmt->close();
        }
    }
    $conn->close();

} else {
    echo "Invalid request.";
}
?>
