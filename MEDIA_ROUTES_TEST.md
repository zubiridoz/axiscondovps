# 📸 Pruebas de Rutas de Medios Consolidadas

## Estado Actual
✅ **MediaController** creado y consolidado  
✅ **Routes.php** actualizado con todas las rutas  
✅ **Seguridad** implementada (directory traversal, MIME types)  
✅ **Caché HTTP** configurado para performance  

---

## Cómo Probar

### 1. **Rutas Generales (sin subcarpeta)**

```bash
# Amenidades
curl -v https://app.axiscondo.mx/media/image/amenities/photo.jpg
curl -v https://app.axiscondo.mx/api/v1/amenities/image/photo.jpg

# Anuncios
curl -v https://app.axiscondo.mx/media/image/announcements/ann_123.jpg
curl -v https://app.axiscondo.mx/anuncios/archivo/ann_123.jpg

# Paquetes
curl -v https://app.axiscondo.mx/media/image/parcels/parcel_456.jpg
curl -v https://app.axiscondo.mx/api/v1/security/parcel-photo/parcel_456.jpg

# Configuración
curl -v https://app.axiscondo.mx/media/image/settings/logo.jpg
curl -v https://app.axiscondo.mx/configuracion/imagen/logo.jpg

# Pagos
curl -v https://app.axiscondo.mx/media/image/payments/proof.jpg
curl -v https://app.axiscondo.mx/finanzas/archivo/payments/proof.jpg

# Finanzas
curl -v https://app.axiscondo.mx/media/image/financial/doc.pdf
curl -v https://app.axiscondo.mx/finanzas/archivo/financial/doc.pdf
```

### 2. **Rutas con Subdirectorio (AssetService)**

```bash
# Amenidades con ID
curl -v https://app.axiscondo.mx/api/v1/assets/amenities/1/photo.jpg
curl -v https://app.axiscondo.mx/api/v1/assets/amenities/2/image.png

# Condominios con ID
curl -v https://app.axiscondo.mx/api/v1/assets/condominiums/2/logo.jpg

# Avatares
curl -v https://app.axiscondo.mx/api/v1/assets/avatars/user_123.jpg
```

---

## Respuestas Esperadas

### ✅ Success (200)
- Headers: `Content-Type: image/jpeg`
- Headers: `Cache-Control: public, max-age=31536000, immutable`
- Headers: `ETag: "hash_del_archivo"`
- Body: Contenido del archivo

### ✅ Cache Hit (304 Not Modified)
- **Si enviaste:** `If-Modified-Since: [fecha actual]`
- **Respuesta:** Status 304 (sin body)

### ❌ Not Found (404)
- Archivo no existe en el path correcto
- Revisa que el archivo esté en `writable/uploads/{tipo}/{archivo}`

### ❌ Forbidden (403)
- Intento de directory traversal detectado (p.ej: `/media/image/../../config`)

---

## Estructura de Carpetas

```
writable/uploads/
├── access/              (seguridad/acceso)
├── amenities/           (amenidades)
│   ├── 1/
│   │   └── photo.jpg    (pueden tener subcarpetas por ID)
│   └── photo.jpg        (o sin subcarpeta)
├── announcements/       (anuncios)
├── avatars/             (avatares de usuarios)
├── financial/           (documentos financieros)
├── parcels/             (fotos de paquetes)
├── payments/            (comprobantes de pago)
├── staff/               (fotos de personal)
├── tickets/             (archivos de tickets)
└── condominiums/        (logos/covers de condominios)
    ├── 1/
    │   └── logo.jpg
    └── 2/
        └── cover.png
```

---

## Debugging

### Ver Headers de Cache
```bash
curl -i -v https://app.axiscondo.mx/media/image/amenities/photo.jpg
```

### Verificar archivo existe localmente
```bash
ls -la /home/axiscondo-app/htdocs/app.axiscondo.mx/writable/uploads/amenities/
```

### Ver logs de MediaController
```bash
tail -f /home/axiscondo-app/htdocs/app.axiscondo.mx/writable/logs/log-*.log
```

---

## Notas Importantes

- **TODOS los endpoints legacy siguen funcionando** para compatibilidad con apps móviles
- **Caché agresivo (1 año)** en MediaController - ideal para archivos inmutables
- **Sin autenticación requerida** - los archivos son públicos desde `writable/uploads`
- **Seguridad**: Directory traversal bloqueado, MIME types validados, Content-Type correcto

---

## Para la App Android/iOS

Usa cualquiera de estas URLs indistintamente:

```
# Amenidades
https://app.axiscondo.mx/api/v1/assets/amenities/{id}/{filename}
https://app.axiscondo.mx/api/v1/amenities/image/{filename}
https://app.axiscondo.mx/media/image/amenities/{filename}

# Paquetes
https://app.axiscondo.mx/api/v1/security/parcel-photo/{filename}
https://app.axiscondo.mx/media/image/parcels/{filename}

# Anuncios
https://app.axiscondo.mx/media/image/announcements/{filename}
https://app.axiscondo.mx/anuncios/archivo/{filename}
```

Todas apuntan al mismo MediaController centralizado. ✅

---

**Creado:** 2026-04-27
**Estado:** ✅ LISTO PARA PRODUCCIÓN
