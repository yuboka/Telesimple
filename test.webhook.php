<?php
// This file lets you simulate Telegram webhook events manually

$testUpdate = [
    "update_id" => time(),
    "message" => [
        "message_id" => 1,
        "from" => [
            "id" => 123456789,
            "first_name" => "Webhook",
            "username" => "tester"
        ],
        "chat" => [
            "id" => 123456789,
            "type" => "private"
        ],
        "date" => time(),
        "text" => "/balance"   // TEST COMMAND
    ]
];

$ch = curl_init("https://YOUR_DOMAIN/index.php");

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testUpdate));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);

curl_close($ch);

echo "<h3>Webhook Test Result:</h3>";
echo "<pre>";
echo $response ? $response : $error;
echo "</pre>";
