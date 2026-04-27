/**
 * Configuraciones y Lógica Global de la PWA
 */
const CONFIG = {
    // Resuelve dinámicamente la ruta base del proyecto CI4
    // Detecta si estamos en /condominet26/public/pwa/resident/ y sube al root de CI4 public
    API_URL: (() => {
        const path = window.location.pathname;
        const pwaIndex = path.indexOf('/pwa/');
        if (pwaIndex !== -1) {
            // Extraemos todo hasta antes de /pwa/ (ej: /condominet26/public)
            // y le añadimos /api/v1 (las rutas CI4 van por /public/api/v1)
            return window.location.origin + path.substring(0, pwaIndex) + '/api/v1';
        }
        return window.location.origin + '/api/v1';
    })()
};

// ==========================================
// Módulo de Autenticación y Token Management
// ==========================================
const Auth = {
    getToken: () => localStorage.getItem('pwa_token'),
    setToken: (token) => localStorage.setItem('pwa_token', token),
    clearSession: () => {
        localStorage.removeItem('pwa_token');
        localStorage.removeItem('pwa_user');
    },
    getUser: () => JSON.parse(localStorage.getItem('pwa_user') || '{}'),
    setUser: (data) => localStorage.setItem('pwa_user', JSON.stringify(data)),
    
    // Verificador in-line de vista protegida
    checkProtected: () => {
        if (!Auth.getToken()) {
            window.location.replace('login.html');
        }
    },

    // Iniciar Sesión (Petición Pura)
    login: async (email, password) => {
        try {
            const response = await fetch(`${CONFIG.API_URL}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password, device_name: 'PWA Resident' })
            });
            const result = await response.json();
            
            if (response.ok && result.data && result.data.token) {
                // El endpoint preexistente retorna: data.token, data.user
                Auth.setToken(result.data.token);
                Auth.setUser(result.data.user);
                return { success: true };
            }
            return { success: false, message: result.message || 'Error desconocido' };
        } catch (error) {
            return { success: false, message: 'Problema de conexión al servidor.' };
        }
    },
    
    logout: async () => {
        const token = Auth.getToken();
        if (token) {
            await fetch(`${CONFIG.API_URL}/logout`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` }
            });
        }
        Auth.clearSession();

        // Redirigir al login principal del backend
        const path = window.location.pathname;
        const pwaIndex = path.indexOf('/pwa/');
        const mainLoginUrl = pwaIndex !== -1 
            ? window.location.origin + path.substring(0, pwaIndex) + '/login' 
            : '/login';
        
        window.location.replace(mainLoginUrl);
    }
};

// ==========================================
// HTTP Client Wrapper (Inyecta Bearer)
// ==========================================
const ApiClient = {
    async request(endpoint, method = 'GET', body = null) {
        const token = Auth.getToken();
        
        // Si no hay token, redirigir al login (por seguridad)
        if (!token) {
            window.location.replace('login.html');
            return;
        }

        const headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        };

        const config = { method, headers };

        if (body) {
            if (body instanceof FormData) {
                // FormData maneja su propio Content-Type
                config.body = body;
            } else {
                headers['Content-Type'] = 'application/json';
                config.body = JSON.stringify(body);
            }
        }

        try {
            const response = await fetch(`${CONFIG.API_URL}${endpoint}`, config);
            
            if (response.status === 401) {
                // Token expirado o inválido
                Auth.clearSession();
                window.location.replace('login.html');
                return null;
            }

            return await response.json();
            
        } catch (error) {
            console.error("API Error:", error);
            return { status: 'error', message: 'Error de red' };
        }
    }
};

// ==========================================
// Registro del Service Worker Global
// ==========================================
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('service-worker.js')
            .then(reg => console.log('SW Registrado', reg.scope))
            .catch(err => console.log('SW Falló', err));
    });
}
