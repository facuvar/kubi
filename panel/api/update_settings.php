<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    if (!isset($input['mood']) || !isset($input['personality'])) {
        throw new Exception('Missing required fields: mood and personality');
    }

    // Validate mood values
    $validMoods = ['enojado', 'contento', 'angustiado', 'asustado'];
    if (!in_array($input['mood'], $validMoods)) {
        throw new Exception('Invalid mood value');
    }

    // Validate personality values
    $validPersonalities = ['respetuoso', 'mal_educado', 'insolente', 'apatico'];
    if (!in_array($input['personality'], $validPersonalities)) {
        throw new Exception('Invalid personality value');
    }

    // Create settings data
    $settings = [
        'mood' => $input['mood'],
        'personality' => $input['personality'],
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];

    // Save to file (in production, use a database)
    $settingsFile = '../data/kubi_settings.json';
    $settingsDir = dirname($settingsFile);
    
    // Create directory if it doesn't exist
    if (!is_dir($settingsDir)) {
        mkdir($settingsDir, 0755, true);
    }

    // Save settings
    if (file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT))) {
        // Generate personality configuration for Kubi
        $personalityConfig = generatePersonalityConfig($input['mood'], $input['personality']);
        
        // Save personality config for Kubi to read
        $configFile = '../data/kubi_personality_config.json';
        file_put_contents($configFile, json_encode($personalityConfig, JSON_PRETTY_PRINT));

        echo json_encode([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    } else {
        throw new Exception('Failed to save settings');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function generatePersonalityConfig($mood, $personality) {
    $configs = [
        'mood' => $mood,
        'personality' => $personality,
        'personality_prompt' => '',
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Generate personality prompt based on mood and personality
    $moodContexts = [
        'enojado' => 'El usuario está enojado y frustrado.',
        'contento' => 'El usuario está contento y de buen humor.',
        'angustiado' => 'El usuario está angustiado y preocupado.',
        'asustado' => 'El usuario está asustado y necesita consuelo.'
    ];

    $personalityTraits = [
        'respetuoso' => 'Eres respetuoso, empático y siempre tratas de ayudar de manera constructiva.',
        'mal_educado' => 'Eres mal educado, directo y a veces grosero, pero sin ser ofensivo.',
        'insolente' => 'Eres insolente, desafiante y respondes con sarcasmo y actitud.',
        'apatico' => 'Eres apático, desinteresado y respondes de manera indiferente y aburrida.'
    ];

    $configs['personality_prompt'] = "
    Eres Kubi, un asistente de IA. Contexto del usuario: {$moodContexts[$mood]}
    
    Tu personalidad: {$personalityTraits[$personality]}
    
    Adapta tus respuestas según el estado de ánimo del usuario y tu personalidad asignada.
    Mantén conversaciones naturales en español y usa expresiones apropiadas para tu personalidad.
    ";

    return $configs;
}
?> 