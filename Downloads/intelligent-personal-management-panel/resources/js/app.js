// Livewire se inicializa automáticamente a través de Blade
// No necesita importación manual en la mayoría de casos

// Modo oscuro persistente
document.addEventListener('DOMContentLoaded', () => {
    const darkMode = localStorage.getItem('darkMode') === 'true';
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
});

// Notificaciones tipo toast
window.notify = (message, type = 'success') => {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in text-white ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        type === 'warning' ? 'bg-yellow-500' :
        'bg-blue-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
};

// Inicializar tooltips (opcional)
document.querySelectorAll('[data-tooltip]').forEach(el => {
    el.addEventListener('mouseenter', function() {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute bg-slate-900 text-white text-sm px-3 py-1 rounded z-50';
        tooltip.textContent = this.getAttribute('data-tooltip');
        this.appendChild(tooltip);
    });
    
    el.addEventListener('mouseleave', function() {
        this.querySelector('[class*="absolute"]')?.remove();
    });
});
