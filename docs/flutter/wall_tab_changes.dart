// ═══════════════════════════════════════════════════════════════════════════
//  CAMBIOS EXACTOS PARA wall_tab.dart
//  Copia y pega cada sección en el lugar indicado
// ═══════════════════════════════════════════════════════════════════════════

// ─────────────────────────────────────────────
// CAMBIO 1: Agregar estos imports al inicio del archivo
//           (después de import '../../../services/html_utils.dart';)
// ─────────────────────────────────────────────

import '../../../services/moderation_service.dart';
import '../../../widgets/moderation_widgets.dart';


// ─────────────────────────────────────────────
// CAMBIO 2: En _initializeData(), agregar ModerationService.loadBlockedIds()
//           REEMPLAZAR el método completo con esta versión:
// ─────────────────────────────────────────────

  Future<void> _initializeData() async {
    ModerationService.loadBlockedIds(); // 🛡️ Apple 1.2: Pre-cargar IDs bloqueados
    await Future.wait([
      _loadAuthHeaders(),
      _loadAnnouncements(),
    ]);
    _checkAdminStatus();
  }


// ─────────────────────────────────────────────
// CAMBIO 3: En _buildAnnouncementCard(), agregar helper + menú de moderación
//
// 3a. Agregar este helper en AnnouncementWallScreenState (antes de _buildAnnouncementCard):
// ─────────────────────────────────────────────

  int _safeInt(dynamic v) => v is int ? v : int.tryParse(v?.toString() ?? '') ?? 0;


// ─────────────────────────────────────────────
// 3b. En _buildAnnouncementCard(), buscar este bloque:
//
//     // Lógica Senior: Admin ve acciones en TODOS los posts; residente solo en los suyos
//     if (isAdmin || (widget.userData?['id']?.toString() ?? '') == (...))
//       IconButton(...)
//
// Y REEMPLAZARLO con este código completo:
// ─────────────────────────────────────────────

                      // Lógica Senior: Admin ve acciones en TODOS los posts; residente solo en los suyos
                      if (isAdmin || (widget.userData?['id']?.toString() ?? '') == (item['user_id']?.toString() ?? item['author_id']?.toString() ?? ''))
                        IconButton(
                          padding: EdgeInsets.zero,
                          constraints: const BoxConstraints(),
                          visualDensity: VisualDensity.compact,
                          icon: const Icon(Icons.more_horiz, color: Color(0xFF1D4C9D), size: 28),
                          onPressed: () => _showAnnouncementActions(item),
                        ),
                      // 🛡️ Apple Guideline 1.2: Menú de moderación (report/block) para posts de OTROS usuarios
                      if ((widget.userData?['id']?.toString() ?? '') != (item['user_id']?.toString() ?? item['author_id']?.toString() ?? ''))
                        PostModerationMenu(
                          postId: _safeInt(item['id']),
                          authorUserId: _safeInt(item['user_id'] ?? item['author_id'] ?? item['created_by']),
                          authorName: authorName,
                          currentUserId: _safeInt(widget.userData?['id']),
                          onBlocked: () => _loadAnnouncements(),
                        ),


// ─────────────────────────────────────────────
// CAMBIO 4: En AnnouncementDetailScreen, pasar userData para saber quién es el usuario actual
//
// 4a. Modificar la clase AnnouncementDetailScreen para recibir userData:
// ─────────────────────────────────────────────

class AnnouncementDetailScreen extends StatefulWidget {
  final dynamic announcement;
  final VoidCallback? onBack;
  final Map<String, dynamic>? userData; // 🛡️ NUEVO
  const AnnouncementDetailScreen({super.key, required this.announcement, this.onBack, this.userData});

  @override
  State<AnnouncementDetailScreen> createState() => _AnnouncementDetailScreenState();
}


// ─────────────────────────────────────────────
// 4b. Donde se instancia AnnouncementDetailScreen en _buildMainContent(),
//     agregar userData:
// ─────────────────────────────────────────────

    // ANTES:
    return AnnouncementDetailScreen(
      announcement: _selectedAnnouncement,
      onBack: () { ... },
    );

    // DESPUÉS:
    return AnnouncementDetailScreen(
      announcement: _selectedAnnouncement,
      userData: widget.userData, // 🛡️ NUEVO
      onBack: () {
        setState(() => _selectedAnnouncement = null);
        _loadAnnouncements();
      },
    );


// ─────────────────────────────────────────────
// 4c. En _AnnouncementDetailScreenState, agregar helper:
// ─────────────────────────────────────────────

  int _safeInt(dynamic v) => v is int ? v : int.tryParse(v?.toString() ?? '') ?? 0;


// ─────────────────────────────────────────────
// 4d. En _buildCommentItem(), buscar el Row que contiene el avatar y la burbuja.
//     Agregar el CommentModerationMenu DESPUÉS del Expanded(child: Column(...))
//     dentro del Row children:
//
//  ANTES:
//    Row(
//      children: [
//        Container(... CircleAvatar ...),
//        SizedBox(width: 12),
//        Expanded(child: Column(... burbuja ...)),
//      ],
//    )
//
//  DESPUÉS:
// ─────────────────────────────────────────────

          return Container(
            margin: const EdgeInsets.only(bottom: 16),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Avatar con borde sutil para profundidad
                Container(
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.white, width: 2),
                    boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 4)],
                  ),
                  child: CircleAvatar(
                    radius: 18,
                    backgroundColor: const Color(0xFF1D4C9D).withValues(alpha: 0.1),
                    backgroundImage: (headers != null && avatarUrl.isNotEmpty) ? NetworkImage(avatarUrl, headers: headers) : null,
                    child: (headers == null || avatarUrl.isEmpty)
                        ? Text(initials, style: const TextStyle(color: Color(0xFF1D4C9D), fontSize: 13, fontWeight: FontWeight.bold))
                        : null,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Burbuja de comentario estilo SaaS Premium
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF1F5F9),
                          borderRadius: const BorderRadius.only(
                            topRight: Radius.circular(16),
                            bottomLeft: Radius.circular(16),
                            bottomRight: Radius.circular(16),
                          ),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(fullName, style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF1E293B))),
                                Text(c['time_ago'] ?? 'ahora', style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF94A3B8))),
                              ],
                            ),
                            const SizedBox(height: 6),
                            Text(c['comment'] ?? c['content'] ?? '', style: GoogleFonts.inter(fontSize: 14, color: const Color(0xFF475569), height: 1.4)),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
                // 🛡️ Apple Guideline 1.2: Menú de moderación para comentarios
                CommentModerationMenu(
                  commentId: _safeInt(c['id']),
                  authorUserId: _safeInt(c['user_id']),
                  authorName: fullName,
                  currentUserId: _safeInt(widget.userData?['id']),
                  onBlocked: () => _loadDetails(),
                ),
              ],
            ),
          );
