// ═══════════════════════════════════════════════════════════════
// AGREGAR ESTOS MÉTODOS AL FINAL DE api_service.dart
// (antes del cierre de la clase ApiService)
// ═══════════════════════════════════════════════════════════════

  // --- MÉTODOS DE MODERACIÓN (Apple Guideline 1.2) ---

  static Future<Map<String, dynamic>> reportContent({
    int? announcementId,
    int? commentId,
    required int reportedUserId,
    required String reason,
    String? description,
  }) async {
    try {
      final headers = await getAuthHeaders();
      headers['Content-Type'] = 'application/json';
      final body = <String, dynamic>{
        'reported_user_id': reportedUserId,
        'reason': reason,
      };
      if (announcementId != null) body['announcement_id'] = announcementId;
      if (commentId != null) body['comment_id'] = commentId;
      if (description != null && description.isNotEmpty) body['description'] = description;

      final response = await http.post(
        Uri.parse('$baseUrl/moderation/report'),
        headers: headers,
        body: json.encode(body),
      );
      return json.decode(response.body);
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> blockUser(int blockedUserId) async {
    try {
      final headers = await getAuthHeaders();
      headers['Content-Type'] = 'application/json';
      final response = await http.post(
        Uri.parse('$baseUrl/moderation/block'),
        headers: headers,
        body: json.encode({'blocked_user_id': blockedUserId}),
      );
      return json.decode(response.body);
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> unblockUser(int blockedUserId) async {
    try {
      final headers = await getAuthHeaders();
      headers['Content-Type'] = 'application/json';
      final response = await http.post(
        Uri.parse('$baseUrl/moderation/unblock'),
        headers: headers,
        body: json.encode({'blocked_user_id': blockedUserId}),
      );
      return json.decode(response.body);
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> getBlockedUsers() async {
    try {
      final headers = await getAuthHeaders();
      final response = await http.get(
        Uri.parse('$baseUrl/moderation/blocked-users'),
        headers: headers,
      );
      return json.decode(response.body);
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }
