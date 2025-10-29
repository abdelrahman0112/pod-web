import './bootstrap';
import Alpine from 'alpinejs';

// Alpine.js global configuration
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
    },
    hide() {
        this.open = false;
    }
}));

Alpine.data('tabs', () => ({
    activeTab: 0,
    switchTab(index) {
        this.activeTab = index;
    }
}));

Alpine.data('search', () => ({
    query: '',
    results: [],
    loading: false,
    async search() {
        if (this.query.length < 2) {
            this.results = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
            this.results = await response.json();
        } catch (error) {
            console.error('Search error:', error);
            this.results = [];
        } finally {
            this.loading = false;
        }
    }
}));

// Global Alpine.js utilities
window.Alpine = Alpine;
Alpine.start();
