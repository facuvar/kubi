<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Check if Kubi is running by looking for a status file or process
    $statusFile = '../data/kubi_status.json';
    $online = false;
    $lastSeen = null;
    $currentSettings = null;

    // Check if status file exists and is recent (within last 5 minutes)
    if (file_exists($statusFile)) {
        $status = json_decode(file_get_contents($statusFile), true);
        if ($status && isset($status['timestamp'])) {
            $lastSeen = strtotime($status['timestamp']);
            $online = (time() - $lastSeen) < 300; // 5 minutes
        }
    }

    // Get current settings
    $settingsFile = '../data/kubi_settings.json';
    if (file_exists($settingsFile)) {
        $currentSettings = json_decode(file_get_contents($settingsFile), true);
    }

    echo json_encode([
        'success' => true,
        'online' => $online,
        'lastSeen' => $lastSeen ? date('Y-m-d H:i:s', $lastSeen) : null,
        'currentSettings' => $currentSettings
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'online' => false
    ]);
}
?> 