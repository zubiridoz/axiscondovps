// ═══════════════════════════════════════════════════════════════════════════
//  GUÍA DE INTEGRACIÓN EN wall_tab.dart
//  Cambios mínimos para cumplir Apple Guideline 1.2
// ═══════════════════════════════════════════════════════════════════════════

// ─────────────────────────────────────────────
// PASO 1: Imports (agregar al inicio de wall_tab.dart)
// ─────────────────────────────────────────────

// import 'package:axiscondo/services/moderation_service.dart';
// import 'package:axiscondo/widgets/moderation_widgets.dart';

// ─────────────────────────────────────────────
// PASO 2: Declarar en el State
// ─────────────────────────────────────────────

/*
class _AnnouncementWallScreenState extends State<AnnouncementWallScreen> {
  // ... tus variables existentes ...
  
  // ── NUEVO: Servicio de moderación ──
  late final ModerationService _moderationService;

  @override
  void initState() {
    super.initState();
    _moderationService = ModerationService(widget.apiService); // o como inicialices tu ApiService
    _moderationService.loadBlockedIds(); // Pre-cargar IDs bloqueados
    _loadAnnouncements();
  }
*/

// ─────────────────────────────────────────────
// PASO 3: En _buildAnnouncementCard(), agregar el menú ⋮
// ─────────────────────────────────────────────

// BUSCAR en tu _buildAnnouncementCard el Row del header que muestra
// avatar + nombre + fecha. Agregar PostModerationMenu al final:

/*
  Widget _buildAnnouncementCard(Map<String, dynamic> announcement) {
    // ... tu código existente del card ...
    
    // Dentro del Row del header, DESPUÉS del nombre/fecha, agregar:
    Row(
      children: [
        // Avatar existente...
        CircleAvatar(...),
        SizedBox(width: 10),
        // Nombre y fecha existentes...
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(authorName, ...),
              Text(timeAgo, ...),
            ],
          ),
        ),
        // ══════ NUEVO: Menú de moderación ══════
        PostModerationMenu(
          postId: announcement['id'] is int 
              ? announcement['id'] 
              : int.tryParse(announcement['id'].toString()) ?? 0,
          authorUserId: announcement['user_id'] is int 
              ? announcement['user_id'] 
              : int.tryParse(announcement['user_id'].toString()) ?? 0,
          authorName: '${announcement['first_name'] ?? ''} ${announcement['last_name'] ?? ''}'.trim(),
          currentUserId: _currentUserId, // tu variable con el ID del usuario logueado
          moderationService: _moderationService,
          isAdmin: _userIsAdmin, // tu variable que indica si es admin
          onBlocked: () {
            // Refrescar el feed completo para filtrar inmediatamente
            _loadAnnouncements();
          },
        ),
        // ══════════════════════════════════════
      ],
    ),
    // ... resto del card (contenido, botones like/comment) ...
  }
*/

// ─────────────────────────────────────────────
// PASO 4: En _buildCommentItem(), agregar menú ⋮ en comentarios
// ─────────────────────────────────────────────

/*
  Widget _buildCommentItem(Map<String, dynamic> comment) {
    // ... tu código existente ...
    
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Avatar del comentario existente...
          CircleAvatar(...),
          SizedBox(width: 8),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Nombre y contenido existentes...
                Text(authorName, ...),
                Text(comment['content'], ...),
              ],
            ),
          ),
          // ══════ NUEVO: Menú de moderación de comentario ══════
          CommentModerationMenu(
            commentId: comment['id'] is int 
                ? comment['id'] 
                : int.tryParse(comment['id'].toString()) ?? 0,
            authorUserId: comment['user_id'] is int 
                ? comment['user_id'] 
                : int.tryParse(comment['user_id'].toString()) ?? 0,
            authorName: '${comment['first_name'] ?? ''} ${comment['last_name'] ?? ''}'.trim(),
            currentUserId: _currentUserId,
            moderationService: _moderationService,
            onBlocked: () {
              // Refrescar el detalle del anuncio para filtrar los comentarios
              _loadAnnouncementDetail(_currentAnnouncementId);
            },
          ),
          // ══════════════════════════════════════════════════════
        ],
      ),
    );
  }
*/

// ─────────────────────────────────────────────
// PASO 5: Agregar entrada a la pantalla de Usuarios Bloqueados
// ─────────────────────────────────────────────

// En tu pantalla de Perfil/Configuración, agregar un ListTile:

/*
  ListTile(
    leading: const Icon(Icons.block, color: Colors.red),
    title: const Text('Usuarios bloqueados'),
    trailing: const Icon(Icons.chevron_right),
    onTap: () {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => BlockedUsersScreen(
            moderationService: _moderationService,
          ),
        ),
      );
    },
  ),
*/

// ─────────────────────────────────────────────
// PASO 6: Adaptar las llamadas HTTP en moderation_service.dart
// ─────────────────────────────────────────────

// El ModerationService usa `apiService.post()` y `apiService.get()`.
// Adapta estas llamadas al patrón exacto de tu ApiService.
//
// Si tu ApiService usa Dio directamente:
//
//   final response = await apiService.dio.post(
//     '/api/v1/moderation/report',
//     data: body,
//     options: Options(headers: {'Authorization': 'Bearer $token'}),
//   );
//   return response.data;
//
// Si tu ApiService tiene un wrapper genérico:
//
//   final response = await apiService.post('moderation/report', body: body);
//   return response;
//
// Asegúrate de que:
//   1. El Content-Type sea application/json
//   2. El header Authorization tenga el token del usuario
//   3. El header X-Tenant-ID tenga el ID del condominio activo

// ─────────────────────────────────────────────
// RESUMEN DE ARCHIVOS
// ─────────────────────────────────────────────

/*
ARCHIVOS NUEVOS EN FLUTTER:
  lib/services/moderation_service.dart  → Lógica de API + cache de bloqueados
  lib/widgets/moderation_widgets.dart   → PostModerationMenu, CommentModerationMenu,
                                          showReportBottomSheet, showBlockDialog,
                                          BlockedUsersScreen

ARCHIVOS A MODIFICAR EN FLUTTER:
  lib/screens/wall_tab.dart             → Agregar imports, instanciar ModerationService,
                                          agregar PostModerationMenu en _buildAnnouncementCard,
                                          agregar CommentModerationMenu en _buildCommentItem
  lib/screens/profile_screen.dart       → Agregar entrada a BlockedUsersScreen

ENDPOINTS BACKEND (YA IMPLEMENTADOS):
  POST   /api/v1/moderation/report         → Reportar contenido
  POST   /api/v1/moderation/block          → Bloquear usuario
  POST   /api/v1/moderation/unblock        → Desbloquear usuario
  GET    /api/v1/moderation/blocked-users   → Listar bloqueados

FILTRADO AUTOMÁTICO (YA IMPLEMENTADO EN BACKEND):
  - GET /api/v1/announcements             → Excluye posts de usuarios bloqueados
  - GET /api/v1/announcements/:id         → Excluye comentarios de usuarios bloqueados
*/
