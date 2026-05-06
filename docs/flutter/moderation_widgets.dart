// lib/widgets/moderation_widgets.dart
// Apple Guideline 1.2 — Moderation UI Widgets
import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import 'package:google_fonts/google_fonts.dart';
import '../services/moderation_service.dart';

// ─────────────────────────────────────────────
// 1. MENÚ DE MODERACIÓN (⋮) para Posts
// ─────────────────────────────────────────────

class PostModerationMenu extends StatelessWidget {
  final int postId;
  final int authorUserId;
  final String authorName;
  final int currentUserId;
  final VoidCallback? onBlocked;

  const PostModerationMenu({
    Key? key,
    required this.postId,
    required this.authorUserId,
    required this.authorName,
    required this.currentUserId,
    this.onBlocked,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    if (authorUserId == currentUserId || authorUserId == 0) return const SizedBox.shrink();
    return IconButton(
      padding: EdgeInsets.zero,
      constraints: const BoxConstraints(minWidth: 28, minHeight: 28),
      visualDensity: VisualDensity.compact,
      icon: Icon(Icons.more_vert, color: Colors.grey[500], size: 20),
      onPressed: () => _showMenu(context),
    );
  }

  void _showMenu(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2))),
            const SizedBox(height: 16),
            Text('Acciones', style: GoogleFonts.inter(color: const Color(0xFF64748B), fontSize: 13, fontWeight: FontWeight.w500)),
            const SizedBox(height: 8),
            const Divider(height: 1),
            ListTile(
              leading: const Icon(Icons.flag_outlined, color: Colors.orange, size: 22),
              title: Text('Reportar contenido', style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 15)),
              subtitle: Text('Marcar como inapropiado', style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF94A3B8))),
              onTap: () {
                Navigator.pop(ctx);
                showReportSheet(context: context, announcementId: postId, reportedUserId: authorUserId);
              },
            ),
            const Divider(height: 1),
            ListTile(
              leading: const Icon(Icons.block, color: Color(0xFFEF4444), size: 22),
              title: Text('Bloquear a $authorName', style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 15, color: const Color(0xFFEF4444))),
              subtitle: Text('No verás más su contenido', style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF94A3B8))),
              onTap: () {
                Navigator.pop(ctx);
                showBlockDialog(context: context, blockedUserId: authorUserId, blockedUserName: authorName, onBlocked: onBlocked);
              },
            ),
            const Divider(height: 1),
            ListTile(
              title: Center(child: Text('Cancelar', style: GoogleFonts.inter(color: const Color(0xFF1E293B), fontWeight: FontWeight.bold, fontSize: 16))),
              onTap: () => Navigator.pop(ctx),
            ),
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────
// 2. MENÚ DE MODERACIÓN para Comentarios
// ─────────────────────────────────────────────

class CommentModerationMenu extends StatelessWidget {
  final int commentId;
  final int authorUserId;
  final String authorName;
  final int currentUserId;
  final VoidCallback? onBlocked;

  const CommentModerationMenu({
    Key? key,
    required this.commentId,
    required this.authorUserId,
    required this.authorName,
    required this.currentUserId,
    this.onBlocked,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    if (authorUserId == currentUserId || authorUserId == 0) return const SizedBox.shrink();
    return GestureDetector(
      onTap: () => _showMenu(context),
      child: Padding(
        padding: const EdgeInsets.only(left: 4, top: 8),
        child: Icon(Icons.more_vert, size: 16, color: Colors.grey[400]),
      ),
    );
  }

  void _showMenu(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
        decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2))),
            const SizedBox(height: 16),
            const Divider(height: 1),
            ListTile(
              leading: const Icon(Icons.flag_outlined, color: Colors.orange, size: 22),
              title: Text('Reportar comentario', style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 15)),
              onTap: () {
                Navigator.pop(ctx);
                showReportSheet(context: context, commentId: commentId, reportedUserId: authorUserId);
              },
            ),
            const Divider(height: 1),
            ListTile(
              leading: const Icon(Icons.block, color: Color(0xFFEF4444), size: 22),
              title: Text('Bloquear a $authorName', style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 15, color: const Color(0xFFEF4444))),
              onTap: () {
                Navigator.pop(ctx);
                showBlockDialog(context: context, blockedUserId: authorUserId, blockedUserName: authorName, onBlocked: onBlocked);
              },
            ),
            const Divider(height: 1),
            ListTile(
              title: Center(child: Text('Cancelar', style: GoogleFonts.inter(color: const Color(0xFF1E293B), fontWeight: FontWeight.bold, fontSize: 16))),
              onTap: () => Navigator.pop(ctx),
            ),
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────
// 3. BOTTOM SHEET — Selección de Motivo de Reporte
// ─────────────────────────────────────────────

void showReportSheet({
  required BuildContext context,
  int? announcementId,
  int? commentId,
  required int reportedUserId,
}) {
  final reasons = [
    {'key': 'spam', 'label': 'Spam', 'icon': Icons.report_gmailerrorred},
    {'key': 'harassment', 'label': 'Acoso', 'icon': Icons.person_off},
    {'key': 'offensive', 'label': 'Contenido ofensivo', 'icon': Icons.warning_amber},
    {'key': 'misinformation', 'label': 'Información falsa', 'icon': Icons.fact_check_outlined},
    {'key': 'other', 'label': 'Otro', 'icon': Icons.more_horiz},
  ];

  showModalBottomSheet(
    context: context,
    isScrollControlled: true,
    backgroundColor: Colors.transparent,
    builder: (ctx) => Container(
      decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 24),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(width: 40, height: 4, margin: const EdgeInsets.only(bottom: 20), decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2))),
          Text('¿Por qué reportas este contenido?', style: GoogleFonts.inter(fontSize: 17, fontWeight: FontWeight.w700, color: const Color(0xFF1E293B))),
          const SizedBox(height: 4),
          Text('Tu reporte es anónimo para el autor.', style: GoogleFonts.inter(fontSize: 13, color: const Color(0xFF94A3B8))),
          const SizedBox(height: 16),
          ...reasons.map((r) => Container(
            margin: const EdgeInsets.only(bottom: 6),
            child: ListTile(
              leading: Icon(r['icon'] as IconData, color: const Color(0xFF64748B)),
              title: Text(r['label'] as String, style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 15)),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              tileColor: const Color(0xFFF8FAFC),
              onTap: () async {
                Navigator.pop(ctx);
                final success = await ModerationService.reportContent(
                  announcementId: announcementId,
                  commentId: commentId,
                  reportedUserId: reportedUserId,
                  reason: r['key'] as String,
                );
                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                    content: Row(children: [
                      Icon(success ? Icons.check_circle_outline_rounded : Icons.error_outline, color: Colors.white, size: 20),
                      const SizedBox(width: 12),
                      Expanded(child: Text(
                        success ? 'Reporte enviado. Gracias por ayudar a mantener la comunidad segura.' : 'No se pudo enviar el reporte.',
                        style: GoogleFonts.inter(fontWeight: FontWeight.w600, color: Colors.white),
                      )),
                    ]),
                    backgroundColor: success ? const Color(0xFF10B981) : const Color(0xFFEF4444),
                    behavior: SnackBarBehavior.floating,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    margin: const EdgeInsets.all(20),
                    duration: const Duration(seconds: 3),
                  ));
                }
              },
            ),
          )),
        ],
      ),
    ),
  );
}

// ─────────────────────────────────────────────
// 4. DIÁLOGO — Confirmación de Bloqueo
// ─────────────────────────────────────────────

void showBlockDialog({
  required BuildContext context,
  required int blockedUserId,
  required String blockedUserName,
  VoidCallback? onBlocked,
}) {
  showCupertinoDialog(
    context: context,
    builder: (ctx) => CupertinoAlertDialog(
      title: Text('Bloquear a $blockedUserName'),
      content: const Padding(
        padding: EdgeInsets.only(top: 8),
        child: Text('Ya no verás sus publicaciones ni comentarios en el muro.\n\nPuedes desbloquear en cualquier momento desde tu perfil.'),
      ),
      actions: [
        CupertinoDialogAction(child: const Text('Cancelar'), onPressed: () => Navigator.pop(ctx)),
        CupertinoDialogAction(
          isDestructiveAction: true,
          child: const Text('Bloquear'),
          onPressed: () async {
            Navigator.pop(ctx);
            final success = await ModerationService.blockUser(blockedUserId);
            if (context.mounted) {
              ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                content: Row(children: [
                  Icon(success ? Icons.check_circle_outline_rounded : Icons.error_outline, color: Colors.white, size: 20),
                  const SizedBox(width: 12),
                  Expanded(child: Text(
                    success ? '$blockedUserName ha sido bloqueado.' : 'No se pudo bloquear al usuario.',
                    style: GoogleFonts.inter(fontWeight: FontWeight.w600, color: Colors.white),
                  )),
                ]),
                backgroundColor: success ? const Color(0xFF10B981) : const Color(0xFFEF4444),
                behavior: SnackBarBehavior.floating,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                margin: const EdgeInsets.all(20),
              ));
              if (success && onBlocked != null) onBlocked();
            }
          },
        ),
      ],
    ),
  );
}

// ─────────────────────────────────────────────
// 5. PANTALLA — Usuarios Bloqueados
// ─────────────────────────────────────────────

class BlockedUsersScreen extends StatefulWidget {
  const BlockedUsersScreen({Key? key}) : super(key: key);
  @override
  State<BlockedUsersScreen> createState() => _BlockedUsersScreenState();
}

class _BlockedUsersScreenState extends State<BlockedUsersScreen> {
  List<Map<String, dynamic>> _blockedUsers = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _blockedUsers = await ModerationService.getBlockedUsersList();
    if (mounted) setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: Text('Usuarios bloqueados', style: GoogleFonts.inter(fontWeight: FontWeight.bold, color: const Color(0xFF1E293B))),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Color(0xFF1D4C9D)),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator(color: Color(0xFF1D4C9D)))
          : _blockedUsers.isEmpty
              ? Center(
                  child: Column(mainAxisSize: MainAxisSize.min, children: [
                    Icon(Icons.check_circle_outline, size: 64, color: Colors.grey[400]),
                    const SizedBox(height: 12),
                    Text('No tienes usuarios bloqueados', style: GoogleFonts.inter(fontSize: 16, color: const Color(0xFF64748B))),
                  ]),
                )
              : ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: _blockedUsers.length,
                  separatorBuilder: (_, __) => const Divider(height: 1),
                  itemBuilder: (ctx, i) {
                    final user = _blockedUsers[i];
                    final name = '${user['first_name'] ?? ''} ${user['last_name'] ?? ''}'.trim();
                    final blockedId = int.tryParse(user['blocked_user_id'].toString()) ?? 0;
                    return ListTile(
                      leading: CircleAvatar(
                        backgroundColor: const Color(0xFF1D4C9D).withValues(alpha: 0.1),
                        child: Text(name.isNotEmpty ? name[0].toUpperCase() : '?', style: GoogleFonts.inter(fontWeight: FontWeight.bold, color: const Color(0xFF1D4C9D))),
                      ),
                      title: Text(name.isNotEmpty ? name : 'Usuario', style: GoogleFonts.inter(fontWeight: FontWeight.w600)),
                      trailing: TextButton(
                        child: Text('Desbloquear', style: GoogleFonts.inter(color: const Color(0xFF1D4C9D), fontWeight: FontWeight.bold)),
                        onPressed: () async {
                          final success = await ModerationService.unblockUser(blockedId);
                          if (success && mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                              content: Text('Usuario desbloqueado', style: GoogleFonts.inter(fontWeight: FontWeight.w600)),
                              backgroundColor: const Color(0xFF10B981),
                              behavior: SnackBarBehavior.floating,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            ));
                            _load();
                          }
                        },
                      ),
                    );
                  },
                ),
    );
  }
}
