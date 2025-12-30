@include('errors.layout', [
    'title' => 'Error interno del sistema',
    'code' => '500',
    'message' => 'Ocurrió un error inesperado al procesar la solicitud.',
    'hint' => 'El equipo de TI ya puede revisar los registros del sistema.'
])
