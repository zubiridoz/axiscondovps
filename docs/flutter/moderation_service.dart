// lib/services/moderation_service.dart
// Apple Guideline 1.2 — Content Moderation Service
// ⚠️ REEMPLAZA COMPLETAMENTE tu moderation_service.dart actual
import 'package:flutter/foundation.dart';
import 'api_service.dart';

class ModerationService {
  static final Set<int> _blockedIds = {};
  static Set<int> get blockedIds => _blockedIds;
  static bool isBlocked(int userId) => _blockedIds.contains(userId);

  /// Cargar IDs bloqueados desde el servidor al iniciar
  static Future<void> loadBlockedIds() async {
    try {
      final res = await ApiService.getBlockedUsers();
      if (res['status'] == 'success') {
        final list = res['data']?['blocked_users'] ?? [];
        _blockedIds.clear();
        for (final u in list) {
          final id = u['blocked_user_id'];
          if (id != null) _blockedIds.add(int.parse(id.toString()));
        }
      }
    } catch (e) {
      debugPrint('[ModerationService] Error loading blocked IDs: $e');
    }
  }

  /// Reportar contenido al servidor — retorna true si fue exitoso
  static Future<bool> reportContent({
    int? announcementId,
    int? commentId,
    required int reportedUserId,
    required String reason,
    String? description,
  }) async {
    try {
      final res = await ApiService.reportContent(
        announcementId: announcementId,
        commentId: commentId,
        reportedUserId: reportedUserId,
        reason: reason,
        description: description,
      );
      debugPrint('[ModerationService] reportContent response: $res');
      return res['status'] == 'success';
    } catch (e) {
      debugPrint('[ModerationService] reportContent error: $e');
      return false;
    }
  }

  /// Bloquear usuario en el servidor + cache local — retorna true si fue exitoso
  static Future<bool> blockUser(int userId) async {
    try {
      final res = await ApiService.blockUser(userId);
      debugPrint('[ModerationService] blockUser response: $res');
      if (res['status'] == 'success') {
        _blockedIds.add(userId);
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('[ModerationService] blockUser error: $e');
      return false;
    }
  }

  /// Desbloquear usuario en el servidor + quitar del cache local
  static Future<bool> unblockUser(int userId) async {
    try {
      final res = await ApiService.unblockUser(userId);
      debugPrint('[ModerationService] unblockUser response: $res');
      if (res['status'] == 'success') {
        _blockedIds.remove(userId);
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('[ModerationService] unblockUser error: $e');
      return false;
    }
  }

  /// Obtener lista completa de usuarios bloqueados (para la pantalla de gestión)
  static Future<List<Map<String, dynamic>>> getBlockedUsersList() async {
    try {
      final res = await ApiService.getBlockedUsers();
      if (res['status'] == 'success') {
        final list = res['data']?['blocked_users'] ?? [];
        return List<Map<String, dynamic>>.from(list);
      }
      return [];
    } catch (e) {
      debugPrint('[ModerationService] getBlockedUsersList error: $e');
      return [];
    }
  }
}
