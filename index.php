<?php
header("Content-Type: application/json");

// Get JSON data from TradingView
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (!isset($data['symbol'], $data['price'], $data['time'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

// Log received data
file_put_contents("logs.txt", json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

// Respond to TradingView
echo json_encode(["status" => "success", "message" => "Webhook received"]);
?>
