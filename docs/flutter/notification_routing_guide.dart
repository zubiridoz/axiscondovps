// ═══════════════════════════════════════════════════════════════
// GUÍA DE INTEGRACIÓN FLUTTER: NAVEGACIÓN DESDE NOTIFICACIONES PUSH
// ═══════════════════════════════════════════════════════════════
// 
// Para que la app reaccione al tocar la notificación de "Nuevo comentario",
// debes interceptar el payload FCM en tu manejador principal.
// 
// Cuando el usuario toca la notificación, el backend envía este payload:
// {
//    "type": "announcement",
//    "announcement_id": "123",
//    "click_action": "FLUTTER_NOTIFICATION_CLICK"
// }

// 1. En el archivo donde manejas FirebaseMessaging (usualmente main.dart o un NotificationService):

import 'package:firebase_messaging/firebase_messaging.dart';

void setupFirebaseMessaging(BuildContext context) {
  // Manejar cuando la app está en segundo plano y se abre tocando la notificación
  FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
    _handleNotificationClick(context, message.data);
  });

  // Manejar si la app fue lanzada desde el estado "Terminated" por una notificación
  FirebaseMessaging.instance.getInitialMessage().then((RemoteMessage? message) {
    if (message != null) {
      // Necesitarás usar un pequeño delay o esperar a que el router esté listo
      Future.delayed(const Duration(milliseconds: 500), () {
        _handleNotificationClick(context, message.data);
      });
    }
  });
}

// 2. Función de enrutamiento
void _handleNotificationClick(BuildContext context, Map<String, dynamic> data) {
  final String? type = data['type'];
  
  if (type == 'announcement') {
    final String? announcementIdStr = data['announcement_id'];
    if (announcementIdStr != null) {
      final int announcementId = int.tryParse(announcementIdStr) ?? 0;
      
      if (announcementId > 0) {
        // Redirigir a la vista de detalle del anuncio
        // NOTA: Ajusta esta ruta a cómo tengas definido tu Navigator
        Navigator.of(context).pushNamed(
          '/announcement_detail',
          arguments: {'announcementId': announcementId},
        );
      }
    }
  } 
  else if (type == 'payment_reminder') {
    // Ejemplo para los recordatorios de pago
    Navigator.of(context).pushNamed('/finances');
  }
}
