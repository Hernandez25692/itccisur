<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --border-light: #e2e8f0;
        --gold: #C5A049;
        --gold-light: #d4b56a;
        --gold-dark: #a8862e;
        --gold-dim: rgba(197, 160, 73, 0.1);
        --gold-border: rgba(197, 160, 73, 0.3);
        --navy: #0f172a;
        --white: #ffffff;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
        --success: #059669;
        --success-light: #d1fae5;
        --info: #2563eb;
        --info-light: #dbeafe;
        --warning: #b45309;
        --warning-light: #fef3c7;
        --error: #dc2626;
        --error-light: #fee2e2;
    }

    .vac-page {
        min-height: 100vh;
        background: var(--bg-primary);
        font-family: 'DM Sans', sans-serif;
        padding: 2rem 1rem 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--gray-800);
    }

    .vac-container {
        width: 100%;
        max-width: 860px;
    }

    .vac-container.is-wide {
        max-width: 1200px;
    }

    /* ── Breadcrumb ── */
    .vac-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.78rem;
        color: var(--gray-500);
        letter-spacing: 0.04em;
    }

    .vac-breadcrumb a {
        color: var(--gray-500);
        text-decoration: none;
        transition: color .2s;
    }

    .vac-breadcrumb a:hover {
        color: var(--gold);
    }

    .vac-breadcrumb span {
        color: var(--gray-700);
        font-weight: 500;
    }

    /* ── Card ── */
    .vac-card {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .vac-header {
        padding: 1.75rem 2.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(135deg, rgba(197, 160, 73, 0.05) 0%, transparent 60%);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .vac-header-main {
        flex: 1;
        min-width: 220px;
    }

    .vac-header-icon {
        width: 48px;
        height: 48px;
        background: var(--gold-dim);
        border: 1px solid var(--gold-border);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .vac-header-icon svg {
        width: 22px;
        height: 22px;
        color: var(--gold);
    }

    .vac-header-title {
        font-family: 'Libre Baskerville', serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.2;
        margin: 0;
    }

    .vac-header-sub {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin: 0.25rem 0 0;
    }

    .vac-employee-name {
        color: var(--gold-dark);
        font-weight: 600;
    }

    .vac-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .vac-body {
        padding: 1.75rem 2.25rem 2.25rem;
    }

    .vac-body.is-flush {
        padding: 0;
    }

    /* ── Section ── */
    .vac-section {
        margin-bottom: 1.75rem;
    }

    .vac-section-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--gold-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .vac-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--gold-border);
    }

    .vac-card-title {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .vac-card-title h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .vac-card-title p {
        margin: 0;
        font-size: 0.78rem;
        color: var(--gray-500);
    }

    /* ── Grid ── */
    .vac-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .vac-grid-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    /* ── Field ── */
    .vac-field {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .vac-field.span-2 {
        grid-column: span 2;
    }

    .vac-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .vac-label .req {
        color: var(--gold);
        font-size: 0.85em;
    }

    .vac-input,
    .vac-select,
    .vac-textarea {
        width: 100%;
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 0.6rem;
        padding: 0.65rem 0.9rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        color: var(--gray-800);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        box-sizing: border-box;
    }

    .vac-select {
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%2364748b' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.7rem center;
        background-size: 1.2rem;
        padding-right: 2.5rem;
    }

    .vac-textarea {
        min-height: 80px;
        resize: vertical;
    }

    .vac-input::placeholder,
    .vac-textarea::placeholder {
        color: var(--gray-400);
    }

    .vac-input:hover,
    .vac-select:hover,
    .vac-textarea:hover {
        border-color: var(--gray-300);
    }

    .vac-input:focus,
    .vac-select:focus,
    .vac-textarea:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.12);
    }

    .vac-input.is-invalid,
    .vac-select.is-invalid,
    .vac-textarea.is-invalid {
        border-color: var(--error);
    }

    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(0.4);
        cursor: pointer;
        opacity: 0.7;
    }

    .vac-hint {
        font-size: 0.73rem;
        color: var(--gray-500);
        line-height: 1.4;
        margin: 0.15rem 0 0;
    }

    .vac-error {
        font-size: 0.75rem;
        color: var(--error);
        font-weight: 500;
    }

    .vac-check {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: var(--gray-700);
        cursor: pointer;
    }

    .vac-check input {
        margin-top: 0.2rem;
        width: 1rem;
        height: 1rem;
        accent-color: var(--gold);
        flex-shrink: 0;
    }

    /* ── Alerts ── */
    .vac-alert {
        background: var(--info-light);
        border: 1px solid rgba(37, 99, 235, 0.2);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        font-size: 0.86rem;
        color: var(--gray-700);
        line-height: 1.5;
    }

    .vac-alert.is-success {
        background: var(--success-light);
        border-color: rgba(5, 150, 105, 0.25);
        color: #065f46;
    }

    .vac-alert.is-warning {
        background: var(--warning-light);
        border-color: rgba(180, 83, 9, 0.25);
        color: #78350f;
    }

    .vac-alert.is-error {
        background: var(--error-light);
        border-color: rgba(220, 38, 38, 0.25);
        color: #7f1d1d;
    }

    .vac-alert-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .vac-alert ul {
        margin: 0.25rem 0 0 1.1rem;
        padding: 0;
        list-style: disc;
    }

    .vac-summary {
        background: white;
        padding: 0.85rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(37, 99, 235, 0.12);
        font-size: 0.95rem;
    }

    .vac-summary strong {
        color: var(--info);
    }

    /* ── Stats ── */
    .vac-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 1rem;
    }

    .vac-stat {
        background: var(--gray-50);
        border: 1px solid var(--border-light);
        border-radius: 0.85rem;
        padding: 0.9rem 1rem;
    }

    .vac-stat-label {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--gray-500);
    }

    .vac-stat-value {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-top: 0.2rem;
        font-variant-numeric: tabular-nums;
    }

    .vac-stat-sub {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.15rem;
    }

    .vac-stat.is-main {
        background: var(--navy);
        border-color: var(--navy);
    }

    .vac-stat.is-main .vac-stat-label {
        color: var(--gold-light);
    }

    .vac-stat.is-main .vac-stat-value,
    .vac-stat.is-main .vac-stat-sub {
        color: white;
    }

    .vac-stat.is-warning {
        background: var(--warning-light);
        border-color: rgba(180, 83, 9, 0.25);
    }

    .vac-stat.is-negative .vac-stat-value {
        color: var(--error);
    }

    .vac-stat.is-main.is-negative .vac-stat-value {
        color: #fca5a5;
    }

    /* ── Tables ── */
    .vac-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .vac-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.84rem;
    }

    .vac-table thead th {
        background: var(--gray-50);
        color: var(--gray-600);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: left;
        padding: 0.7rem 0.9rem;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .vac-table tbody td {
        padding: 0.7rem 0.9rem;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: top;
        color: var(--gray-800);
    }

    .vac-table tbody tr:hover td {
        background: rgba(197, 160, 73, 0.04);
    }

    .vac-table .num {
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .vac-table .center {
        text-align: center;
    }

    .vac-table .nowrap {
        white-space: nowrap;
    }

    .vac-table tr.is-muted td {
        color: var(--gray-400);
    }

    .vac-table tr.is-muted td .vac-desc {
        text-decoration: line-through;
    }

    .vac-table tr.is-programado td {
        background: rgba(37, 99, 235, 0.03);
    }

    .vac-table tr.is-actual td {
        background: rgba(197, 160, 73, 0.08);
    }

    .vac-pos {
        color: var(--success);
        font-weight: 600;
    }

    .vac-neg {
        color: var(--error);
        font-weight: 600;
    }

    .vac-muted {
        color: var(--gray-400);
    }

    .vac-small {
        font-size: 0.74rem;
        color: var(--gray-500);
    }

    .vac-empty {
        padding: 1.5rem;
        text-align: center;
        color: var(--gray-500);
        font-size: 0.85rem;
    }

    /* ── Badges ── */
    .vac-badge {
        display: inline-block;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        white-space: nowrap;
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .vac-badge.is-success {
        background: var(--success-light);
        color: var(--success);
    }

    .vac-badge.is-info {
        background: var(--info-light);
        color: var(--info);
    }

    .vac-badge.is-warning {
        background: var(--warning-light);
        color: var(--warning);
    }

    .vac-badge.is-error {
        background: var(--error-light);
        color: var(--error);
    }

    /* ── Buttons ── */
    .vac-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
        margin-top: 0.5rem;
    }

    .vac-btn-cancel,
    .vac-btn-save,
    .vac-btn-sm {
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .vac-btn-cancel {
        padding: 0.65rem 1.4rem;
        border-radius: 0.6rem;
        border: 1px solid var(--gray-200);
        background: white;
        color: var(--gray-600);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .vac-btn-cancel:hover {
        border-color: var(--gray-300);
        color: var(--gray-800);
        background: var(--gray-50);
    }

    .vac-btn-save {
        padding: 0.65rem 1.6rem;
        border-radius: 0.6rem;
        border: none;
        background: linear-gradient(135deg, #d4a843 0%, var(--gold) 60%, var(--gold-dark) 100%);
        color: white;
        font-size: 0.86rem;
        font-weight: 700;
        box-shadow: 0 4px 6px -1px rgba(197, 160, 73, 0.2);
    }

    .vac-btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 10px -1px rgba(197, 160, 73, 0.3);
        filter: brightness(1.05);
    }

    .vac-btn-save svg,
    .vac-btn-cancel svg {
        width: 16px;
        height: 16px;
    }

    .vac-btn-sm {
        padding: 0.3rem 0.7rem;
        border-radius: 0.45rem;
        border: 1px solid var(--gray-200);
        background: white;
        color: var(--gray-700);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .vac-btn-sm:hover {
        border-color: var(--gold);
        color: var(--gold-dark);
    }

    .vac-btn-sm.is-danger:hover {
        border-color: var(--error);
        color: var(--error);
    }

    .vac-actions {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ── Manual adjustment box ── */
    .vac-adjustment {
        background: var(--gray-50);
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin: 1.25rem 0;
        border: 1px solid var(--border-light);
    }

    /* ── Modal ── */
    .vac-modal {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.45);
        padding: 1rem;
    }

    .vac-modal.is-open {
        display: flex;
    }

    .vac-modal-box {
        width: 100%;
        max-width: 460px;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        overflow: hidden;
    }

    .vac-modal-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        font-weight: 700;
        color: var(--gray-900);
    }

    .vac-modal-body {
        padding: 1.25rem;
    }

    /* ── Responsive ── */
    @media (max-width: 700px) {
        .vac-header {
            padding: 1.4rem 1.25rem;
        }

        .vac-body {
            padding: 1.4rem 1.25rem 1.75rem;
        }

        .vac-grid-2 {
            grid-template-columns: 1fr;
        }

        .vac-field.span-2 {
            grid-column: span 1;
        }

        .vac-footer {
            flex-direction: column-reverse;
        }

        .vac-btn-cancel,
        .vac-btn-save {
            width: 100%;
        }
    }
</style>
