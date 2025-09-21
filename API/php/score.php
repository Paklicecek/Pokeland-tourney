<!--
Does not work. How to solve? make a super hard crazy system to get match ids with player ids and match their names for wins
<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'winner') {
    echo "PHP script executed successfully!";

    $username = 'Paklicek';
    $apiKey   = 'MdyRyn07cUcui1creZrt1MWt15eLynHJHLJ0hcRK';
    $tournamentIdentifier = 'myTest92521';  
    $matchID = "427552201";

    $url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/matches/{$matchID}.json";
    
    $winnerId = "273252563";
    $data = [
        'match[scores_csv]' => '1-0',
        'match[winner_id]' => $winnerId
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    ]);


    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
} else {
    echo "Invalid request.";
}
?> -->
