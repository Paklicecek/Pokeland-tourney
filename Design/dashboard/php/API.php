<?php

$username = 'Paklicek';
$API = include __DIR__ . '../../../../config/api.php';
$apiKey = $API['apiKey'];
$tournamentIdentifier = 'myTest92521';  
$url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/participants.json";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);


$data = json_decode($response);

if ($data && isset($_SESSION['Names'])) {

    function mapParticipantIdsToNames($apiData, $sessionNames) {
        $mapping = [];
        $participantIds = [];
        
        foreach ($apiData as $participant) {
            $participantIds[] = $participant->participant->id;
        }
        
        for ($i = 0; $i < count($participantIds) && $i < count($sessionNames); $i++) {
            $mapping[$participantIds[$i]] = $sessionNames[$i];
        }
        
        return $mapping;
    }
    

    $_SESSION['UserID'] = mapParticipantIdsToNames($data, $_SESSION['Names']);
    $_SESSION['api_success'] = "Participant mapping created successfully!";
} else {
    $_SESSION['api_error'] = "Failed to process API data or no names in session";
}
$ch = null;
$response = null;
$status = null;
$error = null;

$url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}/matches.json";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

$matchesData = json_decode($response);

if ($matchesData) {
    function mapPlayerToMatchId($matchesData, $playerIdMapping) {
    $playerMatchMapping = [];
    
    foreach ($matchesData as $match) {
        $matchId = $match->match->id;
        $player1Id = $match->match->player1_id;
        $player2Id = $match->match->player2_id;
        
        // Initialize array if not exists and add match ID for player1
        if ($player1Id && isset($playerIdMapping[$player1Id])) {
            if (!isset($playerMatchMapping[$player1Id])) {
                $playerMatchMapping[$player1Id] = [
                    'name' => $playerIdMapping[$player1Id],
                    'match_ids' => []
                ];
            }
            $playerMatchMapping[$player1Id]['match_ids'][] = $matchId;
        }
        
        // Same for player2
        if ($player2Id && isset($playerIdMapping[$player2Id])) {
            if (!isset($playerMatchMapping[$player2Id])) {
                $playerMatchMapping[$player2Id] = [
                    'name' => $playerIdMapping[$player2Id],
                    'match_ids' => []
                ];
            }
            $playerMatchMapping[$player2Id]['match_ids'][] = $matchId;
        }
    }
    
    return $playerMatchMapping;
}
    
    // Create the player to match ID mapping
    if (isset($_SESSION['UserID'])) {
        $_SESSION['PlayerMatchID'] = mapPlayerToMatchId($matchesData, $_SESSION['UserID']);
        $_SESSION['matches_success'] = "Player-to-match mapping created successfully!";
    }
} else {
    $_SESSION['matches_error'] = "Failed to get matches data";
}
?>