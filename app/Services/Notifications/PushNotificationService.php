<?php

namespace App\Services\Notifications;

/**
 * PushNotificationService — Firebase Cloud Messaging HTTP v1
 * Uses Service Account (OAuth2) — NOT legacy Server Key.
 */
class PushNotificationService
{
    protected string $projectId;
    protected string $serviceAccountPath;
    protected ?string $accessToken = null;

    public function __construct()
    {
        $this->projectId = getenv('FCM_PROJECT_ID') ?: 'axiscondo-19fca';

        $saPath = getenv('FCM_SERVICE_ACCOUNT_PATH') ?: 'app/Config/firebase/axiscondo-19fca-firebase-adminsdk-fbsvc-5970027aee.json';

        // Resolver ruta absoluta
        if (!str_starts_with($saPath, '/') && !preg_match('/^[A-Z]:/i', $saPath)) {
            $saPath = ROOTPATH . $saPath;
        }

        $this->serviceAccountPath = $saPath;

        log_message('info', '[FCM] Service account path: ' . $this->serviceAccountPath);
        log_message('info', '[FCM] File exists: ' . (file_exists($this->serviceAccountPath) ? 'YES' : 'NO'));
        log_message('info', '[FCM] Project ID: ' . $this->projectId);
    }

    /**
     * Envía notificación a TODOS los dispositivos del condominio
     */
    public function sendToCondominium(int $condominiumId, string $title, string $body, array $data = []): bool
    {
        log_message('info', "[FCM] sendToCondominium(condo={$condominiumId})");

        $db = \Config\Database::connect();
        $subscriptions = $db->table('device_push_subscriptions')
            ->where('condominium_id', $condominiumId)
            ->where('fcm_token IS NOT NULL')
            ->where('fcm_token !=', '')
            ->get()->getResultArray();

        log_message('info', '[FCM] Found ' . count($subscriptions) . ' device subscriptions');

        if (empty($subscriptions)) {
            log_message('warning', '[FCM] No tokens found for condominium ' . $condominiumId);
            return false;
        }

        $tokens = array_column($subscriptions, 'fcm_token');
        $tokens = array_values(array_filter(array_unique($tokens)));

        log_message('info', '[FCM] Unique tokens to send: ' . count($tokens));

        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Envía notificación a un usuario específico
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): bool
    {
        $db = \Config\Database::connect();
        $subscriptions = $db->table('device_push_subscriptions')
            ->where('user_id', $userId)
            ->where('fcm_token IS NOT NULL')
            ->where('fcm_token !=', '')
            ->get()->getResultArray();

        if (empty($subscriptions)) {
            log_message('warning', "[FCM] No tokens for user {$userId}");
            return false;
        }

        $tokens = array_column($subscriptions, 'fcm_token');
        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Envía a un array de FCM tokens
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): bool
    {
        $tokens = array_values(array_filter(array_unique($tokens)));
        if (empty($tokens)) {
            log_message('warning', '[FCM] No valid tokens to send');
            return false;
        }

        // Obtener access token OAuth2
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            log_message('error', '[FCM] ❌ Failed to get OAuth2 access token');
            return false;
        }

        log_message('info', '[FCM] ✅ OAuth2 token obtained, sending to ' . count($tokens) . ' devices');

        $allSuccess = true;
        $endpoint = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        foreach ($tokens as $i => $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => array_map('strval', $data),
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound'        => 'default',
                            'channel_id'   => 'axiscondo_notifications',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ],
                    ],
                ],
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $endpoint,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_TIMEOUT        => 15,
            ]);

            $result   = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            log_message('info', "[FCM] Token #{$i} → HTTP {$httpCode}");
            log_message('info', "[FCM] Token #{$i} → Response: " . substr($result, 0, 500));

            if ($curlErr) {
                log_message('error', "[FCM] Token #{$i} → cURL error: {$curlErr}");
                $allSuccess = false;
                continue;
            }

            if ($httpCode !== 200) {
                $allSuccess = false;
                $decoded = json_decode($result, true);
                $errorCode = $decoded['error']['details'][0]['errorCode'] ?? ($decoded['error']['status'] ?? 'UNKNOWN');

                log_message('error', "[FCM] Token #{$i} FAILED ({$httpCode}): {$errorCode}");

                // Limpiar tokens inválidos
                if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT', 'NOT_FOUND'])) {
                    $db = \Config\Database::connect();
                    $db->table('device_push_subscriptions')->where('fcm_token', $token)->delete();
                    log_message('info', "[FCM] Removed stale token: " . substr($token, 0, 30));
                }
            } else {
                log_message('info', "[FCM] Token #{$i} ✅ SENT OK");
            }
        }

        return $allSuccess;
    }

    /**
     * Genera un JWT y lo intercambia por un Access Token OAuth2
     */
    private function getAccessToken(): ?string
    {
        if ($this->accessToken) return $this->accessToken;

        if (!file_exists($this->serviceAccountPath)) {
            log_message('error', '[FCM] ❌ Service account NOT FOUND: ' . $this->serviceAccountPath);
            return null;
        }

        $sa = json_decode(file_get_contents($this->serviceAccountPath), true);

        if (!$sa || empty($sa['private_key']) || empty($sa['client_email'])) {
            log_message('error', '[FCM] ❌ Invalid service account JSON');
            return null;
        }

        log_message('info', '[FCM] Building JWT for: ' . $sa['client_email']);

        // JWT Header
        $header = $this->base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));

        // JWT Claim Set
        $now = time();
        $claimSet = $this->base64url(json_encode([
            'iss'   => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));

        // Firmar con RSA
        $signatureInput = "{$header}.{$claimSet}";
        $signature = '';
        $privateKey = openssl_pkey_get_private($sa['private_key']);

        if (!$privateKey) {
            log_message('error', '[FCM] ❌ Cannot parse private key');
            return null;
        }

        openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $jwt = $signatureInput . '.' . $this->base64url($signature);

        log_message('info', '[FCM] JWT built, exchanging for access token...');

        // Intercambiar JWT por access token
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://oauth2.googleapis.com/token',
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_TIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        log_message('info', "[FCM] OAuth2 response HTTP {$httpCode}");

        if ($curlErr) {
            log_message('error', "[FCM] ❌ OAuth2 cURL error: {$curlErr}");
            return null;
        }

        if ($httpCode !== 200) {
            log_message('error', "[FCM] ❌ OAuth2 failed ({$httpCode}): " . substr($response, 0, 500));
            return null;
        }

        $tokenData = json_decode($response, true);
        $this->accessToken = $tokenData['access_token'] ?? null;

        if ($this->accessToken) {
            log_message('info', '[FCM] ✅ OAuth2 access token obtained');
        } else {
            log_message('error', '[FCM] ❌ No access_token in response');
        }

        return $this->accessToken;
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
