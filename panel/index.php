<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kubi Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .panel-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        .kubi-logo {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .mood-selector, .personality-selector {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .mood-selector:hover, .personality-selector:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        .btn-mood, .btn-personality {
            border-radius: 25px;
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .btn-mood:hover, .btn-personality:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-mood.active, .btn-personality.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }
        .status-indicator {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            margin: 10px 0;
        }
        .status-online {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-offline {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .kubi-preview {
            background: #e3f2fd;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            border-left: 5px solid #2196f3;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="panel-container p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="kubi-logo">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h1 class="h2 mb-2">Kubi Control Panel</h1>
                        <p class="text-muted">Controla la personalidad y estado de ánimo de tu asistente IA</p>
                        
                        <!-- Status Indicator -->
                        <div id="statusIndicator" class="status-indicator status-offline">
                            <i class="fas fa-circle me-2"></i>
                            <span id="statusText">Kubi Offline</span>
                        </div>
                    </div>

                    <!-- Mood Selector -->
                    <div class="mood-selector">
                        <h4 class="mb-3">
                            <i class="fas fa-smile text-warning me-2"></i>
                            Estado de Ánimo
                        </h4>
                        <p class="text-muted mb-3">¿Cómo te sientes hoy?</p>
                        <div class="d-flex flex-wrap justify-content-center">
                            <button class="btn btn-outline-warning btn-mood" data-mood="enojado">
                                <i class="fas fa-angry me-2"></i>Enojado
                            </button>
                            <button class="btn btn-outline-success btn-mood" data-mood="contento">
                                <i class="fas fa-laugh me-2"></i>Contento
                            </button>
                            <button class="btn btn-outline-info btn-mood" data-mood="angustiado">
                                <i class="fas fa-sad-tear me-2"></i>Angustiado
                            </button>
                            <button class="btn btn-outline-danger btn-mood" data-mood="asustado">
                                <i class="fas fa-surprise me-2"></i>Asustado
                            </button>
                        </div>
                    </div>

                    <!-- Personality Selector -->
                    <div class="personality-selector">
                        <h4 class="mb-3">
                            <i class="fas fa-user-cog text-primary me-2"></i>
                            Personalidad de Kubi
                        </h4>
                        <p class="text-muted mb-3">¿Cómo quieres que te responda Kubi?</p>
                        <div class="d-flex flex-wrap justify-content-center">
                            <button class="btn btn-outline-primary btn-personality" data-personality="respetuoso">
                                <i class="fas fa-handshake me-2"></i>Respetuoso
                            </button>
                            <button class="btn btn-outline-secondary btn-personality" data-personality="mal_educado">
                                <i class="fas fa-exclamation-triangle me-2"></i>Mal Educado
                            </button>
                            <button class="btn btn-outline-warning btn-personality" data-personality="insolente">
                                <i class="fas fa-fire me-2"></i>Insolente
                            </button>
                            <button class="btn btn-outline-dark btn-personality" data-personality="apatico">
                                <i class="fas fa-meh me-2"></i>Apático
                            </button>
                        </div>
                    </div>

                    <!-- Kubi Preview -->
                    <div class="kubi-preview">
                        <h5 class="mb-3">
                            <i class="fas fa-eye text-info me-2"></i>
                            Vista Previa de Kubi
                        </h5>
                        <div id="kubiPreview" class="text-muted">
                            Selecciona un estado de ánimo y personalidad para ver cómo responderá Kubi...
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <button id="applySettings" class="btn btn-primary btn-lg me-3" disabled>
                            <i class="fas fa-save me-2"></i>Aplicar Configuración
                        </button>
                        <button id="resetSettings" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-undo me-2"></i>Restablecer
                        </button>
                    </div>

                    <!-- Current Settings Display -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2">Configuración Actual:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Estado de ánimo:</strong> 
                                <span id="currentMood" class="text-muted">No seleccionado</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Personalidad:</strong> 
                                <span id="currentPersonality" class="text-muted">No seleccionado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/panel.js"></script>
</body>
</html> 