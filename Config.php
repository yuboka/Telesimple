<?php
// Telegram Bot Token
define("BOT_TOKEN", "YOUR_TELEGRAM_BOT_TOKEN_HERE");
define("API_URL", "https://api.telegram.org/bot" . BOT_TOKEN . "/");

// Dummy SABUS API endpoints
$SABUS_APIS = [
    "airtime" => "https://sabusapi.com/v1/airtime",
    "data" => "https://sabusapi.com/v1/data",
    "balance" => "https://sabusapi.com/v1/balance",
];
