<?php
// quick fixed script — save as report_winner.php and run on your server
$apiKey     = 'ugHndIRihqjlBC7up0wqw7KVU2LjN1nD1YwRrEjj';
$tournament = 'myTest92521';   // keep your slug here (or 'cs-myTest92521' if you use subdomain format)
$playerA    = 'Bordel';
$playerB    = 'Bordelindi';

// Helper: unified curl wrapper (fixed GET handling)
function callChallonge($method, $url, $fields = []) {
    $method = strtoupper($method);

    // For GET/DELETE: put fields in the query string
    if (($method === 'GET' || $method === 'DELETE') && !empty($fields)) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($fields);
    }

    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
    ];

    // For POST/PUT/PATCH: send form-encoded body
    if (in_array($method, ['POST','PUT','PATCH'], true)) {
        $opts[CURLOPT_POSTFIELDS] = http_build_query($fields);
        $opts[CURLOPT_HTTPHEADER]  = ['Content-Type: application/x-www-form-urlencoded'];
    } else {
        // Ensure GET uses proper flag
        if ($method === 'GET') $opts[CURLOPT_HTTPGET] = true;
    }

    curl_setopt_array($ch, $opts);
    $out  = curl_exec($ch);
    $errn = curl_errno($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($out === false || $errn) {
        throw new RuntimeException("cURL error ($errn): $err");
    }
    if ($code >= 400) {
        // return full body for easier diagnosis
        throw new RuntimeException("HTTP $code: $out");
    }

    $json = json_decode($out, true);
    return (json_last_error() === JSON_ERROR_NONE) ? $json : $out;
}

// 1) Get participants (map names -> ids)
$participants = callChallonge(
    'GET',
    "https://api.challonge.com/v1/tournaments/$tournament/participants.json",
    ['api_key' => $apiKey]
);

$idsByName = [];
foreach ($participants as $p) {
    $idsByName[$p['participant']['name']] = $p['participant']['id'];
}
$aid = $idsByName[$playerA] ?? null;
$bid = $idsByName[$playerB] ?? null;
if (!$aid || !$bid) { throw new RuntimeException('Could not resolve participant IDs for A/B'); }

// 2) Find the A vs B match — use state=all to be safe
$matches = callChallonge(
    'GET',
    "https://api.challonge.com/v1/tournaments/$tournament/matches.json",
    ['api_key' => $apiKey, 'state' => 'all']
);

$matchId = null;
foreach ($matches as $m) {
    $mm = $m['match'];
    $players = [$mm['player1_id'], $mm['player2_id']];
    if (in_array($aid, $players, true) && in_array($bid, $players, true)) {
        $matchId = $mm['id'];
        break;
    }
}
if (!$matchId) { throw new RuntimeException('Could not find the A vs B match'); }

// 3) Report B as winner
$update = callChallonge(
    'PUT',
    "https://api.challonge.com/v1/tournaments/$tournament/matches/$matchId.json",
    [
        'api_key'           => $apiKey,
        'match[winner_id]'  => $bid,
        'match[scores_csv]' => '1-0',
    ]
);

echo "Advanced Player B. Match updated: ID {$update['match']['id']}\n";

?>¨
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>bordel</p>
</body>
</html>