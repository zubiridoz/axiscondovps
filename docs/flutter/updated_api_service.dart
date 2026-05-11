// ═══════════════════════════════════════════════════════════════
// CAMBIOS EN api_service.dart PARA MULTI-UNIDAD
// Solo 2 cambios: (A) Header X-Unit-Id  (B) Método getMyUnits()
// ═══════════════════════════════════════════════════════════════

// ─────────────────────────────────────────────────────────────
// CAMBIO A: Reemplazar _getHeadersInternal() completo
// ─────────────────────────────────────────────────────────────
// BUSCAR este método y reemplazarlo:

/*
  static Future<Map<String, String>> _getHeadersInternal() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');
      final condoId = prefs.getInt('current_condominium_id');
      final unitId = prefs.getInt('current_unit_id'); // ← NUEVO
      _cachedHeaders = {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
        if (condoId != null && condoId > 0) 'X-Condo-Id': condoId.toString(),
        if (unitId != null && unitId > 0) 'X-Unit-Id': unitId.toString(), // ← NUEVO
      };
      return _cachedHeaders!;
    } catch (e) {
      _headersFuture = null;
      rethrow;
    }
  }
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO B: Agregar este método NUEVO (junto a getMyUnit)
// ─────────────────────────────────────────────────────────────

/*
  /// GET /api/v1/condominiums/my-units
  /// Retorna las unidades del residente en el condominio activo.
  static Future<Map<String, dynamic>> getMyUnits() async {
    try {
      final headers = await getAuthHeaders();
      final response = await http.get(
        Uri.parse('$baseUrl/condominiums/my-units'),
        headers: headers,
      );
      return json.decode(response.body);
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }
*/
