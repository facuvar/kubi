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
    $settingsFile = '../data/kubi_settings.json';
    
    if (file_exists($settingsFile)) {
        $settings = json_decode(file_get_contents($settingsFile), true);
        
        if ($settings) {
            echo json_encode([
                'success' => true,
                'data' => $settings
            ]);
        } else {
            throw new Exception('Invalid settings file format');
        }
    } else {
        // Return default settings if file doesn't exist
        echo json_encode([
            'success' => true,
            'data' => [
                'mood' => null,
                'personality' => null,
                'timestamp' => null
            ]
        ]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 