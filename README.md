# 🤖 Kubi - Asistente de IA Empático

Kubi es un asistente de IA avanzado con voz natural en español, personalidad adaptable y panel de control web.

## 🌟 Características

- **Voz Natural**: TTS de Google con pronunciación correcta de acentos
- **IA Inteligente**: Integración completa con ChatGPT
- **Personalidad Adaptable**: 4 estados de ánimo y 4 personalidades
- **Panel Web**: Control remoto vía web en `panel.kubi.ar`
- **Contexto Persistente**: Mantiene conversaciones y memoria
- **Comandos Especiales**: Estadísticas, chistes, tiempo, etc.

## 🎛️ Estados de Ánimo

- 😠 **Enojado**: Usuario frustrado o molesto
- 😊 **Contento**: Usuario de buen humor
- 😰 **Angustiado**: Usuario preocupado o ansioso
- 😨 **Asustado**: Usuario asustado o nervioso

## 👤 Personalidades

- 🤝 **Respetuoso**: Empático y constructivo
- ⚠️ **Mal Educado**: Directo y a veces grosero
- 🔥 **Insolente**: Desafiante y sarcástico
- 😑 **Apático**: Indiferente y aburrido

## 🚀 Instalación

### Requisitos

- Raspberry Pi (recomendado) o Linux
- Python 3.7+
- Conexión a internet
- API Key de OpenAI

### 1. Clonar el repositorio

```bash
git clone https://github.com/facuvar/kubi.git
cd kubi
```

### 2. Instalar dependencias

```bash
# En Raspberry Pi
sudo apt update
sudo apt install -y python3-pip mpg123 ffmpeg espeak festival

# Instalar dependencias Python
pip3 install requests
```

### 3. Configurar API Key

Editar `kubi_v2/config.py`:
```python
OPENAI_API_KEY = "tu-api-key-de-openai-aqui"
```

### 4. Ejecutar Kubi

```bash
cd kubi_v2
python3 kubi_panel_integration.py
```

## 🌐 Panel Web

### Acceso
- URL: `panel.kubi.ar`
- Selecciona estado de ánimo y personalidad
- Aplica configuración en tiempo real

### Estructura del Panel
```
panel/
├── index.php          # Panel principal
├── js/
│   └── panel.js       # Interactividad
├── api/
│   ├── update_settings.php
│   ├── get_settings.php
│   └── status.php
└── data/              # Configuraciones
```

## 📁 Estructura del Proyecto

```
kubi/
├── kubi_v2/           # Versión principal
│   ├── kubi_panel_integration.py
│   ├── kubi_voz_natural_siempre.py
│   ├── kubi_ia_completa_fixed.py
│   └── config.py
├── panel/             # Panel web
│   ├── index.php
│   ├── js/
│   ├── api/
│   └── data/
└── README.md
```

## 🎮 Uso

### Comandos de Voz/Texto
- **"kubi"**: Activar conversación
- **"adiós"**: Salir
- **"estadísticas"**: Ver estadísticas
- **"personalidad"**: Ver configuración actual
- **"chiste"**: Contar un chiste
- **"ayuda"**: Ver comandos disponibles

### Panel Web
1. Abrir `panel.kubi.ar`
2. Seleccionar estado de ánimo
3. Seleccionar personalidad
4. Hacer clic en "Aplicar Configuración"
5. Kubi se adaptará automáticamente

## 🔧 Configuración Avanzada

### Personalizar Voces
Editar `config.py`:
```python
VOICE_CONFIG = {
    "rate": 150,      # Velocidad
    "volume": 0.9,    # Volumen
    "voice_id": None  # ID de voz específica
}
```

### Configurar ChatGPT
```python
CHATGPT_CONFIG = {
    "model": "gpt-3.5-turbo",
    "max_tokens": 300,
    "temperature": 0.7
}
```

## 🚀 Despliegue en Railway

El panel web está configurado para desplegarse automáticamente en Railway desde el repositorio GitHub.

### Variables de Entorno
- `OPENAI_API_KEY`: API Key de OpenAI
- `KUBI_PANEL_URL`: URL del panel (panel.kubi.ar)

## 🤝 Contribuir

1. Fork el proyecto
2. Crear una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 🆘 Soporte

- **Issues**: [GitHub Issues](https://github.com/facuvar/kubi/issues)
- **Discusiones**: [GitHub Discussions](https://github.com/facuvar/kubi/discussions)

## 🙏 Agradecimientos

- OpenAI por ChatGPT
- Google por TTS
- Comunidad de Raspberry Pi
- Contribuidores del proyecto

---

**Desarrollado con ❤️ por la comunidad Kubi** 