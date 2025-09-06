<?php
// api/pd_client.php
function pd_request(string $method, string $endpoint, array $data = null)
{
    $config = require __DIR__ . '/config.php';

    $url = rtrim($config['api_base'], '/') . '/' . ltrim($endpoint, '/');
    $url .= (str_contains($url, '?') ? '&' : '?') . 'api_token=' . urlencode($config['api_token']);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($errno) {
        return ['success' => false, 'error' => $error];
    }
    $json = json_decode($response, true);
    return $json ?? ['success' => false, 'error' => 'Invalid JSON', 'raw' => $response];
}
