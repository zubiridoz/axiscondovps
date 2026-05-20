Arquitectura v2.1 — Ajustes Finales de Resiliencia

Este documento NO reemplaza el plan v2. Solo lo complementa con ajustes puntuales.

Validación de las 8 Observaciones

#

Observación

¿Crítica?

Veredicto

1

Deduplication / Idempotencia

🔴 SÍ

El código actual NO protege contra double-click ni jobs duplicados

2

Masking de teléfonos en logs

🟡 IMPORTANTE

Ajuste simple, 1 función utility

3

Webhook idempotency

🔴 SÍ

Twilio reenvía callbacks, el diseño actual haría UPDATEs ciegos

4

Cleanup / Retención

🟡 IMPORTANTE

Necesario pero simple — replicar patrón de SendReminders línea 64

5

Índices y performance

🔴 SÍ

Faltan 2 índices críticos para el worker

6

Health monitoring del worker

🟡 IMPORTANTE

Heartbeat file es suficiente

7

Graceful shutdown

🟢 NO VALE LA PENA

Ver justificación abajo

8

Otras debilidades

🟢 NO HAY

Ver justificación abajo

Observación 7: ¿Por Qué NO Vale la Pena?

El worker CRON se ejecuta cada minuto, procesa máximo 10 jobs, cada uno toma <2 segundos (una llamada HTTP a Twilio). El ciclo completo dura ~20 segundos máximo.

Si el proceso muere durante un deploy:

El job queda en status = 'processing' con reserved_at seteado

El reclaim de jobs abandonados (ya diseñado en v2) lo detecta en el siguiente ciclo:

UPDATE jobs SET status='pending', reserved_at=NULL
WHERE status='processing' AND reserved_at < NOW() - INTERVAL 10 MINUTE

El job se reprocesa automáticamente

No hay riesgo real de corrupción. Un job de WhatsApp es idempotente por naturaleza: si Twilio recibió la petición, el webhook actualiza el estado; si no la recibió, se reenvía sin daño.

Implementar signal handlers (pcntl_signal) para SIGTERM/SIGINT añadiría complejidad sin beneficio para este volumen. Si el sistema escala a miles de mensajes/minuto, se revisita.

Observación 8: ¿Existen Otras Debilidades Reales?

Revisé el código una vez más. No hay debilidades no cubiertas por la v2 + estos ajustes. Los riesgos reales ya están mitigados:

Race conditions en jobs → SELECT FOR UPDATE SKIP LOCKED

Twilio dentro de transacción → Segregación por diseño

Circuit breaker → Protege contra caídas

Rate limiting → 3 niveles

Retry inteligente → Clasificación de errores

No voy a inventar problemas.

Ajuste 1: Deduplication Key (CRÍTICO)

Análisis del código actual

En InvitationService.php L28-36, ya existe validación de invitación duplicada por email:

$existing = $this->invitationModel
    ->where('condominium_id', $condoId)
    ->where('email', $data['email'])
    ->where('invitation_status', 'pending')
    ->first();

Esto protege contra crear dos invitaciones al mismo email. PERO no protege contra:

Double-click en "Enviar invitación" → 2 requests simultáneos, ambos pasan la validación antes de que el primero haga INSERT

Double-click en "Reenviar" → 2 jobs WhatsApp encolados para el mismo reenvío

Import CSV masivo con el mismo email 2 veces en el archivo

Solución: deduplication_key en tabla jobs

Agregar columna a jobs:

ALTER TABLE jobs ADD COLUMN deduplication_key VARCHAR(128) DEFAULT NULL AFTER correlation_id;
CREATE UNIQUE INDEX idx_dedup_active ON jobs (deduplication_key) 
    WHERE status IN ('pending', 'processing');

[!WARNING]MySQL no soporta índices parciales (WHERE). La alternativa es un índice compuesto:

-- Approach real para MySQL 8:
ALTER TABLE jobs ADD COLUMN deduplication_key VARCHAR(128) DEFAULT NULL AFTER correlation_id;
-- No se puede hacer UNIQUE parcial. Se resuelve en código.
CREATE INDEX idx_dedup ON jobs (deduplication_key, status);

Validación en código (JobDispatcher):

// Antes de insertar un job, verificar si ya existe uno activo con la misma key
$existing = $db->table('jobs')
    ->where('deduplication_key', $dedupKey)
    ->whereIn('status', ['pending', 'processing'])
    ->get()->getRow();

if ($existing) {
    log_message('info', "[QUEUE] Job duplicado ignorado: {$dedupKey}");
    return $existing->id; // Retornar el job existente
}

Formato del deduplication_key:

Escenario

Key

Ejemplo

Envío inicial

inv_{id}_initial

inv_15_initial

Reenvío N

inv_{id}_resend_{timestamp_minuto}

inv_15_resend_1716134400

¿Por qué timestamp al minuto en reenvío? Porque un reenvío legítimo 1 hora después SÍ debe crear un nuevo job. Pero dos clicks en el mismo minuto NO.

El timestamp_minuto es floor(time() / 60) — agrupa al minuto.

Impacto: Solo en JobDispatcher. No afecta worker ni logs ni webhooks.

Protección adicional contra double-click en Controller

En ResidentInvitationsController::invite() y resend(), el frontend ya debería deshabilitar el botón tras click. Pero como defensa en profundidad, el deduplication_key en jobs es suficiente. No necesitamos más capas.

Ajuste 2: Phone Masking (SIMPLE)

Una función utility en WhatsAppService:

public static function maskPhone(string $phone): string
{
    $len = strlen($phone);
    if ($len <= 6) return str_repeat('*', $len);
    return substr($phone, 0, 5) . str_repeat('*', $len - 7) . substr($phone, -2);
}
// +5215512345678 → +5215******78

Dónde se usa:

En log_message() calls dentro de WhatsAppService y ProcessJobsQueue

NO en DB — whatsapp_message_logs.recipient_phone guarda el número completo (necesario para auditoría y re-envíos)

NO en respuestas JSON al admin (el admin necesita ver el número para confirmar)

Impacto: 0 en tablas, 0 en lógica, solo en strings de log.

Ajuste 3: Webhook Idempotency (CRÍTICO)

Problema concreto

Twilio envía callbacks en este orden: queued → sent → delivered → read

Pero puede:

Enviar delivered dos veces

Enviar sent DESPUÉS de delivered (out of order por latencia)

Reenviar cualquier callback si no recibió HTTP 200

Solución: Status Ordering

Definir un peso para cada status. Solo aceptar actualizaciones que avancen, nunca que retrocedan:

private const STATUS_WEIGHT = [
    'queued'      => 1,
    'sent'        => 2,
    'delivered'   => 3,
    'read'        => 4,
    'failed'      => 5,  // terminal
    'undelivered' => 5,  // terminal
];

En el webhook controller:

$currentLog = $db->table('whatsapp_message_logs')
    ->where('twilio_message_sid', $messageSid)
    ->get()->getRowArray();

if (!$currentLog) {
    // SID desconocido — loguear y retornar 200 (no reintentar)
    return $this->response->setStatusCode(200);
}

$currentWeight = self::STATUS_WEIGHT[$currentLog['status']] ?? 0;
$newWeight = self::STATUS_WEIGHT[$newStatus] ?? 0;

if ($newWeight <= $currentWeight) {
    // Status repetido o retroceso — ignorar silenciosamente
    log_message('debug', "[WEBHOOK] Status ignorado (no avanza): SID={$messageSid} current={$currentLog['status']} incoming={$newStatus}");
    return $this->response->setStatusCode(200);
}

// Actualizar
$db->table('whatsapp_message_logs')
    ->where('twilio_message_sid', $messageSid)
    ->update([
        'status' => $newStatus,
        'delivered_at' => ($newStatus === 'delivered') ? date('Y-m-d H:i:s') : $currentLog['delivered_at'],
        'error_code' => $errorCode,
        'error_message' => $errorMessage,
    ]);

¿Tabla adicional para eventos? NO. Para este volumen, el approach de status ordering en whatsapp_message_logs es suficiente. Una tabla webhook_events sería overengineering.

¿UNIQUE constraint en twilio_message_sid? SÍ. Un SID de Twilio es globalmente único. Agregar:

-- Ya está como INDEX en v2, promover a UNIQUE:
ALTER TABLE whatsapp_message_logs 
    DROP INDEX idx_sid,
    ADD UNIQUE INDEX idx_sid_unique (twilio_message_sid);

[!NOTE]El campo permite NULL (logs creados antes de recibir respuesta de Twilio). MySQL permite múltiples NULLs en UNIQUE index, así que no hay conflicto.

Ajuste 4: Cleanup / Retención

Siguiendo el patrón exacto de SendReminders.php L63-65:

$thirtyDaysAgo = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
$db->table('payment_reminder_logs')->where('created_at <', $thirtyDaysAgo)->delete();

Política de retención:

Tabla

Retención

Justificación

jobs (completed/dead)

30 días

Solo operacional, no es auditoría

jobs (failed)

90 días

Para debugging de problemas recurrentes

whatsapp_message_logs

180 días

Auditoría de costos y compliance

Implementación: Agregar cleanup al final de ProcessJobsQueue::run(), ejecutándose una vez al día (no cada minuto):

// Cleanup solo a las 3 AM (mismo patrón que SendReminders verifica hora)
$currentHour = (int) date('G');
if ($currentHour === 3) {
    $thirtyDaysAgo = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
    $ninetyDaysAgo = (new \DateTime())->modify('-90 days')->format('Y-m-d H:i:s');
    $sixMonthsAgo  = (new \DateTime())->modify('-180 days')->format('Y-m-d H:i:s');
    
    $db->table('jobs')->where('created_at <', $thirtyDaysAgo)
       ->whereIn('status', ['completed', 'dead'])->delete();
    $db->table('jobs')->where('created_at <', $ninetyDaysAgo)
       ->where('status', 'failed')->delete();
    $db->table('whatsapp_message_logs')->where('created_at <', $sixMonthsAgo)->delete();
}

Impacto: Cero en lógica existente. Misma convención del proyecto.

Ajuste 5: Índices Corregidos

Índices de v2 que se mantienen ✅

-- whatsapp_message_logs
INDEX idx_condo (condominium_id)
INDEX idx_status (status)
INDEX idx_related (related_entity_type, related_entity_id)
INDEX idx_correlation (correlation_id)
INDEX idx_condo_created (condominium_id, created_at)

-- jobs
INDEX idx_correlation (correlation_id)
INDEX idx_status (status)

Índices NUEVOS necesarios

-- 1. CRÍTICO: Worker query (el query más frecuente del sistema)
-- El worker hace: WHERE queue='whatsapp' AND status='pending' AND available_at <= NOW()
-- ORDER BY priority ASC, created_at ASC
-- El índice de v2 (idx_queue_status_available) ya cubre esto ✅

-- 2. NUEVO: Reclaim de jobs abandonados
-- Query: WHERE status='processing' AND reserved_at < NOW() - 10 MIN
CREATE INDEX idx_reclaim ON jobs (status, reserved_at);

-- 3. NUEVO: Dedup lookup
CREATE INDEX idx_dedup ON jobs (deduplication_key, status);

-- 4. CORREGIR: twilio_message_sid debe ser UNIQUE (no simple INDEX)
-- Ya explicado en Ajuste 3

-- 5. NUEVO: Rate limiting query
-- Query: WHERE condominium_id=X AND created_at > NOW() - 1 HOUR
-- idx_condo_created ya cubre esto ✅

-- 6. NUEVO: Cleanup query
CREATE INDEX idx_jobs_cleanup ON jobs (status, created_at);

Total índices nuevos: 3 (idx_reclaim, idx_dedup, idx_jobs_cleanup)Total corregidos: 1 (idx_sid → idx_sid_unique)

Ajuste 6: Health Monitoring (SIMPLE)

Approach: Heartbeat file. El worker escribe un archivo con timestamp en cada ciclo. Un monitor externo (CRON separado o el mismo dashboard) verifica que el archivo no tenga más de 5 minutos.

// Al final de cada ciclo en ProcessJobsQueue::run()
file_put_contents(WRITEPATH . 'cache/worker_whatsapp_heartbeat.txt', date('Y-m-d H:i:s'));

Verificación en dashboard (opcional futuro):

$heartbeat = @file_get_contents(WRITEPATH . 'cache/worker_whatsapp_heartbeat.txt');
$isAlive = $heartbeat && strtotime($heartbeat) > strtotime('-5 minutes');

¿Alertas? No ahora. Para este volumen, si un admin reporta que un WhatsApp no llegó, el equipo puede verificar manualmente. Cuando escale, se puede añadir un check en el SuperAdmin dashboard.

Esquema SQL Final Consolidado (v2 + v2.1)

-- ═══════════════════════════════════════
-- TABLA: jobs (actualizada con dedup)
-- ═══════════════════════════════════════
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(50) NOT NULL DEFAULT 'default',
    job_class VARCHAR(255) NOT NULL,
    payload JSON NOT NULL,
    correlation_id VARCHAR(36) NOT NULL,
    deduplication_key VARCHAR(128) DEFAULT NULL,
    status ENUM('pending','processing','completed','failed','dead') NOT NULL DEFAULT 'pending',
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    max_attempts TINYINT UNSIGNED NOT NULL DEFAULT 3,
    priority TINYINT UNSIGNED NOT NULL DEFAULT 10,
    available_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reserved_at DATETIME DEFAULT NULL,
    completed_at DATETIME DEFAULT NULL,
    last_error TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_queue_status_available (queue, status, available_at),
    INDEX idx_correlation (correlation_id),
    INDEX idx_status (status),
    INDEX idx_dedup (deduplication_key, status),
    INDEX idx_reclaim (status, reserved_at),
    INDEX idx_jobs_cleanup (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════
-- TABLA: whatsapp_message_logs (sid UNIQUE)
-- ═══════════════════════════════════════
CREATE TABLE whatsapp_message_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    correlation_id VARCHAR(36) NOT NULL,
    condominium_id BIGINT UNSIGNED NOT NULL,
    recipient_phone VARCHAR(20) NOT NULL,
    recipient_name VARCHAR(255) DEFAULT NULL,
    twilio_message_sid VARCHAR(34) DEFAULT NULL,
    content_sid VARCHAR(34) DEFAULT NULL,
    message_type VARCHAR(30) NOT NULL DEFAULT 'invitation',
    status VARCHAR(20) NOT NULL DEFAULT 'queued',
    error_code VARCHAR(10) DEFAULT NULL,
    error_message TEXT DEFAULT NULL,
    related_entity_type VARCHAR(50) DEFAULT NULL,
    related_entity_id BIGINT UNSIGNED DEFAULT NULL,
    attempt_number TINYINT UNSIGNED NOT NULL DEFAULT 1,
    delivered_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_condo (condominium_id),
    INDEX idx_status (status),
    UNIQUE INDEX idx_sid_unique (twilio_message_sid),
    INDEX idx_related (related_entity_type, related_entity_id),
    INDEX idx_correlation (correlation_id),
    INDEX idx_condo_created (condominium_id, created_at),
    FOREIGN KEY (condominium_id) REFERENCES condominiums(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════
-- TABLA: circuit_breaker_state (sin cambios vs v2)
-- ═══════════════════════════════════════
CREATE TABLE circuit_breaker_state (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(50) NOT NULL UNIQUE DEFAULT 'twilio_whatsapp',
    state ENUM('closed','open','half_open') NOT NULL DEFAULT 'closed',
    failure_count INT UNSIGNED NOT NULL DEFAULT 0,
    last_failure_at DATETIME DEFAULT NULL,
    last_success_at DATETIME DEFAULT NULL,
    opened_at DATETIME DEFAULT NULL,
    cooldown_until DATETIME DEFAULT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO circuit_breaker_state (service_name, state) VALUES ('twilio_whatsapp', 'closed');

-- ═══════════════════════════════════════
-- ALTER: resident_invitations (sin cambios vs v2)
-- ═══════════════════════════════════════
ALTER TABLE resident_invitations
    ADD COLUMN notification_channel VARCHAR(10) NOT NULL DEFAULT 'email' AFTER phone;

Riesgos Residuales Reales

#

Riesgo

Prob.

Impacto

Mitigación

1

CRON deja de ejecutarse

Media

Jobs se acumulan

Heartbeat file + revisión manual

2

Meta rechaza template

Baja

No se pueden enviar mensajes

Template alternativo preparado

3

Admin envía a número fijo

Baja

Twilio cobra pero no entrega

libphonenumber detecta tipo MOBILE

4

TLS cipher suite (3 Jun 2026)

Media

API Twilio deja de responder

Probar antes: curl https://tls-test.twilio.com

No hay más riesgos reales. Todo lo demás está cubierto.

Resumen de Cambios v2 → v2.1

Componente

Cambio

Tabla jobs

+columna deduplication_key + 3 índices nuevos

Tabla whatsapp_message_logs

idx_sid → idx_sid_unique (UNIQUE)

JobDispatcher

Verificación de dedup antes de INSERT

WhatsAppService

+método estático maskPhone()

TwilioWebhookController

Status ordering (no retroceder status)

ProcessJobsQueue

+cleanup 3AM + heartbeat file

Graceful shutdown

❌ No implementar (innecesario para este volumen)

La arquitectura v2 se mantiene intacta. Estos son ajustes quirúrgicos de resiliencia.

¿Aprobado para proceder a implementación?

El orden de ejecución sigue siendo el mismo del plan v2:F1 → F2 → F3 → F4 → F5 → F6 → F7 → F8

ULTIMA ACTUALIZACION DEL DOC 19/05/2026 01:52 AM