// ═══════════════════════════════════════════════════════════════
// CAMBIOS EN condominium_service.dart PARA MULTI-UNIDAD
// 3 cambios: (A) switchUnit()  (B) limpiar unit al switchCondo  (C) guardar unit_id
// ═══════════════════════════════════════════════════════════════

// ─────────────────────────────────────────────────────────────
// CAMBIO A: Agregar método switchUnit() (después de switchCondominium)
// ─────────────────────────────────────────────────────────────

/*
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
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO B: En switchCondominium(), AGREGAR esta línea
//           DENTRO del bloque if (data['success'] == true)
//           ANTES de ApiService.clearCache();
// ─────────────────────────────────────────────────────────────

/*
          // ← AGREGAR ESTA LÍNEA:
          final prefs2 = await SharedPreferences.getInstance();
          await prefs2.remove('current_unit_id'); // Limpiar unidad al cambiar de condominio
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO C: En _updateLocalCondoData(), AGREGAR al final
//           (después de ApiService.clearCache())
// ─────────────────────────────────────────────────────────────

/*
    // ← AGREGAR ESTAS LÍNEAS al final de _updateLocalCondoData():
    if (condoData['unit_id'] != null) {
      await prefs.setInt('current_unit_id', int.parse(condoData['unit_id'].toString()));
    }
*/
