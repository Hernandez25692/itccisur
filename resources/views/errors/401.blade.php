@include('errors.layout', [
    'title' => 'Sesión no autenticada',
    'code' => '401',
    'message' => 'Tu sesión no está activa o ha expirado.',
    'hint' => 'Inicia sesión nuevamente para continuar.',
])
