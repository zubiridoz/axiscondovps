// ═══════════════════════════════════════════════════════════════════════════
//  SOPORTE PARA ARCHIVOS DE WORD / DOCUMENTOS EN EL MURO DE AVISOS
// ═══════════════════════════════════════════════════════════════════════════
// 
// Actualmente, la app asume que cualquier tipo de archivo desconocido 
// (como file_type == 'document' para archivos .doc y .docx) debe renderizarse
// por defecto como una imagen, lo cual genera un ícono de "imagen rota".
//
// Para solucionar esto definitivamente, debes agregar el caso 'document' 
// en tu lógica de renderizado de adjuntos (usualmente en un switch/if), 
// tratándolo de la misma manera que tratas a los PDFs (abriendo el enlace 
// con url_launcher para que el teléfono lo descargue/abra).
//
// EJEMPLO DE CÓDIGO SUGERIDO (En el widget donde construyes los attachments):
//
// String fileType = attachment['file_type'] ?? '';
// String url = attachment['url'] ?? '';
//
// if (fileType == 'image') {
//   // Tu código actual para mostrar imágenes
//   return Image.network(url);
// } else if (fileType == 'video') {
//   // Tu código actual para mostrar videos
//   return VideoPlayerWidget(url: url);
// } else if (fileType == 'pdf' || fileType == 'document') {
//   // AGREGAR 'document' AQUÍ
//   // Mostrar un ícono de documento y abrir la URL al tocar
//   return ListTile(
//     leading: Icon(
//       fileType == 'pdf' ? Icons.picture_as_pdf : Icons.description, 
//       color: Colors.red
//     ),
//     title: Text(attachment['original_name'] ?? 'Documento'),
//     onTap: () async {
//       if (await canLaunchUrl(Uri.parse(url))) {
//         await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
//       }
//     },
//   );
// } else {
//   // Fallback genérico (Opcional)
//   return const SizedBox.shrink();
// }
