const CONFIG = {
    // Calculamos dinámicamente el Base URL (ej: localhost/condominet26/public)
    API_URL: window.location.origin + window.location.pathname.substring(0, window.location.pathname.indexOf('/public/')) + '/public/api/v1'
};

const Auth = {
    getToken: () => localStorage.getItem('sec_token'),
    setToken: (token) => localStorage.setItem('sec_token', token),
    clearSession: () => {
        localStorage.removeItem('sec_token');
        localStorage.removeItem('sec_user');
    },
    getUser: () => JSON.parse(localStorage.getItem('sec_user') || '{}'),
    setUser: (data) => localStorage.setItem('sec_user', JSON.stringify(data)),
    
    checkProtected: () => {
        if (!Auth.getToken()) window.location.replace('login.html');
    },

    login: async (email, password) => {
        try {
            const response = await fetch(`${CONFIG.API_URL}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password, device_name: 'PWA Security Gate' })
            });
            const result = await response.json();
            
            if (response.ok && result.data?.token) {
                // Verificar si tiene roles de guardia/en al menos 1 tenant (validación adicional omitida por simplicidad, asumimos credenciales dadas)
                Auth.setToken(result.data.token);
                Auth.setUser(result.data.user);
                return { success: true };
            }
            return { success: false, message: result.message || 'Error de credenciales' };
        } catch (error) {
            return { success: false, message: 'Problema de conexión al servidor' };
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
        window.location.replace('login.html');
    }
};

const ApiClient = {
    async request(endpoint, method = 'GET', body = null) {
        const token = Auth.getToken();
        if (!token) {
            window.location.replace('login.html');
            return null;
        }

        const headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        };

        const config = { method, headers };

        if (body) {
            headers['Content-Type'] = 'application/json';
            config.body = JSON.stringify(body);
        }

        try {
            const response = await fetch(`${CONFIG.API_URL}${endpoint}`, config);
            if (response.status === 401) {
                Auth.clearSession();
                window.location.replace('login.html');
                return null;
            }
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            return { status: 'error', message: 'Error de red en la caseta' };
        }
    }
};

/**
 * ImageCompressor — Utilidad SaaS de compresión client-side
 * Reduce imágenes de cámara (~3-8MB) a <200KB usando Canvas API.
 * Configurable por calidad y dimensión máxima.
 */
const ImageCompressor = {
    MAX_DIMENSION: 1200,
    QUALITY: 0.7,
    OUTPUT_TYPE: 'image/jpeg',

    /**
     * Comprime un File/Blob de imagen y devuelve un nuevo File comprimido.
     * @param {File} file - Archivo original de la cámara
     * @param {Object} opts - { maxDimension, quality }
     * @returns {Promise<File>} Archivo comprimido
     */
    async compress(file, opts = {}) {
        const maxDim = opts.maxDimension || this.MAX_DIMENSION;
        const quality = opts.quality || this.QUALITY;

        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    let { width, height } = img;

                    // Escalar proporcionalmente si excede la dimensión máxima
                    if (width > maxDim || height > maxDim) {
                        const ratio = Math.min(maxDim / width, maxDim / height);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        if (!blob) {
                            resolve(file); // Fallback si falla
                            return;
                        }
                        const compressedFile = new File(
                            [blob],
                            file.name.replace(/\.[^.]+$/, '.jpg'),
                            { type: this.OUTPUT_TYPE, lastModified: Date.now() }
                        );
                        const savedPct = Math.round((1 - compressedFile.size / file.size) * 100);
                        console.log(`[Compress] ${file.name}: ${(file.size/1024).toFixed(0)}KB → ${(compressedFile.size/1024).toFixed(0)}KB (${savedPct}% reducido)`);
                        resolve(compressedFile);
                    }, this.OUTPUT_TYPE, quality);
                };
                img.onerror = () => resolve(file);
                img.src = e.target.result;
            };
            reader.onerror = () => resolve(file);
            reader.readAsDataURL(file);
        });
    }
};

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('service-worker.js');
    });
}
