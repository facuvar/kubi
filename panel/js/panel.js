// Kubi Control Panel JavaScript
class KubiPanel {
    constructor() {
        this.selectedMood = null;
        this.selectedPersonality = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCurrentSettings();
        this.checkKubiStatus();
    }

    bindEvents() {
        // Mood buttons
        document.querySelectorAll('.btn-mood').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.selectMood(e.target.dataset.mood);
            });
        });

        // Personality buttons
        document.querySelectorAll('.btn-personality').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.selectPersonality(e.target.dataset.personality);
            });
        });

        // Apply settings
        document.getElementById('applySettings').addEventListener('click', () => {
            this.applySettings();
        });

        // Reset settings
        document.getElementById('resetSettings').addEventListener('click', () => {
            this.resetSettings();
        });
    }

    selectMood(mood) {
        // Remove active class from all mood buttons
        document.querySelectorAll('.btn-mood').forEach(btn => {
            btn.classList.remove('active');
        });

        // Add active class to selected button
        const selectedBtn = document.querySelector(`[data-mood="${mood}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
        }

        this.selectedMood = mood;
        this.updatePreview();
        this.updateApplyButton();
    }

    selectPersonality(personality) {
        // Remove active class from all personality buttons
        document.querySelectorAll('.btn-personality').forEach(btn => {
            btn.classList.remove('active');
        });

        // Add active class to selected button
        const selectedBtn = document.querySelector(`[data-personality="${personality}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
        }

        this.selectedPersonality = personality;
        this.updatePreview();
        this.updateApplyButton();
    }

    updatePreview() {
        const preview = document.getElementById('kubiPreview');
        
        if (!this.selectedMood || !this.selectedPersonality) {
            preview.innerHTML = 'Selecciona un estado de ánimo y personalidad para ver cómo responderá Kubi...';
            return;
        }

        const moodTexts = {
            'enojado': 'estás enojado',
            'contento': 'estás contento',
            'angustiado': 'estás angustiado',
            'asustado': 'estás asustado'
        };

        const personalityTexts = {
            'respetuoso': 'respetuoso y empático',
            'mal_educado': 'mal educado y directo',
            'insolente': 'insolente y desafiante',
            'apatico': 'apático y desinteresado'
        };

        const previewText = `
            <div class="alert alert-info">
                <strong>Kubi responderá de manera ${personalityTexts[this.selectedPersonality]}</strong><br>
                <small>Adaptándose a que ${moodTexts[this.selectedMood]}</small>
            </div>
        `;

        preview.innerHTML = previewText;
    }

    updateApplyButton() {
        const applyBtn = document.getElementById('applySettings');
        if (this.selectedMood && this.selectedPersonality) {
            applyBtn.disabled = false;
            applyBtn.classList.remove('btn-secondary');
            applyBtn.classList.add('btn-primary');
        } else {
            applyBtn.disabled = true;
            applyBtn.classList.remove('btn-primary');
            applyBtn.classList.add('btn-secondary');
        }
    }

    async applySettings() {
        if (!this.selectedMood || !this.selectedPersonality) {
            this.showNotification('Por favor selecciona un estado de ánimo y personalidad', 'warning');
            return;
        }

        const applyBtn = document.getElementById('applySettings');
        const originalText = applyBtn.innerHTML;
        
        // Show loading state
        applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Aplicando...';
        applyBtn.disabled = true;

        try {
            const response = await fetch('api/update_settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    mood: this.selectedMood,
                    personality: this.selectedPersonality
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Configuración aplicada exitosamente', 'success');
                this.updateCurrentSettings();
                this.saveToLocalStorage();
            } else {
                this.showNotification('Error al aplicar configuración: ' + result.message, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión', 'danger');
        } finally {
            // Restore button state
            applyBtn.innerHTML = originalText;
            applyBtn.disabled = false;
        }
    }

    resetSettings() {
        // Clear selections
        this.selectedMood = null;
        this.selectedPersonality = null;

        // Remove active classes
        document.querySelectorAll('.btn-mood, .btn-personality').forEach(btn => {
            btn.classList.remove('active');
        });

        // Update UI
        this.updatePreview();
        this.updateApplyButton();
        this.updateCurrentSettings();

        // Clear local storage
        localStorage.removeItem('kubiSettings');

        this.showNotification('Configuración restablecida', 'info');
    }

    updateCurrentSettings() {
        const moodSpan = document.getElementById('currentMood');
        const personalitySpan = document.getElementById('currentPersonality');

        moodSpan.textContent = this.selectedMood ? this.selectedMood.charAt(0).toUpperCase() + this.selectedMood.slice(1) : 'No seleccionado';
        personalitySpan.textContent = this.selectedPersonality ? this.selectedPersonality.replace('_', ' ').charAt(0).toUpperCase() + this.selectedPersonality.replace('_', ' ').slice(1) : 'No seleccionado';
    }

    async loadCurrentSettings() {
        try {
            const response = await fetch('api/get_settings.php');
            const result = await response.json();

            if (result.success && result.data) {
                this.selectedMood = result.data.mood;
                this.selectedPersonality = result.data.personality;

                // Update UI
                if (this.selectedMood) {
                    const moodBtn = document.querySelector(`[data-mood="${this.selectedMood}"]`);
                    if (moodBtn) moodBtn.classList.add('active');
                }

                if (this.selectedPersonality) {
                    const personalityBtn = document.querySelector(`[data-personality="${this.selectedPersonality}"]`);
                    if (personalityBtn) personalityBtn.classList.add('active');
                }

                this.updatePreview();
                this.updateApplyButton();
                this.updateCurrentSettings();
            }
        } catch (error) {
            console.error('Error loading settings:', error);
        }
    }

    async checkKubiStatus() {
        try {
            const response = await fetch('api/status.php');
            const result = await response.json();

            const statusIndicator = document.getElementById('statusIndicator');
            const statusText = document.getElementById('statusText');

            if (result.online) {
                statusIndicator.className = 'status-indicator status-online';
                statusText.textContent = 'Kubi Online';
            } else {
                statusIndicator.className = 'status-indicator status-offline';
                statusText.textContent = 'Kubi Offline';
            }
        } catch (error) {
            console.error('Error checking status:', error);
        }
    }

    saveToLocalStorage() {
        const settings = {
            mood: this.selectedMood,
            personality: this.selectedPersonality,
            timestamp: new Date().toISOString()
        };
        localStorage.setItem('kubiSettings', JSON.stringify(settings));
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
}

// Initialize panel when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new KubiPanel();
}); 