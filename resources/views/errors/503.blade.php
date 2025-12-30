@include('errors.layout', [
    'title' => 'Servicio no disponible',
    'code' => '503',
    'message' => 'El sistema se encuentra temporalmente fuera de servicio.',
    'hint' => 'Estamos realizando tareas de mantenimiento. Intenta más tarde.'
])
