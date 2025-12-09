<?php
// ==========================================
// 🔧 配置区：请在此填入您的 API Key
// ==========================================
$API_KEY = "sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"; // 替换为您的 DeepSeek 或 OpenAI Key
$API_URL = "https://api.deepseek.com/chat/completions"; // 接口地址
$MODEL   = "deepseek-chat"; // 模型名称
// ==========================================

// 1. 设置响应头 (允许 JSON 通信和跨域)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求 (Options)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 2. 仅允许 POST 请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => ['message' => 'Only POST method is allowed']]);
    exit;
}

// 3. 获取前端发送的消息
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!isset($input['messages'])) {
    echo json_encode(['error' => ['message' => 'No messages provided']]);
    exit;
}

// 4. 组装请求数据
$data = [
    'model' => $MODEL,
    'messages' => $input['messages'],
    'temperature' => 0.7
];

// 5. 使用 CURL 发送请求给 AI 服务商
$ch = curl_init($API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $API_KEY
]);

// 忽略 SSL 证书验证 (防止服务器证书报错，生产环境建议开启)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

// 6. 错误处理
if (curl_errno($ch)) {
    echo json_encode(['error' => ['message' => 'Curl error: ' . curl_error($ch)]]);
} else {
    // 直接输出 AI 返回的结果
    echo $response;
}

curl_close($ch);
?>