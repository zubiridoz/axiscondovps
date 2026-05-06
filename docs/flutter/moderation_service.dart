// lib/services/moderation_service.dart
// Apple Guideline 1.2 — Content Moderation Service
import 'package:flutter/foundation.dart';
import 'api_service.dart';

class ModerationService {
  static final Set<int> _blockedIds = {};
  static Set<int> get blockedIds => _blockedIds;
  static bool isBlocked(int userId) => _blockedIds.contains(userId);

  /// Cargar IDs bloqueados al iniciar (fire-and-forget)
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

  static Future<bool> reportContent({
    int? announcementId,
    int? commentId,
    required int reportedUserId,
    required String reason,
    String? description,
  }) async {
    final res = await ApiService.reportContent(
      announcementId: announcementId,
      commentId: commentId,
      reportedUserId: reportedUserId,
      reason: reason,
      description: description,
    );
    return res['status'] == 'success';
  }

  static Future<bool> blockUser(int blockedUserId) async {
    final res = await ApiService.blockUser(blockedUserId);
    if (res['status'] == 'success') {
      _blockedIds.add(blockedUserId);
      return true;
    }
    return false;
  }

  static Future<bool> unblockUser(int blockedUserId) async {
    final res = await ApiService.unblockUser(blockedUserId);
    if (res['status'] == 'success') {
      _blockedIds.remove(blockedUserId);
      return true;
    }
    return false;
  }

  static Future<List<Map<String, dynamic>>> getBlockedUsersList() async {
    final res = await ApiService.getBlockedUsers();
    if (res['status'] == 'success') {
      return List<Map<String, dynamic>>.from(res['data']?['blocked_users'] ?? []);
    }
    return [];
  }
}
