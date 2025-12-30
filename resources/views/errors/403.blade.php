@include('errors.layout', [
    'title' => 'Acceso denegado',
    'code' => '403',
    'message' => 'No tienes permisos para acceder a este recurso.',
    'hint' => 'Si necesitas acceso, solicita autorización al Departamento de TI.'
])
