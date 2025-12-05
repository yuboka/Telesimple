<?php
require_once "config.php";

$update = json_decode(file_get_contents("php://input"), true);
if (!$update) exit();

if (isset($update["message"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $text = strtolower(trim($update["message"]["text"]));

    switch ($text) {
        case "/start":
        case "/help":
            $reply = "Welcome! Available commands:
            
/airtime  → Get Airtime API list
/data     → Get Data plans list
/balance  → Check SABUS wallet balance
/help     → Show this menu";
            break;

        case "/balance":
            $reply = getBalance();
            break;

        case "/airtime":
            $reply = getAirtimeApi();
            break;

        case "/data":
            $reply = getDataPlans();
            break;

        default:
            $reply = "Unknown command. Type /help";
    }

    sendMessage($chat_id, $reply);
}


/* ------------------- SABUS API FUNCTIONS ------------------- */

function sabusCurl($endpoint, $method = "GET", $payload = [])
{
    $url = SABUS_BASE . $endpoint;

    $curl = curl_init();

    $headers = [
        "Authorization: Bearer " . SABUS_KEY,
        "Content-Type: application/json"
    ];

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
    ];

    if ($method == "POST") {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = json_encode($payload);
    }

    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) return "Curl Error: $err";

    return $response;
}

/* --- Get SABUS Wallet Balance --- */
function getBalance()
{
    $res = sabusCurl("wallet/balance");

    $json = json_decode($res, true);

    if (!$json || !isset($json["balance"])) {
        return "Failed to fetch balance";
    }

    return "💰 *Wallet Balance:* ₦" . $json["balance"];
}

/* --- Get Airtime API info --- */
function getAirtimeApi()
{
    $res = sabusCurl("airtime/pricing");

    return "*SABUS Airtime Endpoints:*\n" .
           "Endpoint: sabusapi.com/api/airtime\n" .
           "Method: POST\n\n" .
           "Pricing Response:\n```\n$res\n```";
}

/* --- Get Data Plans --- */
function getDataPlans()
{
    $res = sabusCurl("data/plans");

    return "*SABUS Data Plans:*\n```\n$res\n```";
}


/* ------------------- Telegram Send Message ------------------- */

function sendMessage($chat_id, $text)
{
    $url = TELEGRAM_API . "sendMessage";
    $data = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "Markdown"
    ];

    file_get_contents($url . "?" . http_build_query($data));
}            break;

        case "/balance":
            $reply = "SABUS Wallet Balance:
1. API: https://sabusapi.com/v1/balance
2. Method: GET
3. Requires: API key";
            break;

        default:
            $reply = "Unknown command. Type /help";
            break;
    }

    sendMessage($chat_id, $reply);
}

/**
 * Send a message back to Telegram
 */
function sendMessage($chat_id, $text)
{
    $url = API_URL . "sendMessage";
    $data = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "Markdown"
    ];

    file_get_contents($url . "?" . http_build_query($data));
}
?>
