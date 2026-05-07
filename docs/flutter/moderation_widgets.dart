// lib/widgets/moderation_widgets.dart
// Apple Guideline 1.2 — Report & Block UI Components
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../services/moderation_service.dart';

class PostModerationMenu extends StatelessWidget {
  final int postId;
  final int authorUserId;
  final String authorName;
  final int currentUserId;
  final VoidCallback onBlocked;

  const PostModerationMenu({
    super.key,
    required this.postId,
    required this.authorUserId,
    required this.authorName,
    required this.currentUserId,
    required this.onBlocked,
  });

  @override
  Widget build(BuildContext context) {
    if (authorUserId == currentUserId || authorUserId <= 0) return const SizedBox.shrink();

    return PopupMenuButton<String>(
      icon: const Icon(Icons.more_horiz, color: Color(0xFF64748B), size: 24),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      elevation: 8,
      onSelected: (value) {
        if (value == 'report') {
          _showReportSheet(context);
        } else if (value == 'block') {
          _showBlockDialog(context);
        }
      },
      itemBuilder: (context) => [
        PopupMenuItem(
          value: 'report',
          child: Row(
            children: [
              const Icon(Icons.flag_outlined, color: Color(0xFF1E293B), size: 20),
              const SizedBox(width: 12),
              Text('Reportar publicación', style: GoogleFonts.inter(color: const Color(0xFF1E293B), fontWeight: FontWeight.w500)),
            ],
          ),
        ),
        PopupMenuItem(
          value: 'block',
          child: Row(
            children: [
              const Icon(Icons.block_outlined, color: Color(0xFFEF4444), size: 20),
              const SizedBox(width: 12),
              Text('Bloquear usuario', style: GoogleFonts.inter(color: const Color(0xFFEF4444), fontWeight: FontWeight.w500)),
            ],
          ),
        ),
      ],
    );
  }

  void _showReportSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => _ReportReasonSheet(
        title: 'Reportar Publicación',
        onSubmit: (reason, description) async {
          final ok = await ModerationService.reportContent(
            announcementId: postId,
            reportedUserId: authorUserId,
            reason: reason,
            description: description,
          );
          if (context.mounted) {
            ScaffoldMessenger.of(context).showSnackBar(SnackBar(
              content: Text(ok
                  ? 'Publicación reportada. Un administrador la revisará.'
                  : 'Error al enviar reporte. Intenta de nuevo.'),
              backgroundColor: ok ? const Color(0xFF10B981) : const Color(0xFFEF4444),
            ));
          }
        },
      ),
    );
  }

  void _showBlockDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text('Bloquear Usuario', style: GoogleFonts.inter(fontWeight: FontWeight.bold)),
        content: Text('¿Deseas bloquear a $authorName? Ya no verás sus publicaciones ni comentarios.', style: GoogleFonts.inter(fontSize: 14)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: Text('Cancelar', style: GoogleFonts.inter(color: const Color(0xFF64748B)))),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(ctx);
              final ok = await ModerationService.blockUser(authorUserId);
              if (ok) onBlocked();
              if (context.mounted) {
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                  content: Text(ok ? 'Has bloqueado a $authorName' : 'Error al bloquear usuario'),
                  backgroundColor: ok ? const Color(0xFF10B981) : const Color(0xFFEF4444),
                ));
              }
            },
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEF4444)),
            child: Text('Bloquear', style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }
}

class CommentModerationMenu extends StatelessWidget {
  final int commentId;
  final int authorUserId;
  final String authorName;
  final int currentUserId;
  final VoidCallback onBlocked;

  const CommentModerationMenu({
    super.key,
    required this.commentId,
    required this.authorUserId,
    required this.authorName,
    required this.currentUserId,
    required this.onBlocked,
  });

  @override
  Widget build(BuildContext context) {
    if (authorUserId == currentUserId || authorUserId <= 0) return const SizedBox.shrink();

    return PopupMenuButton<String>(
      icon: const Icon(Icons.more_vert, color: Color(0xFFCBD5E1), size: 18),
      padding: EdgeInsets.zero,
      constraints: const BoxConstraints(),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      elevation: 4,
      onSelected: (value) {
        if (value == 'report') {
          _showReportSheet(context);
        } else if (value == 'block') {
          _showBlockDialog(context);
        }
      },
      itemBuilder: (context) => [
        PopupMenuItem(
          value: 'report',
          child: Row(
            children: [
              const Icon(Icons.flag_outlined, color: Color(0xFF1E293B), size: 18),
              const SizedBox(width: 8),
              Text('Reportar', style: GoogleFonts.inter(color: const Color(0xFF1E293B), fontSize: 13, fontWeight: FontWeight.w500)),
            ],
          ),
        ),
        PopupMenuItem(
          value: 'block',
          child: Row(
            children: [
              const Icon(Icons.block_outlined, color: Color(0xFFEF4444), size: 18),
              const SizedBox(width: 8),
              Text('Bloquear usuario', style: GoogleFonts.inter(color: const Color(0xFFEF4444), fontSize: 13, fontWeight: FontWeight.w500)),
            ],
          ),
        ),
      ],
    );
  }

  void _showReportSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (ctx) => _ReportReasonSheet(
        title: 'Reportar Comentario',
        onSubmit: (reason, description) async {
          final ok = await ModerationService.reportContent(
            commentId: commentId,
            reportedUserId: authorUserId,
            reason: reason,
            description: description,
          );
          if (context.mounted) {
            ScaffoldMessenger.of(context).showSnackBar(SnackBar(
              content: Text(ok
                  ? 'Comentario reportado exitosamente.'
                  : 'Error al enviar reporte. Intenta de nuevo.'),
              backgroundColor: ok ? const Color(0xFF10B981) : const Color(0xFFEF4444),
            ));
          }
        },
      ),
    );
  }

  void _showBlockDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text('Bloquear Usuario', style: GoogleFonts.inter(fontWeight: FontWeight.bold)),
        content: Text('¿Deseas bloquear a $authorName? Ya no verás sus publicaciones ni comentarios.', style: GoogleFonts.inter(fontSize: 14)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: Text('Cancelar', style: GoogleFonts.inter(color: const Color(0xFF64748B)))),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(ctx);
              final ok = await ModerationService.blockUser(authorUserId);
              if (ok) onBlocked();
              if (context.mounted) {
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                  content: Text(ok ? 'Has bloqueado a $authorName' : 'Error al bloquear usuario'),
                  backgroundColor: ok ? const Color(0xFF10B981) : const Color(0xFFEF4444),
                ));
              }
            },
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEF4444)),
            child: Text('Bloquear', style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }
}

/// BottomSheet con selector de motivo de reporte
class _ReportReasonSheet extends StatefulWidget {
  final String title;
  final Future<void> Function(String reason, String? description) onSubmit;

  const _ReportReasonSheet({required this.title, required this.onSubmit});

  @override
  State<_ReportReasonSheet> createState() => _ReportReasonSheetState();
}

class _ReportReasonSheetState extends State<_ReportReasonSheet> {
  String? _selectedReason;
  final _descController = TextEditingController();
  bool _sending = false;

  static const _reasons = {
    'spam': '📩 Spam o publicidad',
    'harassment': '😡 Acoso o bullying',
    'offensive': '🚫 Contenido ofensivo',
    'misinformation': '❌ Información falsa',
    'other': '📝 Otro motivo',
  };

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.fromLTRB(24, 16, 24, MediaQuery.of(context).viewInsets.bottom + 24),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2)))),
          const SizedBox(height: 20),
          Text(widget.title, style: GoogleFonts.inter(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF1E293B))),
          const SizedBox(height: 4),
          Text('Selecciona el motivo del reporte:', style: GoogleFonts.inter(fontSize: 13, color: const Color(0xFF64748B))),
          const SizedBox(height: 16),
          ..._reasons.entries.map((e) => InkWell(
            onTap: () => setState(() => _selectedReason = e.key),
            borderRadius: BorderRadius.circular(10),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              margin: const EdgeInsets.only(bottom: 4),
              decoration: BoxDecoration(
                color: _selectedReason == e.key ? const Color(0xFF1D4C9D).withValues(alpha: 0.08) : Colors.transparent,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(
                  color: _selectedReason == e.key ? const Color(0xFF1D4C9D) : Colors.transparent,
                  width: 1.5,
                ),
              ),
              child: Row(
                children: [
                  Icon(
                    _selectedReason == e.key ? Icons.radio_button_checked : Icons.radio_button_unchecked,
                    color: _selectedReason == e.key ? const Color(0xFF1D4C9D) : const Color(0xFF94A3B8),
                    size: 20,
                  ),
                  const SizedBox(width: 12),
                  Text(e.value, style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w500)),
                ],
              ),
            ),
          )),
          if (_selectedReason == 'other') ...[
            const SizedBox(height: 8),
            TextField(
              controller: _descController,
              maxLines: 2,
              decoration: InputDecoration(
                hintText: 'Describe el motivo...',
                hintStyle: GoogleFonts.inter(color: Colors.grey[400], fontSize: 13),
                filled: true,
                fillColor: const Color(0xFFF8FAFC),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey[300]!)),
              ),
            ),
          ],
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            height: 48,
            child: ElevatedButton(
              onPressed: _selectedReason == null || _sending
                  ? null
                  : () async {
                      setState(() => _sending = true);
                      final nav = Navigator.of(context);
                      await widget.onSubmit(_selectedReason!, _descController.text.isNotEmpty ? _descController.text : null);
                      if (mounted) nav.pop();
                    },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF1D4C9D),
                disabledBackgroundColor: const Color(0xFFCBD5E1),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
              ),
              child: _sending
                  ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                  : Text('Enviar Reporte', style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15)),
            ),
          ),
        ],
      ),
    );
  }
}
