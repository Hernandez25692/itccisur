<style>
    .editorial-desk {
        --ed-ink: #10263c;
        --ed-navy: #102943;
        --ed-gold: #e9c779;
        --ed-muted: #506274;
        --ed-line: #e0e5e9;
        --ed-paper: #f6f7f5;
        max-width: 84rem;
        margin-inline: auto;
        color: var(--ed-ink);
        container-type: inline-size;
    }

    .editorial-desk :focus-visible {
        outline: 2px solid #94661b;
        outline-offset: 2px;
    }

    .editorial-masthead {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        border-radius: 14px 14px 5px 5px;
        background: #102943;
        color: #fff;
    }

    .editorial-cover-grid {
        position: absolute;
        z-index: -1;
        inset: 0 0 0 auto;
        width: 47%;
        background-image: linear-gradient(90deg, transparent, #e9c7791c),
            repeating-linear-gradient(90deg, #d0b16a32 0 1px, transparent 1px 48px),
            repeating-linear-gradient(0deg, #d0b16a32 0 1px, transparent 1px 48px);
        background-position: right top;
        mask-image: linear-gradient(90deg, transparent, #000 38%);
        pointer-events: none;
    }

    .editorial-cover-grid::before,
    .editorial-cover-grid::after {
        position: absolute;
        display: block;
        width: 48px;
        height: 48px;
        border: 1px solid #e9c77958;
        background: #e9c7791a;
        content: '';
    }

    .editorial-cover-grid::before { bottom: 95px; right: 48px; }
    .editorial-cover-grid::after { bottom: 47px; right: 144px; }

    .editorial-masthead-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem 2rem;
        padding: 1.9rem clamp(1rem, 3.4cqi, 2.5rem) 1.65rem;
    }

    .editorial-imprint {
        margin: 0 0 .55rem;
        color: var(--ed-gold);
        font-size: .69rem;
        font-weight: 700;
        letter-spacing: .15em;
        text-transform: uppercase;
    }

    .editorial-imprint span { margin-inline: .3rem; color: #aab9c8; }

    .editorial-masthead h1 {
        margin: 0;
        color: #fff;
        font: 400 clamp(2rem, 5.8cqi, 4.65rem)/1.05 Georgia, serif;
        letter-spacing: -.055em;
    }

    .editorial-masthead h1 em { color: var(--ed-gold); font-weight: inherit; }
    .editorial-deck { margin: .65rem 0 0; color: #cbd7e3; font-size: .875rem; }

    .editorial-create {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 2.75rem;
        padding: .6rem .95rem;
        border-radius: 5px;
        background: var(--ed-gold);
        color: #192638;
        font-size: .875rem;
        font-weight: 700;
    }

    .editorial-create:hover { background: #f5d797; }
    .editorial-masthead :focus-visible { outline-color: #fff; }

    .editorial-views {
        display: flex;
        gap: .2rem;
        padding-inline: clamp(.45rem, 2.5cqi, 1.75rem);
        border-top: 1px solid #ffffff24;
        background: #0c2238e8;
    }

    .editorial-views a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        min-height: 2.8rem;
        padding: .3rem .75rem;
        border-bottom: 2px solid transparent;
        color: #cee0ee;
        font-size: .82rem;
        font-weight: 600;
    }

    .editorial-views a:hover,
    .editorial-views a[aria-current] { color: #fff; background: #ffffff0e; }
    .editorial-views a[aria-current] { border-color: var(--ed-gold); }
    .editorial-views a[aria-current] i { color: var(--ed-gold); }

    .editorial-flash { margin-top: 1rem; }

    .editorial-register {
        --ed-inset: clamp(.8rem, 2.35cqi, 1.6rem);
        margin-top: 1rem;
        overflow: hidden;
        border: 1px solid var(--ed-line);
        border-radius: 6px 6px 12px 12px;
        background: #fff;
    }

    .editorial-desk [data-state] { --state-bg: #eef2f5; --state-ink: #455360; }
    .editorial-desk [data-state="publicado"] { --state-bg: #ebf8f2; --state-ink: #17583f; }
    .editorial-desk [data-state="pendiente"] { --state-bg: #fff3e8; --state-ink: #864313; }
    .editorial-desk [data-state="reprogramado"] { --state-bg: #fbf5dc; --state-ink: #685112; }
    .editorial-desk [data-state="cancelado"] { --state-bg: #fdf0ee; --state-ink: #922c2b; }

    .editorial-status-rail {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1px;
        border-bottom: 1px solid var(--ed-line);
        background: var(--ed-line);
    }

    .editorial-status-tab {
        display: grid;
        gap: .25rem;
        padding: .7rem var(--ed-inset) .65rem;
        border-top: 3px solid transparent;
        background: var(--ed-paper);
        color: var(--ed-ink);
    }

    .editorial-status-tab:hover { background: #fff; }
    .editorial-status-tab:focus-visible { outline-offset: -3px; }
    .editorial-status-tab[aria-current] { border-color: var(--state, #b38a39); background: #fff; }

    .editorial-status-label {
        display: flex;
        align-items: center;
        gap: .35rem;
        color: var(--ed-muted);
        font-size: .73rem;
        font-weight: 600;
    }

    .editorial-status-tab strong {
        font: 400 1.9rem/1 Georgia, serif;
        font-variant-numeric: lining-nums tabular-nums;
    }

    .editorial-dot { width: .46rem; height: .46rem; flex: none; border-radius: 50%; background: var(--state); }

    .editorial-overdue {
        display: flex;
        align-items: baseline;
        gap: .55rem;
        margin: .85rem var(--ed-inset) 0;
        padding: .6rem .7rem;
        border-left: 2px solid #c26534;
        background: #fff5eb;
        color: #753916;
        font-size: .8rem;
    }

    .editorial-register-heading {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        justify-content: space-between;
        gap: .25rem .85rem;
        padding: 1.05rem var(--ed-inset) .55rem;
    }

    .editorial-register-heading h2 {
        margin: 0;
        font: 400 clamp(1.4rem, 3.1cqi, 2rem)/1.2 Georgia, serif;
        letter-spacing: -.025em;
    }

    .editorial-register-heading > span { color: var(--ed-muted); font-size: .78rem; }

    .editorial-filters {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: start;
        gap: .5rem .75rem;
        padding: 0 var(--ed-inset) .8rem;
    }

    .editorial-search {
        display: flex;
        align-items: center;
        grid-row: 1;
        grid-column: 1;
        max-width: 38rem;
        min-height: 2.65rem;
        border: 1px solid #cdd6de;
        border-radius: 5px;
        color: var(--ed-muted);
    }

    .editorial-search:focus-within { border-color: #94661b; }
    .editorial-search > i { padding-left: .75rem; font-size: .85rem; }

    .editorial-search input {
        flex: 1;
        min-width: 0;
        padding: .55rem .6rem;
        border: 0;
        background: transparent;
        color: var(--ed-ink);
        font-size: .85rem;
    }

    .editorial-search input:focus { outline: none; box-shadow: none; }
    .editorial-search input::placeholder { color: var(--ed-muted); }

    .editorial-search button {
        align-self: stretch;
        padding-inline: .7rem;
        border-left: 1px solid #cdd6de;
        border-radius: 0 4px 4px 0;
        background: #f2f5f7;
        color: var(--ed-ink);
        font-size: .78rem;
        font-weight: 700;
    }

    .editorial-search button:hover { background: #e8eef2; }
    .editorial-filter-panel { grid-column: 1 / -1; grid-row: 2; }

    .editorial-filter-panel summary {
        display: flex;
        align-items: center;
        gap: .5rem;
        width: max-content;
        min-height: 2.2rem;
        padding: .35rem .6rem;
        border: 1px solid var(--ed-line);
        border-radius: 5px;
        color: var(--ed-ink);
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        list-style: none;
    }

    .editorial-filter-panel summary::-webkit-details-marker { display: none; }
    .editorial-filter-panel summary:hover,
    .editorial-filter-panel[open] summary { background: #f2f5f7; }
    .editorial-filter-panel summary .fa-chevron-down { color: var(--ed-muted); font-size: .63rem; }
    .editorial-filter-panel[open] summary .fa-chevron-down { transform: rotate(180deg); }

    .editorial-filter-count {
        min-width: 1.2rem;
        padding: .1rem .25rem;
        border-radius: 3px;
        background: #f5e7c7;
        color: #624517;
        text-align: center;
        font-size: .7rem;
        line-height: 1.2;
    }

    .editorial-clear {
        grid-row: 2;
        grid-column: 2;
        z-index: 1;
        align-self: start;
        padding: .5rem 0;
        color: var(--ed-muted);
        font-size: .8rem;
        text-decoration: underline;
        text-underline-offset: .16em;
    }

    .editorial-filter-fields {
        margin-top: .5rem;
        padding: .85rem;
        border: 1px solid var(--ed-line);
        border-radius: 6px;
        background: var(--ed-paper);
    }

    .editorial-filter-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .65rem; }
    .editorial-desk .filtro-label { color: var(--ed-muted); font-size: .74rem; }
    .editorial-desk .filtro-control {
        width: 100%;
        min-height: 2.4rem;
        border: 1px solid #cdd6de;
        border-radius: 5px;
        background-color: #fff;
        color: var(--ed-ink);
        font-size: .82rem;
    }

    .editorial-filter-actions { display: flex; justify-content: flex-end; margin-top: .75rem; }
    .editorial-apply {
        min-height: 2.4rem;
        padding: .35rem .8rem;
        border-radius: 5px;
        background: var(--ed-navy);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
    }
    .editorial-apply:hover { background: #254664; }

    .editorial-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        text-align: left;
        font-size: .85rem;
    }

    .editorial-table thead th {
        padding: .55rem .65rem;
        border-block: 1px solid var(--ed-line);
        background: #f7f9fa;
        color: var(--ed-muted);
        text-align: left;
        font-size: .67rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .editorial-table thead th:first-child { width: 6.7rem; padding-left: var(--ed-inset); }
    .editorial-table thead th:nth-child(3) { width: 9.1rem; }
    .editorial-table thead th:nth-child(4) { width: 8.45rem; }
    .editorial-table thead th:last-child { width: 8.65rem; }

    .editorial-month th {
        padding: .38rem var(--ed-inset);
        border-bottom: 1px solid #e9dfcb;
        background: #f8f4eb;
        color: #594728;
        text-align: left;
        font-size: .75rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .editorial-month i { margin-right: .35rem; color: #84672f; }
    .editorial-month span { margin-left: .12rem; font-size: .72rem; font-weight: 500; }

    .editorial-entry { border-bottom: 1px solid var(--ed-line); }
    .editorial-entry:last-child { border-bottom: 0; }
    .editorial-entry:hover { background: #fbfcfd; }
    .editorial-entry td { padding: .64rem .65rem; vertical-align: middle; }
    .editorial-entry .editorial-date { padding-left: var(--ed-inset); }

    .editorial-date time { display: flex; align-items: baseline; gap: .3rem; }
    .editorial-date time strong {
        font: 400 1.6rem/1 Georgia, serif;
        font-variant-numeric: lining-nums tabular-nums;
    }
    .editorial-date time span { color: var(--ed-muted); font-size: .7rem; text-transform: capitalize; }
    .editorial-date-note { display: block; margin-top: .15rem; color: var(--ed-muted); font-size: .68rem; font-variant-numeric: tabular-nums; }
    .editorial-desk abbr { text-decoration: none; }

    .editorial-topic > a { color: var(--ed-ink); font-size: .93rem; font-weight: 700; overflow-wrap: anywhere; }
    .editorial-topic > a:hover { color: #355777; text-decoration: underline; text-underline-offset: .15em; }
    .editorial-topic-meta { display: flex; flex-wrap: wrap; gap: .08rem .5rem; margin-top: .16rem; color: var(--ed-muted); font-size: .74rem; overflow-wrap: anywhere; }
    .editorial-formats { color: #6f5428; }

    .editorial-channel-list { display: flex; flex-wrap: wrap; gap: .2rem; }
    .editorial-channel {
        --channel: #5e6374;
        display: inline-grid;
        place-items: center;
        min-width: 1.34rem;
        min-height: 1.38rem;
        padding-inline: .15rem;
        border: 1px solid #d7dfe5;
        border-radius: 4px;
        background: #fff;
        color: var(--channel);
        font-size: .63rem;
        font-weight: 800;
        line-height: 1;
    }

    .editorial-no-channels { color: var(--ed-muted); }
    .editorial-state-label {
        display: inline-flex;
        align-items: center;
        gap: .34rem;
        padding: .22rem .47rem;
        border-radius: 4px;
        background: var(--state-bg);
        color: var(--state-ink);
        font-size: .72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .editorial-entry .editorial-entry-actions { padding-right: var(--ed-inset); }
    .editorial-entry-actions > div { display: flex; justify-content: flex-end; gap: .2rem; }
    .editorial-icon-action {
        display: inline-grid;
        place-items: center;
        width: 2rem;
        height: 2rem;
        border: 1px solid var(--ed-line);
        border-radius: 5px;
        background: #fff;
        color: #39556d;
        font-size: .77rem;
    }

    .editorial-icon-action:hover { border-color: #b4c4d2; background: #ecf1f5; }
    .editorial-publish { border-color: #c5dfd2; background: #f0f8f4; color: #226646; }
    .editorial-publish:hover { border-color: #9ec6b0; background: #e1f1e8; }

    .editorial-empty {
        padding: 2.75rem 1rem;
        border-top: 1px solid var(--ed-line);
        background: #f9faf9;
        text-align: center;
    }

    .editorial-empty i { color: #84672f; font-size: 1.45rem; }
    .editorial-empty p { margin: .55rem 0; font: 400 1.2rem/1.3 Georgia, serif; }
    .editorial-empty a { color: #355777; font-size: .82rem; font-weight: 700; text-decoration: underline; text-underline-offset: .15em; }
    .editorial-pagination { padding: .85rem var(--ed-inset); border-top: 1px solid var(--ed-line); }

    @container (min-width: 700px) {
        .editorial-filters { --ed-search-width: min(calc(100% - 13.5rem), 38rem); }
        .editorial-search { grid-column: 1 / -1; width: var(--ed-search-width); z-index: 1; }
        .editorial-filter-panel { grid-row: 1; }
        .editorial-filter-panel summary { margin-inline-start: calc(var(--ed-search-width) + .65rem); min-height: 2.65rem; }
        .editorial-clear { grid-row: 1; padding-block: .7rem; }
    }

    @container (max-width: 699px) {
        .editorial-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .editorial-table,
        .editorial-table tbody { display: block; }
        .editorial-table thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip-path: inset(50%); }
        .editorial-month { display: block; }
        .editorial-month th { display: block; }
        .editorial-entry {
            display: grid;
            grid-template-columns: 3.6rem minmax(0, 1fr) auto;
            grid-template-areas: 'date topic topic' 'date state state' 'date channels actions';
            align-items: center;
            gap: .35rem .65rem;
            padding: .65rem var(--ed-inset);
        }
        .editorial-entry td,
        .editorial-entry .editorial-date,
        .editorial-entry .editorial-entry-actions { padding: 0; }
        .editorial-entry .editorial-date { grid-area: date; align-self: start; padding-top: .15rem; }
        .editorial-date time { flex-wrap: wrap; gap: .12rem .25rem; }
        .editorial-topic { grid-area: topic; }
        .editorial-channels { grid-area: channels; }
        .editorial-entry-state { grid-area: state; }
        .editorial-entry-actions { grid-area: actions; }
        .editorial-icon-action { width: 2.2rem; height: 2.2rem; }
    }

    @container (max-width: 495px) {
        .editorial-status-rail { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .editorial-status-tab { grid-template-columns: minmax(0, 1fr) auto; align-items: center; padding-block: .48rem; }
        .editorial-status-total { grid-column: 1 / -1; }
        .editorial-status-tab strong { font-size: 1.35rem; }
        .editorial-masthead-main { padding-block: 1.3rem 1rem; }
        .editorial-views a { flex: 1; padding-inline: .15rem; font-size: .74rem; }
        .editorial-cover-grid { width: 63%; opacity: .55; }
        .editorial-register-heading > span { font-size: .72rem; }
        .editorial-search { grid-column: 1 / -1; }
    }

    @container (max-width: 300px) {
        .editorial-entry {
            grid-template-columns: 3.25rem minmax(0, 1fr);
            grid-template-areas: 'date topic' 'date state' 'date channels' 'date actions';
        }
        .editorial-entry-actions > div { justify-content: flex-start; }
        .editorial-search input { padding-inline: .4rem; }
        .editorial-views i { display: none; }
    }
</style>
