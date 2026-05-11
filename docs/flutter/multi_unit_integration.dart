/// ============================================================
/// MULTI-UNIT FLUTTER INTEGRATION GUIDE
/// ============================================================
/// 
/// Este archivo contiene los cambios necesarios en el proyecto
/// Flutter para soportar multi-unidad.
/// 
/// Los cambios son en 3 archivos:
///   1. api_service.dart    — Headers + getMyUnits()
///   2. condominium_service.dart — Orquestación del switch
///   3. resident_main_navigation.dart — UI Drawer

// ============================================================
// 1. API SERVICE — Agregar X-Unit-Id header
// ============================================================
// 
// En el método getAuthHeaders() o equivalente que genera headers
// para cada request HTTP, agregar:

/*
  Future<Map<String, String>> getAuthHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token') ?? '';
    final condoId = prefs.getInt('condominium_id') ?? 0;
    final unitId = prefs.getInt('current_unit_id'); // ← NUEVO
    
    final headers = {
      'Authorization': 'Bearer $token',
      'X-Condo-Id': condoId.toString(),
      'Content-Type': 'application/json',
    };
    
    // Solo enviar si existe (backward compatible)
    if (unitId != null && unitId > 0) {
      headers['X-Unit-Id'] = unitId.toString();
    }
    
    return headers;
  }
*/

// Nuevo método para obtener las unidades:
/*
  Future<Map<String, dynamic>> getMyUnits() async {
    final headers = await getAuthHeaders();
    final response = await http.get(
      Uri.parse('$baseUrl/api/v1/condominiums/my-units'),
      headers: headers,
    );
    return jsonDecode(response.body);
  }
*/


// ============================================================
// 2. CONDOMINIUM SERVICE — Método switchUnit()
// ============================================================
/*
  Future<void> switchUnit(int newUnitId) async {
    final prefs = await SharedPreferences.getInstance();
    
    // 1. Guardar nueva preferencia
    await prefs.setInt('current_unit_id', newUnitId);
    
    // 2. Limpiar datos cacheados que dependen de unit_id
    await prefs.remove('cached_profile');
    await prefs.remove('cached_unit_data');
    await prefs.remove('cached_qr_list');
    await prefs.remove('cached_balance');
    await prefs.remove('cached_statement');
    await prefs.remove('cached_access_logs');
    
    // 3. Forzar recarga completa
    // Desde el widget que llama este método:
    // Navigator.of(context).pushNamedAndRemoveUntil('/', (route) => false);
  }
*/


// ============================================================
// 3. DRAWER — Sección "Tus Unidades"
// ============================================================
// 
// En resident_main_navigation.dart, dentro del Drawer,
// agregar DEBAJO de la sección "TUS COMUNIDADES":
//
// Pseudocódigo del widget:

/*
class _UnitSelectorSection extends StatefulWidget {
  final Function(int) onUnitSelected;
  
  @override
  _UnitSelectorSectionState createState() => _UnitSelectorSectionState();
}

class _UnitSelectorSectionState extends State<_UnitSelectorSection> {
  List<Map<String, dynamic>> units = [];
  int? currentUnitId;
  bool loading = true;

  @override
  void initState() {
    super.initState();
    _loadUnits();
  }

  Future<void> _loadUnits() async {
    try {
      final apiService = ApiService();
      final response = await apiService.getMyUnits();
      
      if (response['status'] == 'success') {
        final data = response['data'];
        setState(() {
          units = List<Map<String, dynamic>>.from(data['units'] ?? []);
          currentUnitId = data['current_unit_id'];
          loading = false;
        });
      }
    } catch (e) {
      setState(() => loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    // Si solo tiene 1 unidad o ninguna, no mostrar sección
    if (loading || units.length <= 1) return SizedBox.shrink();
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: 20, vertical: 8),
          child: Text(
            'TUS UNIDADES',
            style: GoogleFonts.inter(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: Colors.white.withOpacity(0.5),
              letterSpacing: 1.2,
            ),
          ),
        ),
        ...units.map((unit) {
          final isActive = unit['unit_id'].toString() == currentUnitId?.toString();
          final unitNumber = unit['unit_number'] ?? 'N/A';
          final sectionName = unit['section_name'];
          final type = unit['type'] == 'owner' ? 'Propietario' : 'Inquilino';
          
          return ListTile(
            dense: true,
            leading: Container(
              width: 36, height: 36,
              decoration: BoxDecoration(
                color: isActive ? Color(0xFF6366f1) : Colors.white.withOpacity(0.1),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Center(
                child: Text(
                  unitNumber.substring(0, unitNumber.length.clamp(0, 3)),
                  style: GoogleFonts.inter(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                  ),
                ),
              ),
            ),
            title: Text(
              'Unidad $unitNumber',
              style: GoogleFonts.inter(
                fontSize: 14,
                fontWeight: isActive ? FontWeight.w600 : FontWeight.w400,
                color: Colors.white.withOpacity(isActive ? 1.0 : 0.7),
              ),
            ),
            subtitle: Text(
              [if (sectionName != null) sectionName, type].join(' · '),
              style: GoogleFonts.inter(
                fontSize: 11,
                color: Colors.white.withOpacity(0.5),
              ),
            ),
            trailing: isActive
                ? Icon(Icons.check_circle, color: Color(0xFF6366f1), size: 20)
                : null,
            onTap: isActive ? null : () => widget.onUnitSelected(int.parse(unit['unit_id'].toString())),
          );
        }).toList(),
        Divider(color: Colors.white.withOpacity(0.1)),
      ],
    );
  }
}
*/

// ============================================================
// 4. INTEGRACIÓN EN EL DRAWER
// ============================================================
// 
// En el build() del Drawer de ResidentMainNavigation,
// agregar el widget _UnitSelectorSection ANTES de la 
// sección "TUS COMUNIDADES":
//
/*
  _UnitSelectorSection(
    onUnitSelected: (newUnitId) async {
      Navigator.pop(context); // Cerrar drawer
      
      final condominiumService = CondominiumService();
      await condominiumService.switchUnit(newUnitId);
      
      // Forzar reconstrucción completa
      Navigator.of(context).pushNamedAndRemoveUntil('/', (route) => false);
    },
  ),
*/

// ============================================================
// 5. AL HACER switchTenant (cambio de condominio)
// ============================================================
// 
// Cuando el usuario cambia de CONDOMINIO, limpiar también 
// el current_unit_id para que el backend resuelva 
// automáticamente la primera unidad del nuevo condominio:
//
/*
  // En CondominiumService.switchTenant():
  await prefs.remove('current_unit_id'); // ← AGREGAR
*/

// ============================================================
// 6. AL RECIBIR DATOS DE switchTenant
// ============================================================
// 
// Cuando el backend retorna datos del switch de condominio,
// guardar el unit_id si viene en la respuesta:
//
/*
  if (data['unit_id'] != null) {
    await prefs.setInt('current_unit_id', data['unit_id']);
  }
*/
