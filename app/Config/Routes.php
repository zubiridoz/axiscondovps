<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');



// ==========================================
// LOGIN UNIVERSAL (WEB)
// ==========================================
$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::attemptLogin');
$routes->get('app-required', 'Auth\AuthController::appRequired');
$routes->post('register', 'Auth\RegisterController::register');
$routes->get('logout', 'Auth\LogoutController::logout');
$routes->get('auth/select-tenant', 'Auth\AuthController::selectTenant');
$routes->get('auth/select-tenant/(:num)', 'Auth\AuthController::selectTenant/$1');

// ==========================================
// PANELES WEB (SaaS OWNER & TENANT ADMINS)
// ==========================================
$routes->group('superadmin', ['namespace' => 'App\Controllers\SuperAdmin', 'filter' => ['auth', 'superadmin']], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Gestión de condominios (AJAX endpoints)
    $routes->get('condominiums/(:num)/detail', 'CondominiumController::detail/$1');
    $routes->post('condominiums/(:num)/suspend', 'CondominiumController::suspend/$1');
    $routes->post('condominiums/(:num)/activate', 'CondominiumController::activate/$1');
    $routes->post('condominiums/(:num)/delete', 'CondominiumController::softDelete/$1');

    // Configuración SuperAdmin (perfil + gestión de super admins)
    $routes->get('settings', 'SettingsController::index');
    $routes->post('settings/update-profile', 'SettingsController::updateProfile');
    $routes->post('settings/update-password', 'SettingsController::updatePassword');
    $routes->post('settings/upload-avatar', 'SettingsController::uploadAvatar');
    $routes->get('settings/avatar/(.*)', 'SettingsController::serveAvatar/$1');
    $routes->get('settings/list-admins', 'SettingsController::listAdmins');
    $routes->post('settings/add-admin', 'SettingsController::addAdmin');
    $routes->post('settings/remove-admin', 'SettingsController::removeAdmin');

    // Gestión de Planes SaaS
    $routes->get('plans', 'PlanController::index');
    $routes->get('plans/list', 'PlanController::list');
    $routes->post('plans/store', 'PlanController::store');
    $routes->get('plans/(:num)', 'PlanController::get/$1');
    $routes->post('plans/(:num)/update', 'PlanController::update/$1');
    $routes->post('plans/(:num)/delete', 'PlanController::delete/$1');
    $routes->post('plans/assign', 'PlanController::assign');
    $routes->get('plans/condominiums', 'PlanController::condominiums');

    // Pagos Manuales SaaS
    $routes->post('payments/record', 'ManualPaymentController::record');
    $routes->get('payments/(:num)/history', 'ManualPaymentController::history/$1');

    // Retornos de Stripe Checkout
    $routes->get('billing/success', 'BillingController::success');
    $routes->get('billing/cancel', 'BillingController::cancel');
});

// ==========================================
// PUBLIC MEDIA ASSETS (sin auth)
// ==========================================
$routes->get('media/image/(.*)', 'MediaController::image/$1');

// Temporario para sync
$routes->get('sync-notifications', 'App\Controllers\Admin\NotificationController::syncTemp');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'tenant']], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('testdb', '\App\Controllers\TestDB::index');
    
    
    // Notifications Endpoints
    $routes->get('notifications', 'NotificationController::getLatest');
    $routes->post('notifications/mark-all-read', 'NotificationController::markAllRead');
    
    // Módulo Residentes Invitaciones HTTP endpoints
    $routes->post('residentes/invite', 'ResidentInvitationsController::invite');
    $routes->post('residentes/import', 'ResidentInvitationsController::importPreview');
    $routes->post('residentes/import/confirm', 'ResidentInvitationsController::importProcess');
    $routes->post('residentes/invitaciones/actualizar', 'ResidentInvitationsController::update');
    $routes->post('residentes/invitaciones/eliminar', 'ResidentInvitationsController::delete');
    $routes->post('residentes/invitaciones/reenviar', 'ResidentInvitationsController::resend');
    
    $routes->get('residentes/invitaciones', 'ResidentController::invitations');
    $routes->get('residentes/por-asignar', 'ResidentController::unassigned');
    $routes->get('residentes', 'ResidentController::index');
    $routes->post('residentes/asignar-unidad', 'ResidentController::assignUnitJson');
    
    // Manage Resident Modal Endpoints
    $routes->get('residentes/profile/(:num)', 'ResidentController::getProfile/$1');
    $routes->post('residentes/cambiar-unidad', 'ResidentController::changeUnitJson');
    $routes->post('residentes/cambiar-rol', 'ResidentController::changeRoleJson');
    $routes->post('residentes/remover-unidad', 'ResidentController::removeUnitJson');
    $routes->post('residentes/remover-comunidad', 'ResidentController::removeCommunityJson');
    $routes->post('residentes/actualizar-telefono', 'ResidentController::updatePhoneJson');

    $routes->get('tickets', 'TicketController::index');
    $routes->get('tickets/panel', 'TicketController::panel');
    $routes->get('tickets/metricas', 'TicketController::metrics');
    $routes->post('tickets/crear', 'TicketController::create');
    $routes->get('tickets/assignees', 'TicketController::getAssignees');
    $routes->post('tickets/update-details/(:num)', 'TicketController::updateDetails/$1');
    $routes->post('tickets/delete/(:num)', 'TicketController::delete/$1');
    $routes->get('tickets/(:num)/messages', 'TicketController::getMessages/$1');
    $routes->post('tickets/(:num)/messages', 'TicketController::sendMessage/$1');
    $routes->get('tickets/media/(.*)', 'TicketController::serveTicketMedia/$1');
    $routes->get('tickets/(:segment)', 'TicketController::detail/$1');
    
    // Módulo Amenidades
    $routes->get('amenidades', 'AmenityController::indexView');
    $routes->get('amenidades/nueva', 'AmenityController::editView');
    $routes->get('amenidades/detalle/(:segment)', 'AmenityController::showView/$1');
    $routes->get('amenidades/editar/(:segment)', 'AmenityController::editView/$1');
    $routes->post('amenidades/crear-wizard', 'AmenityController::createWizard');
    $routes->post('amenidades/actualizar-wizard/(:segment)', 'AmenityController::updateWizard/$1');
    $routes->post('amenidades/crear', 'AmenityController::create');
    $routes->post('amenidades/editar/(:num)', 'AmenityController::update/$1');
    $routes->post('amenidades/eliminar/(:num)', 'AmenityController::delete/$1');
    $routes->get('amenidades/imagen/(.*)', '\App\Controllers\MediaController::image/amenities/$1');
    $routes->get('amenidades/documento/(.*)', 'AmenityController::serveDocument/$1');
    $routes->post('amenidades/documento/eliminar/(:num)', 'AmenityController::deleteDocument/$1');
    $routes->get('amenidades/reservas', 'BookingController::bookingsView');
    $routes->post('amenidades/reservas/crear', 'BookingController::create');
    $routes->get('amenidades/reservas/usuarios', 'BookingController::getUsersForSelector');
    $routes->get('amenidades/reservas/disponibilidad/(:num)', 'BookingController::getAmenityAvailability/$1');
    $routes->post('amenidades/reservas/eliminar/(:num)', 'BookingController::delete/$1');
    $routes->post('amenidades/reservas/aprobar/(:num)', 'BookingController::approve/$1');
    $routes->post('amenidades/reservas/rechazar/(:num)', 'BookingController::reject/$1');
    $routes->post('amenidades/reservas/cancelar/(:num)', 'BookingController::cancel/$1');
    $routes->get('amenidades/estadisticas', 'BookingController::statistics');
    $routes->get('anuncios', 'AnnouncementController::indexView');
    $routes->post('anuncios/crear', 'AnnouncementController::create');
    $routes->get('anuncios/detalle/(:num)', 'AnnouncementController::getAnnouncement/$1');
    $routes->post('anuncios/like/(:num)', 'AnnouncementController::toggleLike/$1');
    $routes->post('anuncios/comentar/(:num)', 'AnnouncementController::addComment/$1');
    $routes->post('anuncios/comentario/eliminar/(:num)', 'AnnouncementController::deleteComment/$1');
    $routes->post('anuncios/actualizar/(:num)', 'AnnouncementController::update/$1');
    $routes->post('anuncios/eliminar/(:num)', 'AnnouncementController::delete/$1');
    $routes->get('anuncios/archivo/(.*)', '\App\Controllers\MediaController::image/announcements/$1');
    $routes->get('calendario', 'CalendarController::calendarView');
    $routes->post('calendario/crear', 'CalendarController::create');
    $routes->post('calendario/actualizar/(:num)', 'CalendarController::update/$1');
    $routes->post('calendario/eliminar/(:num)', 'CalendarController::delete/$1');
    $routes->get('calendario/recipientes', 'CalendarController::getRecipients');
    $routes->get('calendario/evento/(:num)', 'CalendarController::getEvent/$1');
    $routes->get('documentos', 'FileController::indexView');
    $routes->post('documentos/folder', 'FileController::createFolder');
    $routes->post('documentos/upload', 'FileController::uploadFiles');
    $routes->post('documentos/update-access/(:num)', 'FileController::updateAccess/$1');
    $routes->get('documentos/download/(:num)', 'FileController::downloadFile/$1');
    $routes->post('documentos/toggle-star/(:num)', 'FileController::toggleStar/$1');
    $routes->post('documentos/move/(:num)', 'FileController::moveDocument/$1');
    $routes->post('documentos/rename/(:num)', 'FileController::renameDocument/$1');
    $routes->post('documentos/delete/(:num)', 'FileController::deleteDocument/$1');
    $routes->get('documentos/api/folders', 'FileController::apiGetFolders');
    $routes->post('documentos/track-view/(:num)', 'FileController::trackView/$1');
    $routes->get('encuestas', 'PollController::indexView');
    $routes->post('encuestas/crear', 'PollController::create');
    $routes->get('encuestas/detalles/(:any)', 'PollController::details/$1');
    $routes->post('encuestas/cerrar/(:any)', 'PollController::close/$1');
    $routes->post('encuestas/eliminar/(:any)', 'PollController::delete/$1');
    
    $routes->group('finanzas', static function($routes) {
        $routes->get('panel', 'FinanceController::dashboard');
        $routes->get('morosidad', 'FinanceController::morosidad');
        $routes->get('morosidad/export', 'FinanceController::exportMorosidad');
        $routes->get('nuevo-registro', 'FinanceController::nuevoRegistro');
        $routes->post('nuevo-registro/api/pending-charges', 'FinanceController::getPendingCharges');
        $routes->get('reporte-mensual', 'FinanceController::descargarReporteMensual');
        $routes->get('movimientos', 'FinanceController::movimientos');
        $routes->get('pagos-por-unidad', 'FinanceController::pagosPorUnidad');
        $routes->get('pagos-por-unidad/(:segment)', 'FinanceController::unitDetail/$1');
        $routes->post('pagos-por-unidad/set-initial-balance', 'FinanceController::setInitialBalance');
        $routes->get('pagos-por-unidad/api/list', 'FinanceController::apiUnitList');
        $routes->get('pagos-por-unidad/api/(:segment)', 'FinanceController::apiUnitDetail/$1');
        $routes->get('morosidad/api-unit-summary/(:segment)', 'FinanceController::apiUnitSummary/$1');
        $routes->post('activate-billing', 'FinanceController::activateBilling');
        $routes->get('reset-db', 'FinanceController::resetDb');
        $routes->post('guardar-registro', 'FinanceController::storeRegistro');
        $routes->post('transaccion/update', 'FinanceController::updateTransaction');
        $routes->post('transaccion/delete', 'FinanceController::deleteTransaction');
        $routes->post('transaccion/bulk-delete', 'FinanceController::bulkDeleteTransactions');
        $routes->get('recibo-pago/(:num)', 'FinanceController::downloadPaymentReceipt/$1');
        $routes->get('estado-de-cuenta/(:segment)', 'FinanceController::downloadAccountStatement/$1');
        $routes->get('mora/unit-info/(:segment)', 'FinanceController::moraUnitInfo/$1');
        $routes->post('mora/aplicar', 'FinanceController::applyMoraCharge');
        $routes->get('archivo/financial/(.*)', '\App\Controllers\MediaController::image/financial/$1');
        $routes->post('comprobante/review', 'FinanceController::reviewPayment');
        $routes->post('comprobante/delete', 'FinanceController::deletePaymentVoucher');
        $routes->get('archivo/payments/(.*)', '\App\Controllers\MediaController::image/payments/$1');

        
        $routes->get('registro', 'FinanceController::indexView');
        $routes->get('movimientos', 'FinanceController::indexView');
        $routes->get('pagos', 'FinanceController::indexView');
        $routes->get('morosidad', 'FinanceController::indexView');
        $routes->get('extraordinarias', 'FinanceController::extraordinarias');
        $routes->post('extraordinarias/crear', 'FinanceController::storeExtraordinaria');
        $routes->get('extraordinarias/detalle/(:num)', 'FinanceController::detalleExtraordinaria/$1');
        $routes->post('extraordinarias/update', 'FinanceController::updateExtFee');
        $routes->post('extraordinarias/charge/update', 'FinanceController::updateExtCharge');
        $routes->post('extraordinarias/charge/delete', 'FinanceController::deleteExtCharge');
        $routes->post('extraordinarias/charge/pay', 'FinanceController::payExtCharge');
        $routes->post('extraordinarias/delete', 'FinanceController::deleteExtFee');
        $routes->get('historicos', 'FinanceController::historicos');
        $routes->post('historicos/generar', 'FinanceController::generateHistoricos');
    });

    $routes->get('paqueteria', 'ParcelController::indexView');
    $routes->get('paqueteria/detalle/(:num)', 'ParcelController::detail/$1');
    $routes->post('paqueteria/marcar-entregado/(:num)', 'ParcelController::markAsDelivered/$1');
    $routes->get('paqueteria/comprobante/(:num)', 'ParcelController::downloadReceipt/$1');
    $routes->get('seguridad', 'AccessLogController::indexView');
    $routes->post('seguridad/generar-qr', 'AccessLogController::storeQr');
    $routes->post('seguridad/dispositivos/crear', 'AccessLogController::createDeviceCredential');
    $routes->post('seguridad/dispositivos/actualizar-nombre', 'AccessLogController::updateDeviceName');
    $routes->post('seguridad/dispositivos/restablecer-password', 'AccessLogController::resetDevicePassword');
    $routes->post('seguridad/dispositivos/eliminar', 'AccessLogController::deleteDevice');
    $routes->post('seguridad/staff/crear', 'AccessLogController::createStaffMember');
    $routes->post('seguridad/staff/actualizar', 'AccessLogController::updateStaffMember');
    $routes->post('seguridad/staff/eliminar', 'AccessLogController::deleteStaffMember');
    $routes->get('unidades', 'UnitController::indexView');
    $routes->get('unidades/export', 'UnitController::exportCSV');
    $routes->post('unidades/crear', 'UnitController::createWeb');
    $routes->post('unidades/editar', 'UnitController::updateWeb');
    $routes->post('unidades/importar-preview', 'UnitController::previewImportCSV');
    $routes->post('unidades/importar', 'UnitController::processImportCSV');
    $routes->post('unidades/masivo', 'UnitController::bulkUpdate');
    $routes->get('unidades/buscar-usuarios', 'UnitController::searchUsersWeb');
    $routes->post('unidades/crear-usuario-manual', 'UnitController::createManualUserWeb');
    $routes->get('unidades/notas/(:num)', 'UnitController::getNotes/$1');
    $routes->post('unidades/notas/agregar', 'UnitController::addNote');
    $routes->post('unidades/notas/eliminar', 'UnitController::deleteNote');
    $routes->post('unidades/eliminar-json/(:num)', 'UnitController::deleteUnitJson/$1');
    $routes->get('unidades/eliminar/(:num)', 'UnitController::deleteWeb/$1');
    
    $routes->get('notificaciones', 'DashboardController::index'); // mocks
    $routes->get('configuracion', 'SettingsController::indexView');
    $routes->post('configuracion/update-info', 'SettingsController::updateInfo');
    
    // Payment Reminders
    $routes->post('configuracion/payment-reminders/save', 'SettingsController::savePaymentReminder');
    $routes->post('configuracion/payment-reminders/toggle', 'SettingsController::togglePaymentReminder');
    $routes->post('configuracion/payment-reminders/delete', 'SettingsController::deletePaymentReminder');
    $routes->post('configuracion/update-address', 'SettingsController::updateAddress');
    $routes->post('configuracion/upload-logo', 'SettingsController::uploadLogo');
    $routes->post('configuracion/upload-cover', 'SettingsController::uploadCover');
    $routes->get('configuracion/imagen/(.*)', '\App\Controllers\MediaController::image/settings/$1');
    $routes->get('configuracion/admins', 'SettingsController::listAdmins');
    $routes->post('configuracion/admins/add', 'SettingsController::addAdmin');
    $routes->post('configuracion/admins/remove', 'SettingsController::removeAdmin');
    
    // Perfil & Seguridad
    $routes->post('configuracion/update-profile', 'SettingsController::updateProfile');
    $routes->post('configuracion/upload-avatar', 'SettingsController::uploadAvatar');
    $routes->get('configuracion/avatar/(.*)', 'SettingsController::serveAvatar/$1');
    $routes->post('configuracion/update-password', 'SettingsController::updatePassword');
    
    // Secciones
    $routes->post('configuracion/sections/save', 'SettingsController::saveSection');
    $routes->post('configuracion/sections/delete', 'SettingsController::deleteSection');
    // Preferencias Financieras
    $routes->post('configuracion/financial-access', 'SettingsController::saveFinancialAccess');
    // Preferencias Muro
    $routes->post('configuracion/wall-access', 'SettingsController::saveWallAccess');
    // Restricciones por Morosidad
    $routes->post('configuracion/delinquency-restrictions', 'SettingsController::saveDelinquencyRestrictions');
    // Finanzas - Datos Bancarios y Configuración de Pagos
    $routes->post('configuracion/bank-details', 'SettingsController::saveBankDetails');
    $routes->post('configuracion/payment-config', 'SettingsController::savePaymentConfig');
    // Suscripción
    $routes->get('configuracion/subscription', 'SettingsController::getSubscription');
    $routes->post('configuracion/change-plan', 'SettingsController::changePlan');
    $routes->post('configuracion/billing-portal', 'SettingsController::billingPortal');
    $routes->post('configuracion/delete-community', 'SettingsController::deleteCommunity');
    $routes->get('novedades', 'DashboardController::index');

    // Onboarding Wizard — Creación de Nuevo Condominio
    $routes->get('onboarding', 'OnboardingController::index');
    $routes->post('onboarding/create', 'OnboardingController::create');
    $routes->post('onboarding/units-template', 'OnboardingController::downloadUnitsTemplate');
    $routes->post('onboarding/units-preview', 'OnboardingController::previewUnitsCSV');
    // Switch Condominium — Cambia el tenant activo en sesión web
    $routes->get('switch-condo/(:num)', 'DashboardController::switchCondo/$1');

    $routes->get('test-tenant', function() {
        $tenantService = \App\Services\TenantService::getInstance();
        $tenantService->setTenantId(1); 
        $unitModel = new \App\Models\Tenant\UnitModel();
        $c1 = clone $unitModel->builder();
        $res1 = "T1 units(countAll): " . $c1->countAllResults() . ", T1 units(findAll): " . count($unitModel->findAll());
        
        $tenantService->setTenantId(5); 
        $unitModel2 = new \App\Models\Tenant\UnitModel();
        $c2 = clone $unitModel2->builder();
        $res2 = "T5 units(countAll): " . $c2->countAllResults() . ", T5 units(findAll): " . count($unitModel2->findAll());
        
        $res3 = "ALL DB units: " . (new \App\Models\Tenant\UnitModel())->builder()->countAllResults();

        return $res1 . "\n" . $res2 . "\n" . $res3;
    });
});

// ==========================================
// RUTAS PÚBLICAS DE INVITACIÓN
// ==========================================
$routes->get('invite/(:segment)', 'PublicInvitationController::accept/$1');
$routes->post('invite/(:segment)/register', 'PublicInvitationController::register/$1');
$routes->post('register-resident', 'PublicInvitationController::registerManual');

// API DE ADMINISTRADORES
$routes->group('api/v1/admin', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['apiauth', 'tenant']], static function ($routes) {
    // Finanzas
    $routes->get('finance/units', 'AdminFinanceApiController::getUnits');
    $routes->get('finance/unit/(:num)', 'AdminFinanceApiController::getUnitFinance/$1');
    $routes->get('finance/units/(:num)', 'AdminFinanceApiController::getUnitFinance/$1'); // Alias para compatibilidad Flutter
    $routes->post('finance/store', 'AdminFinanceApiController::store'); // Restaurar "Nuevo Registro" (income/expense)
    $routes->get('finance/community', 'AdminFinanceApiController::communityFinances');
    $routes->get('finance/community/transactions', 'AdminFinanceApiController::communityTransactions');
    $routes->get('finance/community/report', 'AdminFinanceApiController::communityReport');
    // Reservas
    $routes->get('bookings/active', 'AdminBookingApiController::active');
    $routes->get('bookings/history', 'AdminBookingApiController::history');
    $routes->post('bookings/(:num)/approve', 'AdminBookingApiController::approve/$1');
    $routes->post('bookings/(:num)/reject', 'AdminBookingApiController::reject/$1');
    $routes->options('bookings/(:num)/approve', 'AdminBookingApiController::approve/$1');
    $routes->options('bookings/(:num)/reject', 'AdminBookingApiController::reject/$1');
});

// ==========================================
// API REST V1 (PARA PWAS DE RESIDENTES Y CASETAS)
// ==========================================
$routes->post('api/v1/login', 'Api\V1\ApiAuthController::login');
$routes->post('api/v1/logout', 'Api\V1\ApiAuthController::logout');
$routes->post('api/v1/register-invitation', 'PublicInvitationController::registerApi');
$routes->options('api/v1/login', 'Api\V1\ApiAuthController::login'); // Preflight CORS
$routes->options('api/v1/logout', 'Api\V1\ApiAuthController::logout');
$routes->options('api/v1/register-invitation', 'PublicInvitationController::registerApi');

// API Device Push Subscriptions (FCM Token Registration)
$routes->group('api/v1/devices', ['namespace' => 'App\\Controllers\\Api\\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->post('subscribe', 'DeviceSubscriptionController::subscribe');
    $routes->delete('unsubscribe', 'DeviceSubscriptionController::unsubscribe');
    $routes->options('subscribe', 'DeviceSubscriptionController::subscribe');
    $routes->options('unsubscribe', 'DeviceSubscriptionController::unsubscribe');
});

$routes->group('api/v1/security', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->post('validate-qr', 'SecurityController::validateQr');
    $routes->post('entry', 'SecurityController::entry');
    $routes->post('entries', 'SecurityController::entry'); // Alias Guardia
    $routes->post('qr-entry', 'SecurityController::entry'); // Alias Guardia
    $routes->post('exit', 'SecurityController::exit');
    $routes->get('active_visitors', 'SecurityController::activeVisitors');
    $routes->get('units_directory', 'SecurityController::unitsDirectory');
    $routes->get('directory', 'SecurityController::unitsDirectory'); // Alias Guardia
    $routes->get('units', 'SecurityController::unitsDirectory'); // Alias Guardia
    $routes->get('entry_logs', 'SecurityController::entryLogs');
    $routes->get('entries/history', 'SecurityController::entryLogs'); // Alias Guardia
    $routes->get('visitor_detail/(:num)', 'SecurityController::visitorDetail/$1');
    $routes->get('entries/(:num)', 'SecurityController::visitorDetail/$1'); // Alias Guardia
    $routes->options('validate-qr', 'SecurityController::validateQr');
    $routes->options('entry', 'SecurityController::entry');
    $routes->options('entries', 'SecurityController::entry'); // Alias Guardia
    $routes->options('qr-entry', 'SecurityController::entry'); // Alias Guardia
    $routes->options('exit', 'SecurityController::exit');

    // Offline QR Cache & Sync
    $routes->get('offline-cache', 'SecurityController::offlineCache');
    $routes->post('sync-access', 'SecurityController::syncAccess');
    $routes->options('sync-access', 'SecurityController::syncAccess');

    // Paquetería
    $routes->get('couriers', 'ParcelController::couriers');
    $routes->get('parcels/pending', 'ParcelController::pending');
    $routes->get('parcels/count', 'ParcelController::count');
    $routes->get('parcels/history', 'ParcelController::history');
    $routes->get('parcels/(:num)', 'ParcelController::detail/$1');
    $routes->post('parcels', 'ParcelController::create');
    $routes->post('parcels/(:num)/deliver', 'ParcelController::deliver/$1');
    $routes->options('parcels', 'ParcelController::create');
    $routes->options('parcels/(:num)/deliver', 'ParcelController::deliver/$1');
});

// ==========================================
// PUBLIC ASSETS API (sin auth, CDN-ready)
// ==========================================
$routes->get('api/v1/assets/(:alpha)/(:segment)/(.*)', 'Api\V1\AssetsController::serve/$1/$2/$3');
$routes->get('api/v1/assets/(:alpha)/(.*)', 'Api\V1\AssetsController::serve/$1/null/$2');
$routes->get('api/v1/assets/info/(:alpha)/(:segment)/(.*)', 'Api\V1\AssetsController::info/$1/$2/$3');

// Legacy endpoints (compatible con código existente)
$routes->get('api/v1/security/photo/(.*)', 'MediaController::image/access/$1');
$routes->get('api/v1/security/parcel-photo/(.*)', 'MediaController::image/parcels/$1');
$routes->get('api/v1/amenities/image/(.*)', '\App\Controllers\MediaController::image/amenities/$1');
$routes->get('api/v1/amenities/document/(.*)', '\App\Controllers\Admin\AmenityController::serveDocument/$1');
$routes->get('writable/uploads/staff/(.*)', 'MediaController::image/staff/$1');
$routes->get('writable/uploads/tickets/(.*)', 'MediaController::image/tickets/$1');

$routes->get('qr/(:segment)', 'PublicQrController::show/$1');

$routes->get('api/v1/public/image/(.*)', 'MediaController::image/$1');

// API Condominiums (Selector + Switch Tenant + Admin Settings)
$routes->group('api/v1/condominiums', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('mine', 'CondominiumApiController::mine');
    $routes->post('switch', 'CondominiumApiController::switchTenant');
    $routes->options('switch', 'CondominiumApiController::switchTenant');
    // Admin Community Settings
    $routes->get('settings', 'CondominiumApiController::settings');
    $routes->post('settings/update', 'CondominiumApiController::updateSettings');
    $routes->post('settings/image', 'CondominiumApiController::uploadSettingsImage');
    $routes->options('settings/update', 'CondominiumApiController::updateSettings');
    $routes->options('settings/image', 'CondominiumApiController::uploadSettingsImage');
});

// API Finance
$routes->group('api/v1/finance', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('balance', 'FinanceController::balance');
    $routes->get('payments', 'FinanceController::payments');
    $routes->get('statement', 'FinanceController::statement');
    $routes->post('payment-proof', 'FinanceController::uploadPaymentProof');
    $routes->options('payment-proof', 'FinanceController::uploadPaymentProof');
    $routes->get('delinquent-units', 'FinanceController::delinquentUnits');
    $routes->get('file/(.*)', 'FinanceController::serveFinanceFile/$1');
});

// API Announcements
$routes->group('api/v1/announcements', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('/', 'AnnouncementController::index');
    $routes->post('/', 'AnnouncementController::store'); // Crear publicación desde Flutter
    $routes->options('/', 'AnnouncementController::store');
    $routes->get('(:num)', 'AnnouncementController::detail/$1');
    $routes->put('(:num)', 'AnnouncementController::update/$1');    // Editar publicación propia
    $routes->delete('(:num)', 'AnnouncementController::destroy/$1'); // Eliminar publicación propia
    $routes->post('(:num)/like', 'AnnouncementController::toggleLike/$1');
    $routes->post('(:num)/comments', 'AnnouncementController::addComment/$1');
    $routes->post('comments/(:num)/delete', 'AnnouncementController::deleteComment/$1');
    $routes->options('(:num)/like', 'AnnouncementController::toggleLike/$1');
    $routes->options('(:num)/comments', 'AnnouncementController::addComment/$1');
    $routes->options('comments/(:num)/delete', 'AnnouncementController::deleteComment/$1');
    $routes->get('file/(.*)', 'AnnouncementController::serveFile/$1'); // Protegido para imágenes y red segura
});

// Ruta pública y sin token (por ofuscación) exclusivamente para visores Mobile nativos como pdfrx
// ya que los frameworks de iOS/Android pierden los HTTP Headers en peticiones "Range" de binarios.
$routes->get('api/v1/pdf/file/(.*)', '\App\Controllers\Api\V1\AnnouncementController::serveFile/$1');

// API Amenities & Bookings
$routes->group('api/v1/amenities', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('/', 'AmenityController::index');
    $routes->get('(:num)', 'AmenityController::detail/$1');
    $routes->get('(:num)/availability', 'AmenityController::availability/$1');
});

$routes->group('api/v1/bookings', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('active', 'BookingController::active');
    $routes->post('/', 'BookingController::create');
    $routes->post('(:num)/cancel', 'BookingController::cancel/$1');
    $routes->post('(:num)/approve', 'BookingController::approve/$1');
    $routes->post('(:num)/reject', 'BookingController::reject/$1');
    $routes->options('/', 'BookingController::create');
    $routes->options('(:num)/cancel', 'BookingController::cancel/$1');
    $routes->options('(:num)/approve', 'BookingController::approve/$1');
    $routes->options('(:num)/reject', 'BookingController::reject/$1');
});

// API Calendar
$routes->group('api/v1/calendar', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('/', 'CalendarController::index');
    $routes->get('(:num)', 'CalendarController::detail/$1');
    $routes->post('/', 'CalendarController::create');
    $routes->post('(:num)/delete', 'CalendarController::delete/$1');
    $routes->options('/', 'CalendarController::create');
    $routes->options('(:num)/delete', 'CalendarController::delete/$1');
});

// API Resident Profile
$routes->group('api/v1/resident', ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apiauth'], static function($routes) {
    $routes->get('profile', 'ResidentController::profile');
    $routes->get('my-unit', 'ResidentController::myUnit');
    $routes->post('update-profile', 'ResidentController::updateProfile');
    $routes->post('upload-avatar', 'ResidentController::uploadAvatar');
    $routes->get('avatar/(.*)', 'ResidentController::serveAvatar/$1');
    $routes->post('update-password', 'ResidentController::updatePassword');
    $routes->options('update-profile', 'ResidentController::updateProfile');
    $routes->options('upload-avatar', 'ResidentController::uploadAvatar');
    $routes->options('update-password', 'ResidentController::updatePassword');

    // QR Codes (Resident)
    $routes->get('qr-codes', 'ResidentController::getQrs');
    $routes->post('qr-codes', 'ResidentController::createQr');
    $routes->delete('qr-codes/(:num)', 'ResidentController::deleteQr/$1');
    $routes->options('qr-codes', 'ResidentController::createQr');
    $routes->options('qr-codes/(:num)', 'ResidentController::deleteQr/$1');

    // Access Logs (Resident)
    $routes->get('access-logs', 'ResidentController::getAccessLogs');

    // Finance (Resident) — Endpoints consumidos por Flutter
    $routes->get('finances', 'FinanceController::myUnitFinances');
    $routes->get('finances/community', 'FinanceController::communityFinances');
    $routes->get('finances/community-report', 'FinanceController::communityReport');
    $routes->get('finances/community-transactions', 'FinanceController::communityTransactions');
    $routes->post('finances/(:num)/receipt', 'FinanceController::uploadReceipt/$1');
    $routes->options('finances/(:num)/receipt', 'FinanceController::uploadReceipt/$1');
    $routes->post('finances/upload-payment', 'FinanceController::uploadPaymentProof');
    $routes->options('finances/upload-payment', 'FinanceController::uploadPaymentProof');
    $routes->get('finances/account-statement', 'FinanceController::downloadMyStatement');
    $routes->get('finances/payment-receipt/(:num)', 'FinanceController::downloadMyReceipt/$1');
    $routes->get('finances/receipts-history', 'FinanceController::receiptsHistory');
    
    // Notificaciones
    $routes->get('notifications', 'NotificationController::index');
    $routes->post('notifications/(:num)/read', 'NotificationController::markRead/$1');
    $routes->post('notifications/read-all', 'NotificationController::markAllRead');

    // Encuestas (Polls) - Resident API
    $routes->group('polls', static function($routes) {
        $routes->get('/', 'PollController::index');
        $routes->get('active-count', 'PollController::activeCount');
        $routes->post('create', 'PollController::create');
        $routes->delete('(:any)/delete', 'PollController::delete/$1');
        $routes->post('(:any)/delete', 'PollController::delete/$1');
        $routes->get('(:any)/voters', 'PollController::voters/$1');
        $routes->get('(:any)', 'PollController::details/$1');
        $routes->post('vote', 'PollController::vote');
        $routes->put('change-vote', 'PollController::changeVote');
        
        $routes->options('create', 'PollController::create');
        $routes->options('(:any)/delete', 'PollController::delete/$1');
        $routes->options('(:any)/voters', 'PollController::voters/$1');
        $routes->options('vote', 'PollController::vote');
        $routes->options('change-vote', 'PollController::changeVote');
    });

    // Tickets (Reportes) — Resident API
    $routes->get('tickets', 'TicketApiController::active');
    $routes->get('tickets/resolved', 'TicketApiController::resolved');
    $routes->get('tickets/(:num)', 'TicketApiController::detail/$1');
    $routes->post('tickets', 'TicketApiController::create');
    $routes->get('tickets/(:num)/messages', 'TicketApiController::getMessages/$1');
    $routes->post('tickets/(:num)/message', 'TicketApiController::sendMessage/$1');
    $routes->post('tickets/(:num)/resolve', 'TicketApiController::resolve/$1');
    $routes->get('tickets/media/(.*)', 'TicketApiController::serveMedia/$1');
    $routes->options('tickets', 'TicketApiController::create');
    $routes->options('tickets/(:num)/message', 'TicketApiController::sendMessage/$1');
    $routes->options('tickets/(:num)/resolve', 'TicketApiController::resolve/$1');

    // Documentos (Resident / App)
    $routes->get('documents', 'DocumentController::index');
    $routes->get('documents/folders', 'DocumentController::getFolders');
    $routes->get('documents/(:num)', 'DocumentController::getDocument/$1');
    $routes->get('documents/download/(:num)', 'DocumentController::download/$1');
    $routes->post('documents/folder', 'DocumentController::createFolder');
    $routes->post('documents/upload', 'DocumentController::uploadFiles');
    $routes->post('documents/rename/(:num)', 'DocumentController::renameDocument/$1');
    $routes->post('documents/delete/(:num)', 'DocumentController::deleteDocument/$1');
    $routes->post('documents/toggle-star/(:num)', 'DocumentController::toggleStar/$1');
    // Preflight Documentos
    $routes->options('documents/folder', 'DocumentController::createFolder');
    $routes->options('documents/upload', 'DocumentController::uploadFiles');
    $routes->options('documents/rename/(:num)', 'DocumentController::renameDocument/$1');
    $routes->options('documents/delete/(:num)', 'DocumentController::deleteDocument/$1');
    $routes->options('documents/toggle-star/(:num)', 'DocumentController::toggleStar/$1');
});

// ══════════════════════════════════════════════════════════
// WEBHOOKS DE TERCEROS
// ══════════════════════════════════════════════════════════
$routes->post('api/webhooks/stripe', 'SuperAdmin\BillingController::webhook');

