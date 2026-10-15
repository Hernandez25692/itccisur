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
    | inicio_sin_acumulacion: desde esta fecha las vacaciones ya no se
    | acumulan. Los días de cada periodo vencen al siguiente aniversario y
    | todo lo acumulado antes de esta fecha vence el día anterior
    | (31-dic-2026).
    |
    */

    'vacaciones' => [
        'dias_por_antiguedad' => [
            1 => 10,
            2 => 12,
            3 => 15,
            4 => 20,
        ],

        'inicio_sin_acumulacion' => '2027-01-01',

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
        'Ejecutiva de Registro',
        'Receptor',
        'Ejecutiva de Asistencia a Socios',
        'Gerente de Servicios Empresariales y Afiliaciones',
        'Encargado IT',
        'Gerente Administrativa',
        'Contador',
        'Gestor de Cobros y Promotor de Afiliaciones',
        'Asistente Administrativo',
        'Ejecutivo de Recursos Humanos',
        'Gerente de Operaciones Registrales',
        'Registrador Titular',
        'Auxiliar de Logística',
        'Auxiliar Contable',
        'Ejecutivo de Proyecto',
        'Extractador',
        'Recursos Humanos',
        'Registrador Adjunto',
        'Implementadora',
        'Delegado Registral',
        'Auxiliar de Limpieza',
        'Ejecutivo de Marketing',
        'Digitalizador de Documentos',
        'Ejecutivo de Control y Producción',
    ],

];
