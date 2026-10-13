import './bootstrap';
import 'bootstrap';

// Prevent client-side serializers (e.g. JSON.stringify in devtools/loggers) on $wire Proxy
// from accidentally dispatching remote method calls for "toJSON"
document.addEventListener('livewire:init', () => {
    if (window.Livewire) {
        window.Livewire.hook('component.init', ({ component }) => {
            if (component && component.reactive) {
                component.reactive.toJSON = () => ({});
            }
        });
    }
});

// Initialize any Bootstrap tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
});
