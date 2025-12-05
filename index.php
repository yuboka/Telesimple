<?php
require_once "Config.php";

// Read Telegram updates (webhook)
$update = json_decode(file_get_contents("php://input"), true);

if (!$update) {
    exit("No update received");
}

if (isset($update["message"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $text = trim($update["message"]["text"]);

    switch ($text) {
        case "/start":
        case "/help":
            $reply = "Welcome! Available commands:
• /airtime – Airtime API list
• /data – Data API list
• /balance – Wallet balance API
• /help – Show help menu";
            break;

        case "/airtime":
            $reply = "SABUS Airtime APIs:
1. API: https://sabusapi.com/v1/airtime
2. Method: POST
3. Params: network, phone, amount";
            break;

        case "/data":
            $reply = "SABUS Data APIs:
1. API: https://sabusapi.com/v1/data
2. Method: POST
3. Params: network, plan_id, phone";
            break;

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
