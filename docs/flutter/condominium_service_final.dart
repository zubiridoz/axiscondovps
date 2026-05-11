import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'api_service.dart';

/// Servicio dedicado a las operaciones de condominios:
/// - Obtener condominios del usuario
/// - Cambiar condominio activo (switch tenant)
/// - Cambiar unidad activa (switch unit) ← NUEVO
class CondominiumService {
  /// Obtiene la lista de condominios del usuario autenticado.
  static Future<Map<String, dynamic>> getMyCondominiums() async {
    try {
      final headers = await ApiService.getAuthHeaders();
      final url = Uri.parse('${ApiService.baseUrl}/condominiums/mine');
      final response = await http.get(url, headers: headers);

      if (response.statusCode == 200) {
        return json.decode(response.body);
      }
      return {'status': 'error', 'message': 'Error ${response.statusCode}'};
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  /// Cambia el condominio activo del usuario.
  /// Retorna los datos del nuevo condominio (nombre, rol, unidad, etc.)
  static Future<Map<String, dynamic>> switchCondominium(int condominiumId) async {
    try {
      final headers = await ApiService.getAuthHeaders();
      final url = Uri.parse('${ApiService.baseUrl}/condominiums/switch');
      final response = await http.post(
        url,
        headers: {...headers, 'Content-Type': 'application/json'},
        body: json.encode({'condominium_id': condominiumId}),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        // Backend ahora responde: { success: true, condominium: { id, name, role, ... } }
        if (data['success'] == true && data['condominium'] != null) {
          await _updateLocalCondoData(data['condominium']);

          // ✅ MULTI-UNIDAD: Limpiar unidad activa al cambiar de condominio
          final prefs = await SharedPreferences.getInstance();
          await prefs.remove('current_unit_id');
          
          // ✅ SINCRONIZACIÓN SENIOR: Usar el método correcto y limpiar caché
          ApiService.clearCache();
        }

        return data;
      }
      return {'success': false, 'message': 'Error ${response.statusCode}'};
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  /// Cambia la unidad activa del residente (100% client-side).
  /// El backend es stateless — solo lee X-Unit-Id del header.
  static Future<void> switchUnit(int newUnitId) async {
    final prefs = await SharedPreferences.getInstance();
    
    // 1. Guardar nueva unidad activa
    await prefs.setInt('current_unit_id', newUnitId);
    
    // 2. Limpiar datos cacheados que dependen de unit_id
    await prefs.remove('cached_profile');
    await prefs.remove('cached_unit_data');
    await prefs.remove('cached_qr_list');
    await prefs.remove('cached_balance');
    await prefs.remove('cached_statement');
    await prefs.remove('cached_access_logs');
    
    // 3. Forzar reconstrucción de headers con nuevo X-Unit-Id
    ApiService.clearCache();
  }

  /// Actualiza los datos locales del condominio activo en SharedPreferences.
  static Future<void> _updateLocalCondoData(Map<String, dynamic> condoData) async {
    final prefs = await SharedPreferences.getInstance();

    // Actualizar userData con los nuevos datos del condominio
    final String? userJson = prefs.getString('userData');
    if (userJson != null) {
      final userData = json.decode(userJson) as Map<String, dynamic>;
      
      // Normalización de llaves (SaaS Compatibility)
      userData['condo_name'] = condoData['name'] ?? condoData['condo_name'] ?? userData['condo_name'];
      userData['unit_number'] = condoData['unit_number'] ?? condoData['number'] ?? userData['unit_number'];
      userData['unit_id'] = condoData['unit_id'] ?? condoData['id_unidad'];
      
      // ✅ SINCRONIZACIÓN DE ROL: Asegurar que el nuevo rol se guarde en userData para que el Muro lo detecte
      if (condoData['role'] != null) userData['role'] = condoData['role'];
      if (condoData['role_name'] != null) userData['role_name'] = condoData['role_name'];
      if (condoData['is_admin'] != null) userData['is_admin'] = condoData['is_admin'];
      
      await prefs.setString('userData', json.encode(userData));
    }

    // Guardar el ID del condominio activo para X-Condo-Id
    final int newCondoId = int.parse((condoData['id'] ?? condoData['condominium_id'] ?? 0).toString());
    await prefs.setInt('current_condominium_id', newCondoId);
    final roleStr = condoData['role']?.toString().toLowerCase() ?? 'resident';
    await prefs.setString('userRole', roleStr);
    await prefs.setString('userType', roleStr);
    
    // Forzar limpieza de headers de nuevo por seguridad
    ApiService.clearCache();

    // ✅ MULTI-UNIDAD: Guardar unit_id si viene en la respuesta del switch
    if (condoData['unit_id'] != null) {
      await prefs.setInt('current_unit_id', int.parse(condoData['unit_id'].toString()));
    }
  }

  /// Obtiene el ID del condominio activo desde SharedPreferences.
  static Future<int?> getCurrentCondominiumId() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getInt('current_condominium_id');
  }
}
