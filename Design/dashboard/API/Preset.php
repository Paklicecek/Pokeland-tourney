<?php     
    $username = 'Paklicek';
    $API = include __DIR__ . '../../../../config/api.php';
    $apiKey = $API['apiKey'];
    $tournamentIdentifier = 'myTest92521';  
    

    $url = "https://api.challonge.com/v1/tournaments/{$tournamentIdentifier}.json";
    
    $data = [
        'tournament[name]' => $preset_title,
        'tournament[description]' => $preset_rules
    ];
        // 'tournament[url]' => 'updated-tournament-url', přidat později s počítadlem na číslo turnaje
        // 'tournament[start_at]' => '2024-12-31T12:00:00Z', přidat později s automatickým ukazováním času

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
?> 
