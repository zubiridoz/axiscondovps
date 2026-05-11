// ═══════════════════════════════════════════════════════════════
// CAMBIOS EN resident_main_navigation.dart PARA MULTI-UNIDAD
// 3 cambios: (A) Variables  (B) Método _loadUnits  (C) Widget en Drawer
// ═══════════════════════════════════════════════════════════════

// ─────────────────────────────────────────────────────────────
// CAMBIO A: Agregar variables de estado (junto a las de condos)
// ─────────────────────────────────────────────────────────────
// BUSCAR estas líneas:
//   List<Condominium> _userCondos = [];
//   int? _activeCondoId;
//   bool _loadingCondos = true;
//
// AGREGAR DEBAJO:

/*
  List<Map<String, dynamic>> _userUnits = [];
  int? _activeUnitId;
  bool _loadingUnits = true;
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO B: En initState(), agregar _loadUnits()
// ─────────────────────────────────────────────────────────────
// BUSCAR:
//   _loadUserData();
//   _loadCondos();
//
// AGREGAR DEBAJO:
//   _loadUnits();

// ─────────────────────────────────────────────────────────────
// CAMBIO C: Agregar el método _loadUnits() (junto a _loadCondos)
// ─────────────────────────────────────────────────────────────

/*
  Future<void> _loadUnits() async {
    try {
      if (mounted) setState(() => _loadingUnits = true);
      
      final prefs = await SharedPreferences.getInstance();
      _activeUnitId = prefs.getInt('current_unit_id');
      
      final res = await ApiService.getMyUnits();
      
      if (res['status'] == 'success' && res['data'] != null) {
        final data = res['data'];
        final List<dynamic> rawUnits = data['units'] ?? [];
        final currentId = data['current_unit_id'];
        
        if (mounted) {
          setState(() {
            _userUnits = rawUnits.map((e) => Map<String, dynamic>.from(e as Map)).toList();
            if (currentId != null) {
              _activeUnitId = int.tryParse(currentId.toString());
            }
            _loadingUnits = false;
          });
        }
      } else {
        if (mounted) setState(() => _loadingUnits = false);
      }
    } catch (e) {
      debugPrint("Error loading units in Drawer: $e");
      if (mounted) setState(() => _loadingUnits = false);
    }
  }
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO D: Widget _buildUnitSelector() — agregar como método
// ─────────────────────────────────────────────────────────────

/*
  Widget _buildUnitSelector() {
    // Si solo tiene 1 unidad o está cargando, no mostrar
    if (_loadingUnits || _userUnits.length <= 1) return const SizedBox.shrink();
    
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(height: 8),
          Text(
            'TUS UNIDADES',
            style: GoogleFonts.inter(
              fontSize: 11,
              fontWeight: FontWeight.w800,
              color: Colors.white.withValues(alpha: 0.6),
              letterSpacing: 1.2,
            ),
          ),
          const SizedBox(height: 12),
          ..._userUnits.map((unit) {
            final unitId = int.tryParse(unit['unit_id'].toString()) ?? 0;
            final isActive = unitId == _activeUnitId;
            final unitNumber = unit['unit_number']?.toString() ?? 'N/A';
            final sectionName = unit['section_name']?.toString();
            final type = unit['type'] == 'owner' ? 'Propietario' : 'Inquilino';
            final subtitle = [if (sectionName != null) sectionName, type].join(' · ');
            
            return GestureDetector(
              onTap: isActive ? null : () async {
                Navigator.pop(context); // Cerrar drawer
                await CondominiumService.switchUnit(unitId);
                if (mounted) {
                  Navigator.of(context).pushNamedAndRemoveUntil('/', (route) => false);
                }
              },
              child: Container(
                margin: const EdgeInsets.only(bottom: 10),
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: isActive 
                      ? const Color(0xFF6366f1).withValues(alpha: 0.15)
                      : Colors.white.withValues(alpha: 0.05),
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(
                    color: isActive 
                        ? const Color(0xFF6366f1).withValues(alpha: 0.5) 
                        : Colors.white.withValues(alpha: 0.1),
                    width: isActive ? 1.5 : 1,
                  ),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 36,
                      height: 36,
                      decoration: BoxDecoration(
                        color: isActive ? const Color(0xFF6366f1) : Colors.white.withValues(alpha: 0.1),
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
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Unidad $unitNumber',
                            style: GoogleFonts.inter(
                              fontSize: 13,
                              fontWeight: isActive ? FontWeight.w700 : FontWeight.w500,
                              color: Colors.white.withValues(alpha: isActive ? 1.0 : 0.7),
                            ),
                          ),
                          Text(
                            subtitle,
                            style: GoogleFonts.inter(
                              fontSize: 11,
                              color: isActive 
                                  ? const Color(0xFF6366f1).withValues(alpha: 0.9)
                                  : Colors.white.withValues(alpha: 0.5),
                            ),
                          ),
                        ],
                      ),
                    ),
                    if (isActive)
                      const Icon(Icons.check_circle, color: Color(0xFF6366f1), size: 18),
                  ],
                ),
              ),
            );
          }),
          Divider(color: Colors.white.withValues(alpha: 0.1)),
          const SizedBox(height: 4),
        ],
      ),
    );
  }
*/

// ─────────────────────────────────────────────────────────────
// CAMBIO E: En _buildDrawer(), insertar _buildUnitSelector()
//           ANTES de la sección "TUS COMUNIDADES"
// ─────────────────────────────────────────────────────────────
// BUSCAR en el ListView children del Drawer, después del Container
// del header con el avatar, ANTES del Padding de "TUS COMUNIDADES":

/*
                // ══════ NUEVO: Selector de Unidades ══════
                _buildUnitSelector(),
                // ═════════════════════════════════════════
                
                // ── Sección: Tus Comunidades ──  (existente)
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  ...
*/
