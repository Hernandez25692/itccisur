@include('errors.layout', [
    'title' => 'Sesión expirada',
    'code' => '419',
    'message' => 'Tu sesión ha expirado por inactividad.',
    'hint' => 'Vuelve a iniciar sesión e intenta nuevamente.'
])
