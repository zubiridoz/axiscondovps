<style>
    /* Manage Resident Modal Pro Styles */
    #manageResidentModal .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    #manageResidentModal .modal-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0.5rem;
    }

    /* Profile Header Info */
    .mr-profile-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 0 1.5rem;
    }
    .mr-avatar {
        width: 50px;
        height: 50px;
        background-color: #f1f5f9;
        color: #475569;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 600;
    }
    .mr-info h5 {
        margin: 0;
        font-weight: 600;
        color: #1e293b;
        font-size: 1.1rem;
    }
    .mr-info p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Info Boxes */
    .mr-box {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin: 0 1.5rem 1rem 1.5rem;
        background: #fff;
    }
    .mr-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .mr-box-title {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .mr-box-title i {
        color: #64748b;
    }

    /* Unit List Items */
    .mr-unit-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .mr-unit-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .mr-unit-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        min-width: 60px;
    }

    /* Role Badge Select */
    .mr-role-select {
        border: 1px solid #e0e7ff;
        background-color: #eff6ff;
        color: #232D3F;
        border-radius: 20px;
        padding: 0.2rem 0.6rem 0.2rem 1.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234338ca'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 8px 8px;
        min-width: 110px;
        position: relative;
    }
    .mr-role-select:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.2);
    }
    .mr-role-icon {
        position: absolute;
        left: 0.6rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        color: #4338ca;
        pointer-events: none;
    }

    /* Remove Buttons */
    .mr-btn-remove-unit {
        color: #ef4444;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 0.3rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .mr-btn-remove-unit:hover {
        background: #ef4444;
        color: white;
    }

    .mr-btn-change-unit {
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 0.4rem;
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mr-btn-change-unit:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    /* Roles Section */
    .mr-role-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: #f8fafc;
        width: fit-content;
    }
    .mr-role-checkbox input {
        accent-color: #64748b;
        cursor: not-allowed;
    }
    .mr-role-checkbox label {
        font-size: 0.85rem;
        color: #475569;
        font-weight: 500;
        margin: 0;
    }

    /* Remove Community Button */
    .mr-btn-remove-community {
        width: calc(100% - 3rem);
        margin: 0 1.5rem 1.5rem 1.5rem;
        background: #fff;
        border: 1px solid #fca5a5;
        color: #ef4444;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .mr-btn-remove-community:hover {
        background: #fef2f2;
        border-color: #ef4444;
    }

    /* Remove Modal Styles (Confirmations) */
    .mr-confirm-icon {
        text-align: left;
        color: #f59e0b;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }
    .mr-confirm-text {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .mr-confirm-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mr-confirm-card:hover { border-color: #cbd5e1; }
    .mr-confirm-card.danger {
        background: #fef2f2;
        border-color: #fecaca;
    }
    .mr-confirm-card.danger h6 { color: #ef4444; }
    
    .mr-confirm-card h6 {
        margin: 0 0 0.25rem 0;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .mr-confirm-card h6 i { color: #64748b; }
    .mr-confirm-card.danger h6 i { color: #ef4444; }
    .mr-confirm-card p {
        margin: 0 0 0.75rem 0;
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.4;
    }
    .mr-confirm-card button {
        width: 100%;
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 0.4rem;
        padding: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        transition: all 0.2s;
    }
    .mr-confirm-card.danger button {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    .mr-confirm-card.danger button:hover {
        background: #dc2626;
    }

    #manageResidentModal .modal-body {
        padding: 0;
    }

    /* Unit Picker Searchable Dropdown CSS */
    .unit-picker {
        position: relative;
    }
    .unit-picker-panel {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 160px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        margin-top: 4px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        z-index: 1060; /* Above bootstrap modal 1055 */
    }
    .unit-picker-search {
        padding: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: white;
        border-radius: 0.6rem 0.6rem 0 0;
    }
    .unit-picker-search input {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 0.4rem;
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
    }
    .unit-picker-search input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.1);
    }
    .unit-picker-options {
        max-height: 160px;
        overflow-y: auto;
        border-radius: 0 0 0.6rem 0.6rem;
        background: white;
    }
    .unit-picker-opt {
        padding: 0.4rem 0.65rem;
        font-size: 0.82rem;
        cursor: pointer;
        color: #334155;
    }
    .unit-picker-opt:hover {
        background-color: #f8fafc;
        color: #6366f1;
    }
</style>

<!-- Main Manage Resident Modal -->
<div class="modal fade" id="manageResidentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">

                <div class="mr-profile-header">
                    <div class="mr-avatar" id="mr-avatar-initials">A</div>
                    <div class="mr-info">
                        <h5 id="mr-name-display">Cargando...</h5>
                        <p class="mb-1"><i class="bi bi-envelope"></i> <span id="mr-email-display">...</span></p>
                        <p><i class="bi bi-telephone"></i> <span id="mr-phone-header-display">Sin teléfono</span></p>
                    </div>
                </div>

                <div class="mr-box">
                    <div class="mr-box-header">
                        <div class="mr-box-title"><i class="bi bi-telephone"></i> Teléfonos</div>
                        <button type="button" class="mr-btn-change-unit" id="mr-btn-add-phone">
                            <i class="bi bi-plus-lg"></i> Agregar teléfono
                        </button>
                    </div>
                    <div class="text-muted small" style="margin-top: -0.5rem;" id="mr-phone-list">
                        Sin teléfono
                    </div>
                </div>

                <div class="mr-box">
                    <div class="mr-box-header">
                        <div class="mr-box-title"><i class="bi bi-house-door"></i> Unidades</div>
                        <!-- Searchable picker wrapper -->
                        <div class="unit-picker" id="mr-add-unit-picker" style="width: auto;">
                            <!-- Reusing the CSS from the import wizard for consistency -->
                            <button type="button" class="mr-btn-change-unit unit-picker-trigger">
                                <i class="bi bi-plus-lg"></i> Agregar a Unidad
                            </button>
                            <div class="unit-picker-panel" style="right: 0; left: auto; top: 110%;">
                                <div class="unit-picker-search">
                                    <input type="text" placeholder="Buscar unidad..." class="unit-search-field">
                                </div>
                                <div class="unit-picker-options" id="mr-available-units-list">
                                    <!-- Populated statically from $units in residents.php -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mr-units-container">
                        <!-- Unit items injected here by JS -->
                        <div class="text-center text-muted small py-3" id="mr-units-loading">Cargando unidades...</div>
                    </div>
                </div>

                <div class="mr-box">
                    <div class="mr-box-header mb-1">
                        <div class="mr-box-title"><i class="bi bi-info-circle"></i> Información de cuenta</div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Estado</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 border border-success border-opacity-10" id="mr-status-display">Activo</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Correo verificado</span>
                            <span class="text-success small fw-500"><i class="bi bi-check-circle"></i> Correo verificado</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Miembro desde</span>
                            <span class="text-dark small fw-500" id="mr-member-since-display">Cargando...</span>
                        </div>
                    </div>
                </div>

                <button class="btn mr-btn-remove-community" id="btn-trigger-remove-community">
                    <i class="bi bi-people"></i> Remover de comunidad
                </button>

            </div>
        </div>
    </div>
</div>

<!-- Confirm Remove Unit / Remove Community Modal -->
<div class="modal fade" id="confirmRemoveUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-body p-4">
                <div class="mr-confirm-icon">
                    <i class="bi bi-exclamation-triangle"></i> Última Asignación de Unidad
                </div>
                <div class="mr-confirm-text" id="mr-confirm-unit-text">
                    [Nombre] solo está asignado a la unidad [Unidad]. ¿Qué desea hacer?
                </div>

                <div class="mr-confirm-card" id="btn-do-remove-unit" onclick="doRemoveUnitOnly()">
                    <h6><i class="bi bi-house"></i> Remover solo de la unidad</h6>
                    <p>El residente será removido de esta unidad pero permanecerá en la comunidad. Aparecerá en la lista de residentes sin asignar.</p>
                    <button><i class="bi bi-x"></i> Remover de unidad</button>
                </div>

                <div class="mr-confirm-card danger" id="btn-do-remove-community-from-unit" onclick="doRemoveCommunity()">
                    <h6><i class="bi bi-people"></i> Remover de la comunidad</h6>
                    <p>El residente perderá todo acceso a esta comunidad. Esta acción no se puede deshacer.</p>
                    <button><i class="bi bi-trash3"></i> Remover de comunidad</button>
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-outline-secondary" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Direct Remove Community Modal -->
<div class="modal fade" id="confirmRemoveCommunityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3" style="font-size: 2.5rem;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="mb-3 text-dark">Remover de Comunidad</h5>
                <p class="text-muted" id="mr-confirm-community-text">¿Está seguro que desea remover a [Nombre] de esta comunidad?</p>
                <p class="text-danger small fw-500 mb-4">Esta acción no se puede deshacer. El residente perderá todo acceso a esta comunidad, incluyendo roles y asignaciones de unidad.</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-outline-secondary px-4" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger px-4" style="border-radius: 0.5rem;" onclick="doRemoveCommunity()"><i class="bi bi-people"></i> Remover de comunidad</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Añadir/Editar Teléfono -->
<div class="modal fade" id="editPhoneModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0 mt-2 px-4">
                <h5 class="modal-title" style="color: #1e293b; font-weight: 500; font-size: 1.1rem;">Agregar teléfono</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-2">
                <p class="text-muted small mb-4">Ingresa un número de teléfono para este residente.</p>
                
                <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;">Número de teléfono</label>
                <div class="phone-input-wrapper" style="max-width: 100%;">
                    <div class="phone-flag-box px-3 py-2">
                        🇲🇽 <i class="bi bi-chevron-expand ms-1 text-muted" style="font-size: 0.75rem;"></i>
                    </div>
                    <input type="text" id="mr-phone-input-field" class="form-control border-0 shadow-none py-2" placeholder="234 567 8900" style="font-size: 0.95rem;">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                    <button class="btn btn-outline-secondary px-4 py-2" style="border-radius: 0.5rem; font-size: 0.9rem;" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn px-4 py-2" style="background-color: #94a3b8; color: white; border-radius: 0.5rem; font-weight: 500; font-size: 0.9rem; border: none;" id="mr-btn-save-phone">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
