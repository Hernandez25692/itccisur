@include('errors.layout', [
    'title' => 'Demasiadas solicitudes',
    'code' => '429',
    'message' => 'Has realizado demasiadas solicitudes en poco tiempo.',
    'hint' => 'Espera unos minutos antes de intentarlo nuevamente.'
])
