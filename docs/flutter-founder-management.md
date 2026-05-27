# 🛡️ Gestión de Fundadores — Guía de Implementación Flutter

> **Fecha:** 2026-05-27  
> **Backend:** CodeIgniter 4 — API REST  
> **Estado:** Backend ✅ Listo · Flutter ⏳ Pendiente

---

## 📋 Resumen del Feature

Permite que un Fundador (`is_owner = 1`) promueva a otros Co-Admins como Fundadores adicionales, otorgándoles acceso a **todos** los condominios del fundador original. Al revocar el rol, el usuario pierde acceso a los condominios adicionales y conserva solo el que tenía asignado.

### Reglas de negocio

| Regla | Detalle |
|-------|---------|
| Múltiples fundadores | Sí, un condominio puede tener 2+ fundadores |
| Auto-degradación | ❌ Prohibida — un fundador no puede quitarse el rol a sí mismo |
| Mínimo 1 fundador | ✅ Siempre debe quedar al menos 1 fundador por comunidad |
| Acceso multi-condo al promover | El nuevo fundador hereda acceso a todos los condominios del promotor |
| Acceso al revocar | Se eliminan las entradas con `is_owner=1` en otros condominios. Solo conserva el condominio donde fue degradado |

---

## 🔌 Endpoints API

> **Base URL:** `https://app.axiscondo.mx/api/v1/condominiums`  
> **Auth:** `Authorization: Bearer {token}`  
> **Tenant:** `X-Condo-Id: {condominium_id}`

### 1. Listar Administradores

```
GET /api/v1/condominiums/admins
```

**Headers requeridos:**
```
Authorization: Bearer {token}
X-Condo-Id: {condominium_id}
```

**Response exitosa (200):**
```json
{
  "status": "success",
  "data": {
    "admins": [
      {
        "assignment_id": 1,
        "role_id": 2,
        "is_owner": 1,
        "user_id": 4,
        "first_name": "Juan",
        "last_name": "Pérez",
        "email": "juan@example.com",
        "role_name": "ADMIN"
      },
      {
        "assignment_id": 26,
        "role_id": 2,
        "is_owner": 0,
        "user_id": 25,
        "first_name": "Ana",
        "last_name": "García",
        "email": "ana@example.com",
        "role_name": "ADMIN"
      }
    ],
    "current_user_is_owner": true
  }
}
```

**Campos clave por admin:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `assignment_id` | int | ID único del registro (usar para promote/demote) |
| `is_owner` | int | `1` = Fundador, `0` = Co-Admin |
| `user_id` | int | ID del usuario |
| `first_name` | string | Nombre |
| `last_name` | string | Apellido |
| `email` | string | Email |

**Campo raíz:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `current_user_is_owner` | bool | Si el usuario autenticado es fundador en este condominio |

---

### 2. Promover Co-Admin → Fundador

```
POST /api/v1/condominiums/admins/promote
```

**Body (form-data o JSON):**
```json
{
  "assignment_id": 26
}
```

**Response exitosa (200):**
```json
{
  "status": "success",
  "message": "Administrador promovido a Fundador exitosamente. Se le otorgó acceso a 3 comunidad(es) adicional(es)."
}
```

**Errores posibles:**

| HTTP | Código | Causa |
|------|--------|-------|
| 403 | — | El usuario autenticado no es Fundador |
| 404 | — | `assignment_id` no existe en este condominio |
| 409 | — | El admin ya es Fundador |
| 422 | — | `assignment_id` inválido (≤ 0) |

---

### 3. Revocar Fundador → Co-Admin

```
POST /api/v1/condominiums/admins/demote
```

**Body (form-data o JSON):**
```json
{
  "assignment_id": 26
}
```

**Response exitosa (200):**
```json
{
  "status": "success",
  "message": "Rol de Fundador revocado. Solo conserva acceso a esta comunidad."
}
```

**Errores posibles:**

| HTTP | Código | Causa |
|------|--------|-------|
| 403 | — | No es Fundador, o intenta degradarse a sí mismo |
| 404 | — | `assignment_id` no existe en este condominio |
| 409 | — | El admin ya no es Fundador |
| 422 | — | Sería el último fundador de la comunidad |

---

### 4. Listar Condominios del Usuario (ya existente — actualizado)

```
GET /api/v1/condominiums/mine
```

> ⚠️ **NO requiere `X-Condo-Id`** — se usa antes de elegir un condominio.

**Response exitosa (200):**
```json
{
  "status": "success",
  "data": {
    "condominiums": [
      {
        "id": 2,
        "name": "Residencial Los Pinos",
        "logo": "condominiums/2/logo.png",
        "logo_url": "https://app.axiscondo.mx/api/v1/public/image/condominiums/2/logo.png",
        "address": "Calle 10, Mérida, Yucatán",
        "currency": "MXN",
        "status": "active",
        "role_name": "ADMIN",
        "role_id": 2,
        "is_owner": 1,
        "city": "Mérida",
        "initial": "R"
      }
    ],
    "current_id": 2,
    "total": 1
  }
}
```

**Campo nuevo:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `is_owner` | int | `1` = Fundador, `0` = No fundador. **NUEVO — antes no se incluía** |

---

### 5. Login (ya existente — actualizado)

```
POST /api/v1/auth/login
```

**Cambio en la response:**  
El array `tenants` ahora incluye el campo `is_owner`:

```json
{
  "data": {
    "tenants": [
      {
        "condominium_id": 2,
        "role_id": 2,
        "is_owner": 1,
        "role_name": "ADMIN",
        "condominium_name": "Residencial Los Pinos",
        "logo": "condominiums/2/logo.png"
      }
    ]
  }
}
```

---

## 🚨 Códigos de Error Globales (ApiAuthFilter)

> Estos errores se devuelven **antes de llegar al controller**, desde el filtro de autenticación. Flutter debe manejarlos en su **interceptor HTTP global** (Dio interceptor).

### Respuestas 403 con códigos específicos

```json
{
  "success": false,
  "message": "...",
  "code": "ACCESS_REVOKED | COMMUNITY_DELETED | COMMUNITY_SUSPENDED | INVALID_UNIT"
}
```

| Código | Significado | Acción en Flutter |
|--------|-------------|-------------------|
| `ACCESS_REVOKED` | El usuario fue **eliminado** del condominio (admin lo removió). El condominio sigue existiendo pero el usuario ya no tiene acceso | Limpiar `X-Condo-Id` cacheado → Llamar `GET /mine` → Mostrar selector de condominios o pantalla vacía |
| `COMMUNITY_DELETED` | El condominio fue **eliminado** (soft delete). Ya no existe | Limpiar `X-Condo-Id` cacheado → Llamar `GET /mine` → Redirigir al selector |
| `COMMUNITY_SUSPENDED` | El condominio está **suspendido** (suscripción vencida/cancelada) | Mostrar pantalla de "Comunidad suspendida" con opción de contactar admin |
| `INVALID_UNIT` | El `X-Unit-Id` enviado no pertenece al usuario en este condominio | Limpiar `X-Unit-Id` → Re-resolver unidad activa |

---

## 📱 Cambios Requeridos en Flutter

### 1. Interceptor HTTP Global (Dio)

Agregar manejo de los códigos `ACCESS_REVOKED` y `COMMUNITY_DELETED` en el interceptor de Dio:

```dart
// En tu Dio interceptor (onError)
if (error.response?.statusCode == 403) {
  final code = error.response?.data['code'];
  
  switch (code) {
    case 'ACCESS_REVOKED':
      // El usuario fue removido de este condominio
      await _handleAccessRevoked();
      break;
    case 'COMMUNITY_DELETED':
      // El condominio fue eliminado
      await _handleCommunityDeleted();
      break;
    case 'COMMUNITY_SUSPENDED':
      // Mostrar pantalla de suspensión
      await _handleCommunitySuspended();
      break;
  }
}

Future<void> _handleAccessRevoked() async {
  // 1. Limpiar condo cacheado
  await StorageService.remove('current_condo_id');
  
  // 2. Refrescar lista de condominios
  final condos = await CondominiumRepository.fetchMine();
  
  // 3. Navegar según resultado
  if (condos.isEmpty) {
    // Sin condominios → pantalla de "Sin comunidades"
    Navigator.pushNamedAndRemoveUntil('/no-communities', (_) => false);
  } else if (condos.length == 1) {
    // Solo 1 → auto-seleccionar
    await CondominiumRepository.switchTo(condos.first.id);
    Navigator.pushNamedAndRemoveUntil('/dashboard', (_) => false);
  } else {
    // Varios → selector
    Navigator.pushNamedAndRemoveUntil('/select-community', (_) => false);
  }
}
```

### 2. Modelo `Condominium` — Agregar `isOwner`

```dart
class Condominium {
  final int id;
  final String name;
  final String? logo;
  final String? logoUrl;
  final String roleName;
  final int roleId;
  final bool isOwner; // ← NUEVO

  Condominium.fromJson(Map<String, dynamic> json)
      : id = json['id'],
        name = json['name'],
        logo = json['logo'],
        logoUrl = json['logo_url'],
        roleName = json['role_name'] ?? '',
        roleId = json['role_id'] ?? 0,
        isOwner = (json['is_owner'] ?? 0) == 1; // ← NUEVO
}
```

### 3. Modelo `Admin` para la pantalla de gestión

```dart
class AdminUser {
  final int assignmentId;
  final int userId;
  final int roleId;
  final bool isOwner;
  final String firstName;
  final String lastName;
  final String email;
  final String roleName;

  String get fullName => '$firstName $lastName'.trim();
  String get initials =>
      '${firstName.isNotEmpty ? firstName[0] : ''}${lastName.isNotEmpty ? lastName[0] : ''}'
          .toUpperCase();

  AdminUser.fromJson(Map<String, dynamic> json)
      : assignmentId = json['assignment_id'] ?? 0,
        userId = json['user_id'] ?? 0,
        roleId = json['role_id'] ?? 0,
        isOwner = (json['is_owner'] ?? 0) == 1,
        firstName = json['first_name'] ?? '',
        lastName = json['last_name'] ?? '',
        email = json['email'] ?? '',
        roleName = json['role_name'] ?? '';
}
```

### 4. Pantalla de Gestión de Administradores

```dart
// Estructura sugerida para la pantalla
class AdminManagementScreen extends StatefulWidget { ... }

class _AdminManagementScreenState extends State<AdminManagementScreen> {
  List<AdminUser> admins = [];
  bool currentUserIsOwner = false;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadAdmins();
  }

  Future<void> _loadAdmins() async {
    setState(() => isLoading = true);
    try {
      final response = await dio.get('/condominiums/admins');
      final data = response.data['data'];
      admins = (data['admins'] as List)
          .map((a) => AdminUser.fromJson(a))
          .toList();
      currentUserIsOwner = data['current_user_is_owner'] ?? false;
    } catch (e) {
      // Manejar error
    }
    setState(() => isLoading = false);
  }

  Future<void> _promoteAdmin(AdminUser admin) async {
    final confirmed = await showConfirmDialog(
      title: '¿Hacer Fundador?',
      message: '${admin.fullName} tendrá los mismos permisos de Fundador.',
    );
    if (!confirmed) return;

    try {
      await dio.post('/condominiums/admins/promote', data: {
        'assignment_id': admin.assignmentId,
      });
      showSuccessSnackbar('Promovido a Fundador exitosamente');
      await _loadAdmins(); // Refrescar lista
    } on DioException catch (e) {
      showErrorSnackbar(e.response?.data['message'] ?? 'Error al promover');
    }
  }

  Future<void> _demoteAdmin(AdminUser admin) async {
    final confirmed = await showConfirmDialog(
      title: '¿Quitar rol de Fundador?',
      message: '${admin.fullName} perderá acceso a las demás comunidades.',
      isDestructive: true,
    );
    if (!confirmed) return;

    try {
      await dio.post('/condominiums/admins/demote', data: {
        'assignment_id': admin.assignmentId,
      });
      showSuccessSnackbar('Fundador revocado exitosamente');
      await _loadAdmins(); // Refrescar lista
    } on DioException catch (e) {
      showErrorSnackbar(e.response?.data['message'] ?? 'Error al revocar');
    }
  }
}
```

### 5. UI — Badges y Botones

```dart
// En el ListTile o widget de cada admin:
Widget _buildAdminTile(AdminUser admin) {
  final currentUserId = AuthService.currentUserId;
  final isSelf = admin.userId == currentUserId;

  return ListTile(
    leading: CircleAvatar(child: Text(admin.initials)),
    title: Text(admin.fullName),
    subtitle: Text(admin.email),
    trailing: Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        // Badge
        Container(
          padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(
            color: admin.isOwner ? Color(0xFFEFF6FF) : Color(0xFFF1F5F9),
            borderRadius: BorderRadius.circular(6),
          ),
          child: Text(
            admin.isOwner ? 'Fundador' : 'Administrador',
            style: TextStyle(
              color: admin.isOwner ? Color(0xFF1D4ED8) : Color(0xFF475569),
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
        SizedBox(width: 8),

        // Botón Promover (solo visible si yo soy fundador y el target NO es fundador)
        if (currentUserIsOwner && !admin.isOwner)
          IconButton(
            icon: Icon(Icons.shield, color: Color(0xFF8B5CF6)),
            tooltip: 'Hacer Fundador',
            onPressed: () => _promoteAdmin(admin),
          ),

        // Botón Revocar (solo si yo soy fundador, target ES fundador, y NO soy yo)
        if (currentUserIsOwner && admin.isOwner && !isSelf)
          IconButton(
            icon: Icon(Icons.remove_moderator, color: Color(0xFFF59E0B)),
            tooltip: 'Quitar Fundador',
            onPressed: () => _demoteAdmin(admin),
          ),
      ],
    ),
  );
}
```

---

## 🧪 Casos de Prueba

### Promote

| # | Escenario | Esperado |
|---|-----------|----------|
| 1 | Fundador promueve a co-admin | ✅ `is_owner=1` en condo actual + acceso a todos los condos del promotor |
| 2 | Fundador promueve a alguien que ya es fundador | ❌ 409 "Ya es Fundador" |
| 3 | Co-admin intenta promover | ❌ 403 "Solo un Fundador puede..." |
| 4 | `assignment_id` inexistente | ❌ 404 |

### Demote

| # | Escenario | Esperado |
|---|-----------|----------|
| 1 | Fundador revoca a otro fundador | ✅ `is_owner=0` en condo actual + DELETE de entries `is_owner=1` en otros condos |
| 2 | Fundador intenta auto-degradarse | ❌ 403 "No puedes quitarte el rol..." |
| 3 | Revocar al último fundador | ❌ 422 "Debe existir al menos un Fundador" |
| 4 | Revocar a un co-admin (no fundador) | ❌ 409 "No es Fundador" |

### Access Revoked

| # | Escenario | Esperado |
|---|-----------|----------|
| 1 | Admin eliminado intenta acceder con `X-Condo-Id` | ❌ 403 `ACCESS_REVOKED` |
| 2 | Admin accede a condo eliminado | ❌ 403 `COMMUNITY_DELETED` |
| 3 | Admin accede a condo suspendido | ❌ 403 `COMMUNITY_SUSPENDED` |
| 4 | Admin eliminado llama `GET /mine` | ✅ 200 pero sin ese condominio en la lista |

---

## 📁 Archivos Backend Modificados

| Archivo | Cambio |
|---------|--------|
| `app/Controllers/Admin/SettingsController.php` | `promoteToFounder()`, `demoteFounder()` — multi-condo sync |
| `app/Controllers/Api/V1/CondominiumApiController.php` | `listAdmins()`, `promoteToFounder()`, `demoteFounder()`, `mine()` +is_owner |
| `app/Services/Auth/LoginService.php` | `getUserTenants()` +is_owner en SELECT |
| `app/Filters/ApiAuthFilter.php` | Códigos `ACCESS_REVOKED` vs `COMMUNITY_DELETED` |
| `app/Config/Routes.php` | Rutas web + API para promote/demote |
| `app/Views/admin/settings.php` | UI web: botones promote/demote, XSS fix, CSS |
