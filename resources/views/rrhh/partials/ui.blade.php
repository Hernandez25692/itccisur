{{--
    Sistema visual de RRHH. Todas las reglas viven bajo .rh para no afectar
    al resto del sistema. Tipografía: Archivo en dos anchos (condensado para
    títulos y encabezados de tabla, normal para el contenido).
--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,300..800&display=swap">

<style>
    .rh {
        --ink: #142039;
        --ink-2: #4a5672;
        --ink-3: #5f6984;
        --paper: #ffffff;
        --paper-2: #f5f6f9;
        --paper-3: #eceff5;
        --rule: #e3e6ee;
        --rule-2: #cfd5e1;
        --navy: #0c1c3c;
        --gold: #c5a049;
        --gold-ink: #8a6a1f;
        --gold-wash: #faf5e8;

        /* Barra de saldo (validada: azul del periodo / dorado de acumuladas) */
        --serie-periodo: #1c5cab;
        --serie-acum: #c5a049;
        --pista: #e4ecf8;

        /* Días tomados por mes: un solo azul, de claro a oscuro */
        --heat-1: #86b6ef;
        --heat-2: #256abf;
        --heat-3: #184f95;
        --heat-4: #0d366b;

        /* Estados: siempre con ícono y texto */
        --good: #0ca30c;
        --good-ink: #006300;
        --warn: #fab219;
        --warn-ink: #8a5300;
        --warn-wash: #fff6dc;
        --serious: #ec835a;
        --serious-ink: #a8431c;
        --serious-wash: #fdeee6;
        --crit: #d03b3b;
        --crit-ink: #b42318;
        --crit-wash: #fcebea;

        font-family: 'Archivo', system-ui, -apple-system, 'Segoe UI', sans-serif;
        font-size: 14px;
        line-height: 1.45;
        color: var(--ink);
        font-stretch: 100%;
    }

    .rh *,
    .rh *::before,
    .rh *::after {
        box-sizing: border-box;
    }

    .rh a {
        color: inherit;
        text-decoration: none;
    }

    .rh :focus-visible {
        outline: 2px solid var(--navy);
        outline-offset: 2px;
        border-radius: 4px;
    }

    .rh .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .rh .num {
        font-variant-numeric: tabular-nums;
    }

    /* ── Encabezado de página ── */
    .rh-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem 2rem;
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--rule);
    }

    .rh-head-main {
        min-width: 0;
    }

    .rh-title-row {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 0.25rem 0.9rem;
    }

    .rh-title {
        font-size: 2.25rem;
        line-height: 1.05;
        font-weight: 700;
        font-stretch: 72%;
        letter-spacing: -0.01em;
        color: var(--navy);
        margin: 0;
    }

    .rh-sub {
        margin: 0.45rem 0 0;
        color: var(--ink-2);
        font-size: 0.95rem;
        max-width: 68ch;
    }

    .rh-sub strong {
        color: var(--ink);
        font-weight: 650;
    }

    .rh-head-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* Selector de año: la cifra del año es parte del título */
    .rh-year {
        display: inline-flex;
        align-items: baseline;
        gap: 0.15rem;
    }

    .rh-year-num {
        font-size: 2.25rem;
        line-height: 1.05;
        font-weight: 300;
        font-stretch: 72%;
        color: var(--ink-3);
        font-variant-numeric: lining-nums;
    }

    .rh-year-step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 6px;
        color: var(--ink-3);
        align-self: center;
        transition: background-color .15s, color .15s;
    }

    .rh-year-step:hover {
        background: var(--paper-3);
        color: var(--navy);
    }

    .rh-year-step svg {
        width: 16px;
        height: 16px;
    }

    /* ── Botones ── */
    .rh-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        height: 2.5rem;
        padding: 0 1rem;
        border-radius: 8px;
        border: 1px solid var(--rule-2);
        background: var(--paper);
        color: var(--ink);
        font: inherit;
        font-weight: 600;
        font-size: 0.875rem;
        white-space: nowrap;
        cursor: pointer;
        transition: background-color .15s, border-color .15s, color .15s;
    }

    .rh-btn:hover {
        border-color: var(--ink-3);
        background: var(--paper-2);
    }

    .rh-btn svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .rh-btn.is-primary {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--navy);
    }

    .rh-btn.is-primary:hover {
        background: #b8933f;
        border-color: #b8933f;
    }

    .rh-btn.is-dark {
        background: var(--navy);
        border-color: var(--navy);
        color: #fff;
    }

    .rh-btn.is-dark:hover {
        background: #1a2c52;
    }

    .rh-btn.is-quiet {
        border-color: transparent;
        background: transparent;
        color: var(--ink-2);
    }

    .rh-btn.is-quiet:hover {
        background: var(--paper-3);
        color: var(--ink);
    }

    .rh-btn.is-danger:hover {
        color: var(--crit-ink);
        background: var(--crit-wash);
    }

    .rh-btn.is-sm {
        height: 2rem;
        padding: 0 0.7rem;
        font-size: 0.8rem;
        border-radius: 7px;
    }

    /* ── Avisos ── */
    .rh-notice {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1.5rem;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        background: var(--paper-2);
        border: 1px solid var(--rule);
    }

    .rh-notice-text {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        max-width: 80ch;
    }

    .rh-notice svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .rh-notice.is-good {
        background: #eef8ee;
        border-color: #cfe9cf;
    }

    .rh-notice.is-good svg {
        color: var(--good-ink);
    }

    .rh-notice.is-warn {
        background: var(--warn-wash);
        border-color: #f5dc9a;
    }

    .rh-notice.is-warn svg {
        color: var(--warn-ink);
    }

    .rh-notice.is-crit {
        background: var(--crit-wash);
        border-color: #f2c4c1;
    }

    .rh-notice.is-crit svg {
        color: var(--crit-ink);
    }

    /* ── Lo que requiere atención ── */
    .rh-attn {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 0 0 1.25rem;
        padding: 0;
        list-style: none;
    }

    .rh-attn-item {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.55rem 0.85rem 0.55rem 0.7rem;
        border-radius: 8px;
        border: 1px solid var(--rule);
        background: var(--paper);
        font-size: 0.875rem;
        color: var(--ink-2);
        transition: border-color .15s, background-color .15s;
    }

    a.rh-attn-item:hover {
        border-color: var(--rule-2);
        background: var(--paper-2);
    }

    .rh-attn-item strong {
        color: var(--ink);
        font-weight: 650;
    }

    .rh-attn-item.is-active {
        border-color: var(--navy);
        box-shadow: inset 0 0 0 1px var(--navy);
    }

    .rh-attn-item svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    .is-tone-good svg,
    svg.is-tone-good {
        color: var(--good);
    }

    .is-tone-warn svg,
    svg.is-tone-warn {
        color: #d69300;
    }

    .is-tone-serious svg,
    svg.is-tone-serious {
        color: var(--serious);
    }

    .is-tone-crit svg,
    svg.is-tone-crit {
        color: var(--crit);
    }

    /* ── Filtros ── */
    .rh-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .rh-field {
        position: relative;
        display: flex;
        align-items: center;
    }

    .rh-field.is-search {
        flex: 1 1 260px;
        max-width: 360px;
    }

    .rh-field.is-search svg {
        position: absolute;
        left: 0.75rem;
        width: 16px;
        height: 16px;
        color: var(--ink-3);
        pointer-events: none;
    }

    .rh .rh-input,
    .rh .rh-select {
        height: 2.5rem;
        width: 100%;
        border: 1px solid var(--rule-2);
        border-radius: 8px;
        background-color: var(--paper);
        color: var(--ink);
        font: inherit;
        font-size: 0.875rem;
        padding: 0 0.85rem;
        box-shadow: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .rh .rh-input {
        padding-left: 2.25rem;
    }

    .rh .rh-select {
        width: auto;
        min-width: 10rem;
        max-width: 17rem;
        padding-right: 2.25rem;
        cursor: pointer;
    }

    .rh .rh-input::placeholder {
        color: var(--ink-3);
    }

    .rh .rh-input:hover,
    .rh .rh-select:hover {
        border-color: var(--ink-3);
    }

    .rh .rh-input:focus,
    .rh .rh-select:focus {
        outline: none;
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(12, 28, 60, 0.12);
    }

    .rh-filters-end {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rh-clear {
        font-size: 0.85rem;
        color: var(--ink-2);
        text-decoration: underline;
        text-underline-offset: 3px;
        text-decoration-color: var(--rule-2);
    }

    .rh-clear:hover {
        color: var(--ink);
        text-decoration-color: currentColor;
    }

    /* Selector segmentado (filtros de estado) */
    .rh-seg {
        display: inline-flex;
        padding: 3px;
        border-radius: 9px;
        background: var(--paper-3);
        gap: 2px;
    }

    .rh-seg a {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        height: 2.05rem;
        padding: 0 0.8rem;
        border-radius: 7px;
        font-size: 0.85rem;
        font-weight: 550;
        color: var(--ink-2);
        transition: background-color .15s, color .15s;
    }

    .rh-seg a:hover {
        color: var(--ink);
    }

    .rh-seg a[aria-current="true"] {
        background: var(--paper);
        color: var(--navy);
        box-shadow: 0 1px 2px rgba(12, 28, 60, 0.12);
    }

    .rh-seg .rh-count {
        font-size: 0.75rem;
        color: var(--ink-3);
        font-variant-numeric: tabular-nums;
    }

    /* ── Tablas ── */
    .rh-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-top: 1px solid var(--rule-2);
    }

    .rh-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .rh-table thead th {
        text-align: left;
        font-size: 0.8rem;
        font-weight: 600;
        font-stretch: 85%;
        color: var(--ink-2);
        padding: 0.7rem 0.75rem;
        border-bottom: 1px solid var(--rule-2);
        white-space: nowrap;
        background: var(--paper);
    }

    .rh-table tbody td,
    .rh-table tbody th {
        padding: 0.85rem 0.75rem;
        border-bottom: 1px solid var(--rule);
        vertical-align: top;
        text-align: left;
        font-weight: 400;
        background: var(--paper);
    }

    .rh-table tbody tr:hover > td,
    .rh-table tbody tr:hover > th {
        background: var(--paper-2);
    }

    .rh-table .is-right {
        text-align: right;
    }

    .rh-name {
        display: block;
        font-weight: 650;
        color: var(--ink);
        font-size: 0.925rem;
    }

    a.rh-name:hover {
        text-decoration: underline;
        text-underline-offset: 3px;
        text-decoration-color: var(--gold);
        text-decoration-thickness: 2px;
    }

    .rh-meta {
        display: block;
        margin-top: 0.15rem;
        font-size: 0.8rem;
        color: var(--ink-3);
    }

    .rh-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .rh-empty {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--ink-2);
    }

    .rh-empty p {
        margin: 0 0 0.75rem;
    }

    /* ── Saldo y barra ── */
    .rh-saldo {
        display: flex;
        align-items: baseline;
        gap: 0.3rem;
    }

    .rh-saldo-num {
        font-size: 1.3rem;
        line-height: 1.1;
        font-weight: 650;
        font-variant-numeric: tabular-nums;
        color: var(--ink);
    }

    .rh-saldo-num.is-neg {
        color: var(--crit-ink);
    }

    .rh-saldo-num.is-sm {
        font-size: 1rem;
        margin-left: 0.2rem;
    }

    .rh-saldo-unit {
        font-size: 0.8rem;
        color: var(--ink-3);
    }

    .rh-meter {
        display: flex;
        gap: 2px;
        height: 6px;
        margin: 0.4rem 0 0.35rem;
        border-radius: 3px;
        background: var(--pista);
        overflow: hidden;
        width: 100%;
        max-width: 11rem;
    }

    .rh-meter-seg {
        height: 100%;
        min-width: 3px;
    }

    .rh-meter-seg.is-acum {
        background: var(--serie-acum);
    }

    .rh-meter-seg.is-periodo {
        background: var(--serie-periodo);
    }

    .rh-saldo-note {
        font-size: 0.78rem;
        color: var(--ink-3);
    }

    /* ── Etiquetas de estado (ícono + texto) ── */
    .rh-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .rh-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.12rem 0.5rem 0.12rem 0.4rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 550;
        line-height: 1.45;
        white-space: nowrap;
        color: var(--ink);
        background: var(--paper-3);
    }

    .rh-chip svg {
        width: 13px;
        height: 13px;
        flex-shrink: 0;
    }

    .rh-chip.is-warn {
        background: var(--warn-wash);
        color: var(--warn-ink);
    }

    .rh-chip.is-serious {
        background: var(--serious-wash);
        color: var(--serious-ink);
    }

    .rh-chip.is-crit {
        background: var(--crit-wash);
        color: var(--crit-ink);
    }

    .rh-status {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.85rem;
        font-weight: 550;
    }

    .rh-status::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--good);
        flex-shrink: 0;
    }

    .rh-status.is-off {
        color: var(--ink-3);
    }

    .rh-status.is-off::before {
        background: var(--rule-2);
    }

    /* ── Leyenda ── */
    .rh-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.4rem 1.25rem;
        font-size: 0.78rem;
        color: var(--ink-2);
    }

    .rh-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .rh-key {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    .rh-key.is-bar {
        width: 16px;
        height: 6px;
    }

    .rh-scale {
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .rh-scale span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.1rem;
        height: 1.15rem;
        padding: 0 0.25rem;
        border-radius: 3px;
        font-size: 0.68rem;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }

    .h-1 {
        background: var(--heat-1);
        color: var(--navy);
    }

    .h-2 {
        background: var(--heat-2);
        color: #fff;
    }

    .h-3 {
        background: var(--heat-3);
        color: #fff;
    }

    .h-4 {
        background: var(--heat-4);
        color: #fff;
    }

    /* ── Tooltip ── */
    .rh-tip {
        position: fixed;
        z-index: 80;
        max-width: 18rem;
        padding: 0.5rem 0.65rem;
        border-radius: 7px;
        background: var(--navy);
        color: #fff;
        font-size: 0.8rem;
        line-height: 1.4;
        pointer-events: none;
        box-shadow: 0 8px 24px rgba(12, 28, 60, 0.25);
    }

    .rh-tip strong {
        display: block;
        font-weight: 650;
    }

    .rh-tip[hidden] {
        display: none;
    }

    /* ── Diálogo de confirmación ── */
    .rh-dialog {
        border: none;
        border-radius: 12px;
        padding: 0;
        width: min(28rem, calc(100vw - 2rem));
        color: var(--ink);
        box-shadow: 0 24px 60px rgba(12, 28, 60, 0.3);
    }

    .rh-dialog::backdrop {
        background: rgba(12, 28, 60, 0.45);
    }

    .rh-dialog-body {
        padding: 1.4rem 1.5rem 1.25rem;
    }

    .rh-dialog h2 {
        margin: 0 0 0.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        font-stretch: 85%;
        color: var(--navy);
    }

    .rh-dialog p {
        margin: 0 0 0.5rem;
        color: var(--ink-2);
        font-size: 0.9rem;
    }

    .rh-dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 0.9rem 1.5rem;
        background: var(--paper-2);
        border-top: 1px solid var(--rule);
    }

    .rh-foot {
        margin-top: 1.25rem;
        font-size: 0.8rem;
        color: var(--ink-3);
        max-width: 90ch;
        line-height: 1.6;
    }

    @media (max-width: 760px) {
        .rh-title,
        .rh-year-num {
            font-size: 1.85rem;
        }

        .rh-field.is-search {
            max-width: none;
            flex-basis: 100%;
        }

        .rh-field {
            flex: 1 1 100%;
            min-width: 0;
        }

        .rh .rh-select {
            width: 100%;
            max-width: none;
            min-width: 0;
        }

        .rh-filters-end {
            margin-left: 0;
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .rh *,
        .rh *::before,
        .rh *::after {
            transition: none !important;
        }
    }
</style>
