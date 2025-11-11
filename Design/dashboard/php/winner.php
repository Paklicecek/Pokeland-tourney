<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }
    
    if (!isset($_POST["winner"]) || empty($_POST["winner"])) {
        throw new Exception("No winner selected");
    }

    $_SESSION["winner"] = $_POST["winner"];
    $playerName = $_POST["winner"];
    
    $API = include __DIR__ . "../../../../config/api.php";
    if (!$API || !isset($API["apiKey"])) {
        throw new Exception("API configuration not found");
    }
    
    $apiKey = $API["apiKey"];
    $tournament = "myTest92521";

    function callChallonge($method, $url, $fields = []) {
        $method = strtoupper($method);
        if (($method === "GET" || $method === "DELETE") && !empty($fields)) {
            $url .= (strpos($url, "?") === false ? "?" : "&") . http_build_query($fields);
        }

        $ch = curl_init($url);
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        if (in_array($method, ["POST","PUT","PATCH"], true)) {
            $opts[CURLOPT_POSTFIELDS] = http_build_query($fields);
            $opts[CURLOPT_HTTPHEADER]  = ["Content-Type: application/x-www-form-urlencoded"];
        } else {
            if ($method === "GET") $opts[CURLOPT_HTTPGET] = true;
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
            throw new RuntimeException("HTTP $code: $out");
        }

        $json = json_decode($out, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $json : $out;
    }

    $participants = callChallonge(
        "GET",
        "https://api.challonge.com/v1/tournaments/$tournament/participants.json",
        ["api_key" => $apiKey]
    );

    $pid = null;
    foreach ($participants as $p) {
        if (isset($p["participant"]["name"]) && $p["participant"]["name"] === $playerName) {
            $pid = $p["participant"]["id"];
            break;
        }
    }
    
    if (!$pid) {
        throw new RuntimeException("Could not find participant named \"{$playerName}\"");
    }

    $matches = callChallonge(
        "GET",
        "https://api.challonge.com/v1/tournaments/$tournament/matches.json",
        ["api_key" => $apiKey, "state" => "all", "participant_id" => $pid]
    );

    $selected = null;
    foreach ($matches as $m) {
        $mm = $m["match"];
        if (isset($mm["state"]) && $mm["state"] === "complete") continue;
        if ($mm["player1_id"] == $pid || $mm["player2_id"] == $pid) {
            $selected = $mm;
            break;
        }
    }
    
    if (!$selected) {
        throw new RuntimeException("No open match found for {$playerName}. They may have already played their match.");
    }

    if ($selected["player1_id"] == $pid) {
        $scoresCsv = "1-0"; 
    } else {
        $scoresCsv = "0-1"; 
    }
    
    $update = callChallonge(
        "PUT",
        "https://api.challonge.com/v1/tournaments/$tournament/matches/{$selected["id"]}.json",
        [
            "api_key"           => $apiKey,
            "match[winner_id]"  => $pid,
            "match[scores_csv]" => $scoresCsv,
        ]
    );

    $_SESSION["Response"] = true;
    
    echo json_encode([
        "success" => true,
        "message" => "Vítěz {$playerName} byl úspěšně nastaven!"
    ]);
    
} catch (Exception $e) {
    $_SESSION["Response"] = false;
    error_log("Winner.php error: " . $e->getMessage());
    
    if($_SESSION["Response"] == false){
    echo json_encode([
        "success" => false,
        "message" => "Chyba: " . $e->getMessage()
    ]);
    }
}
?>