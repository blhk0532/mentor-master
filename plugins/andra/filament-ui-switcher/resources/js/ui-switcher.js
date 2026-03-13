// UI Switcher Plugin JavaScript
// Handles page reloads and client-side preference management

// Import CSS
import '../css/ui-switcher.css';

document.addEventListener('DOMContentLoaded', () => {
    // Apply stored font size on page load
    const storedFontSize = localStorage.getItem('ui-font-base');
    if (storedFontSize) {
        document.documentElement.style.setProperty('--text-base', storedFontSize + 'px');
    }

    // Listen for Livewire reload events
    window.addEventListener('reload-page', () => {
        // Add a small delay for UX feedback
        setTimeout(() => {
            window.location.reload();
        }, 300);
    });
});

// Export Alpine.js component if needed
if (typeof Alpine !== 'undefined') {
    Alpine.data('uiSwitcher', () => ({
        init() {
            // Initialize any client-side preferences
            this.applyStoredPreferences();
        },

        applyStoredPreferences() {
            const storedFontSize = localStorage.getItem('ui-font-base');
            if (storedFontSize) {
                document.documentElement.style.setProperty('--text-base', storedFontSize + 'px');
            }
        },

        setFontSize(size) {
            document.documentElement.style.setProperty('--text-base', size + 'px');
            localStorage.setItem('ui-font-base', size);
        }
    }));
}
