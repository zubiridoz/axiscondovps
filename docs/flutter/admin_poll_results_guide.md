# Mostrar Resultados de Encuestas a Administradores

Sí, es totalmente posible. El backend (API) ya está preparado y enviando toda la información necesaria. Actualmente, la API devuelve los campos `is_admin` y la lista de `options` (con sus respectivos `vote_count` y `percentage`) para todos los usuarios, independientemente de si han votado o no.

El ocultamiento de los resultados antes de votar es una regla visual (de la interfaz de usuario) en tu aplicación móvil en Flutter.

Para lograr que los administradores vean los resultados sin tener que votar, solo debes hacer un pequeño ajuste en tu código de Flutter.

## Modificación en Flutter

Busca en el código de tu aplicación Flutter la pantalla donde se muestra el detalle de la encuesta (probablemente `PollDetailScreen` o similar).

Actualmente, debes tener una condición que revisa si el usuario ya votó para mostrar los resultados, algo parecido a esto:

```dart
// CÓDIGO ACTUAL (Aproximado)
if (poll.userVoted) {
  return _buildResultsView(); // Muestra las barras de porcentaje
} else {
  return _buildVotingOptions(); // Muestra los botones/radios para votar
}
```

### Opción 1: Administrador solo ve resultados (no vota)
Si quieres que el administrador automáticamente vea los resultados y no necesite (ni pueda) votar desde esa vista rápida, cambia la condición a:

```dart
// NUEVO CÓDIGO
if (poll.userVoted || poll.isAdmin) {
  return _buildResultsView(); // Administradores y residentes que ya votaron ven esto
} else {
  return _buildVotingOptions(); 
}
```

### Opción 2: Administrador ve resultados y también puede votar
Si prefieres que el administrador vea cómo van los porcentajes, pero que aún tenga los botones disponibles para emitir su propio voto, podrías mostrar ambos elementos o hacer un diseño mixto:

```dart
// NUEVO CÓDIGO MIXTO
Column(
  children: [
    if (poll.isAdmin && !poll.userVoted)
       _buildResultsView(), // Muestra los resultados arriba a los admins
       
    if (!poll.userVoted)
       _buildVotingOptions(), // Y también muestra las opciones para votar abajo
       
    if (poll.userVoted)
       _buildResultsView(), // Si ya votó, solo muestra resultados
  ]
)
```

**Nota:** No es necesario hacer ningún cambio en el servidor (backend/PHP). La API ya te está enviando la variable `is_admin: true` cuando el usuario autenticado tiene rol de administrador en el condominio activo, así como los porcentajes en tiempo real. Solo tienes que usar esa variable `isAdmin` en tus condiciones de Dart/Flutter.
