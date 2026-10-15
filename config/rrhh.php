<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vacaciones
    |--------------------------------------------------------------------------
    |
    | dias_por_antiguedad: días que se acreditan al cumplir cada aniversario
    | laboral (años cumplidos => días). Del 4.º año en adelante: 20 días.
    |
    | Año calendario: los días acreditados en un año deben tomarse dentro de
    | ese año; lo no tomado vence el 31 de diciembre y el 1 de enero todos
    | empiezan en cero. Se pueden adelantar (saldo negativo) y lo adelantado
    | se descuenta de la siguiente acreditación, aunque sea del año siguiente.
    |
    | primer_cierre: primer 31 de diciembre en que se aplica el cierre. Lo
    | acreditado antes (incluidas las vacaciones acumuladas) vence ese día.
    |
    | areas_sin_vencimiento: sus días nunca vencen (se acumulan).
    |
    */

    'vacaciones' => [
        'dias_por_antiguedad' => [
            1 => 10,
            2 => 12,
            3 => 15,
            4 => 20,
        ],

        'primer_cierre' => '2026-12-31',

        'areas_sin_vencimiento' => [
            'Dirección Ejecutiva',
        ],

        // Saldo a partir del cual el empleado se considera "crítico"
        'saldo_critico' => 15,

        // Días de anticipación para alertar vencimientos
        'alerta_vencimiento_dias' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Jornada laboral de referencia
    |--------------------------------------------------------------------------
    |
    | Se usa para convertir horas en días (8 h = 1 día) y para excluir la hora
    | de almuerzo en vacaciones/permisos por horas. Los días listados en
    | dias_no_descontables (0 = domingo) no se descuentan en vacaciones por
    | días; los sábados sí se descuentan.
    |
    */

    'jornada' => [
        'entrada' => '08:00',
        'almuerzo_inicio' => '12:00',
        'almuerzo_fin' => '13:00',
        'salida' => '17:00',
        'dias_no_descontables' => [0],
    ],

    'areas' => [
        'Dirección Ejecutiva',
        'Gerencia Administrativa y Financiera',
        'Gerencia de Operaciones Registrales',
        'Gerencia de Servicios Empresariales y Afiliaciones',
    ],

    'cargos' => [
        'Director Ejecutivo',
        'Receptor',
        'Ejecutivo de Asistencia a Socios',
        'Gerente de Servicios Empresariales y Afiliaciones',
        'Encargado IT',
        'Gerente Administrativa Financiera',
        'Gestor de Cobros y Promotor de Afiliaciones',
        'Asistente Administrativo',
        'Ejecutivo de Recursos Humanos',
        'Gerente de Operaciones Registrales',
        'Registrador Titular',
        'Auxiliar Contable',
        'Ejecutivo de Proyecto',
        'Recursos Humanos',
        'Registrador Adjunto',
        'Implementador',
        'Delegado Registral',
        'Auxiliar de Servicios Generales',
        'Ejecutivo de Marketing',
        'Digitalizador de Documentos',
        'Ejecutivo de Control y Producción',
        'Ejecutivo de Atención al Cliente/Auxiliar de Logística',
    ],

];
