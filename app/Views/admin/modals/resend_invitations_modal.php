<style>
    .resend-icon-circle-premium {
        width: 48px;
        height: 48px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #2a3547;
    }

    .process-item-premium {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .process-icon-premium {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .process-text-premium {
        font-size: 0.875rem;
        color: #3F67AC;
        line-height: 1.5;
    }

    .safety-box-premium {
        background: #fdfdfd;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .safety-box-premium i {
        color: #10b981;
        font-size: 1.1rem;
    }

    .safety-box-premium span {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    .btn-resend-action-premium {
        background: #1e293b;
        color: white;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .btn-resend-action-premium:hover {
        background: #0f172a;
        color: white;
        transform: translateY(-1px);
    }
</style>

<!-- MODAL REENVIAR INVITACIONES -->
<div class="modal fade" id="resendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content axis-modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);">
            <div class="modal-header border-0 pb-0" style="padding: 1.5rem 1.5rem 0.5rem;">
                <div class="d-flex align-items-center gap-3">
                    <div class="resend-icon-circle-premium">
                        <i class="bi bi-send-fill" style="transform: rotate(-15deg);"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.01em;">Reenviar Invitaciones</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Refrescar el proceso para pendientes</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
            </div>

            <div class="modal-body p-4 pt-4">
                <div class="mb-4">
                    <h6 class="text-uppercase fw-700 text-muted mb-3" style="font-size: 0.7rem; letter-spacing: 0.05em;">Procedimiento</h6>
                    
                    <div class="process-item-premium">
                        <div class="process-icon-premium text-primary"><i class="bi bi-envelope-at"></i></div>
                        <div class="process-text-premium">Se enviará un recordatorio por correo a los <span class="fw-bold text-dark"><?= $counts['pending'] ?></span> residentes en espera.</div>
                    </div>

                    <div class="process-item-premium">
                        <div class="process-icon-premium"><i class="bi bi-key"></i></div>
                        <div class="process-text-premium">El mensaje contiene su enlace personalizado de registro y acceso a la plataforma.</div>
                    </div>

                    <div class="process-item-premium">
                        <div class="process-icon-premium"><i class="bi bi-shield-lock"></i></div>
                        <div class="process-text-premium">No se generan cargos adicionales ni se duplican registros en la base de datos.</div>
                    </div>
                </div>

                <div class="safety-box-premium mb-4">
                    <i class="bi bi-shield-check"></i>
                    <span>Este proceso es automático y seguro para su base de datos de residentes.</span>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-light border px-4 rounded-3 fw-600" style="font-size: 0.85rem; color: #64748b;" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-resend-action-premium d-flex align-items-center" onclick="confirmResendInvitations(this)">
                        <i class="bi bi-send-fill me-2" style="font-size: 0.8rem;"></i> Enviar Invitaciones
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

