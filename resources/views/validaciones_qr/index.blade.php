<x-app-layout>
    <div class="vqr-page">

        {{-- Header --}}
        <div class="vqr-header">
            <div>
                <h2 class="vqr-title">Validaciones QR</h2>
                <p class="vqr-sub">Gestión de códigos QR por formación</p>
            </div>
            <a href="{{ route('validaciones_qr.create') }}" class="vqr-btn-new">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="8" y1="2" x2="8" y2="14" />
                    <line x1="2" y1="8" x2="14" y2="8" />
                </svg>
                Nueva validación
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="vqr-alert">✓ {{ session('success') }}</div>
        @endif

        {{-- Tabla --}}
        <div class="vqr-card">
            <table class="vqr-table">
                <thead>
                    <tr>
                        <th>Formación</th>
                        <th>Fecha</th>
                        <th>Año</th>
                        <th>Token</th>
                        <th>QR</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($validaciones as $v)
                        <tr>
                            <td class="fw-500">{{ $v->nombre_formacion }}</td>
                            <td class="muted">{{ $v->fecha_formacion }}</td>
                            <td><span class="vqr-year">{{ $v->anio }}</span></td>
                            <td><span class="vqr-token">{{ $v->token }}</span></td>
                            <td>
                                <div class="vqr-qr-wrap">
                                    <img id="qr-{{ $v->token }}" src="data:image/png;base64,{{ $v->qr_base64 }}"
                                        width="72" height="72" class="vqr-qr-img">
                                    <button onclick="copiarImagen('qr-{{ $v->token }}')" class="vqr-btn-copy">
                                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none"
                                            stroke="currentColor" stroke-width="1.5">
                                            <rect x="5" y="5" width="9" height="9" rx="1" />
                                            <path d="M3 11H2a1 1 0 01-1-1V2a1 1 0 011-1h8a1 1 0 011 1v1" />
                                        </svg>
                                        Copiar QR
                                    </button>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('validaciones_qr.destroy', $v->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="vqr-btn-del" onclick="return confirm('¿Eliminar esta validación?')">
                                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none"
                                            stroke="currentColor" stroke-width="1.5">
                                            <polyline points="2,4 14,4" />
                                            <path d="M5 4V2h6v2" />
                                            <path d="M3 4l1 10h8l1-10" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="vqr-pag">{{ $validaciones->links() }}</div>
        </div>
    </div>

    <style>
        .vqr-page {
            padding: 28px 0;
        }

        .vqr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .vqr-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0B1F3A;
            margin: 0;
        }

        .vqr-sub {
            font-size: 13px;
            color: #6b7a8d;
            margin: 2px 0 0;
        }

        .vqr-btn-new {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #0B1F3A;
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: .2s;
        }

        .vqr-btn-new:hover {
            background: #1a3560;
            color: #fff;
        }

        .vqr-alert {
            background: #EAF3DE;
            color: #27500A;
            border: 0.5px solid #97C459;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .vqr-card {
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, .1);
            border-radius: 12px;
            overflow: hidden;
        }

        .vqr-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .vqr-table thead tr {
            border-bottom: 0.5px solid rgba(0, 0, 0, .08);
        }

        .vqr-table th {
            padding: 11px 14px;
            font-size: 11px;
            font-weight: 500;
            color: #6b7a8d;
            text-transform: uppercase;
            letter-spacing: .07em;
            text-align: left;
            background: #f8f9fb;
        }

        .vqr-table td {
            padding: 12px 14px;
            font-size: 13px;
            color: #1a2a3a;
            border-bottom: 0.5px solid rgba(0, 0, 0, .06);
            vertical-align: middle;
        }

        .vqr-table tbody tr:last-child td {
            border-bottom: none;
        }

        .vqr-table tbody tr:hover td {
            background: #f8f9fb;
        }

        .fw-500 {
            font-weight: 500;
        }

        .muted {
            color: #6b7a8d;
        }

        .vqr-token {
            font-family: monospace;
            font-size: 11px;
            color: #6b7a8d;
            background: #f4f6fa;
            padding: 3px 7px;
            border-radius: 6px;
            display: inline-block;
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .vqr-year {
            background: #E6F1FB;
            color: #185FA5;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 9px;
            border-radius: 50px;
        }

        .vqr-qr-wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .vqr-qr-img {
            border-radius: 8px;
            border: 0.5px solid rgba(0, 0, 0, .08);
            display: block;
        }

        .vqr-btn-copy {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 500;
            background: #f4f6fa;
            color: #1a2a3a;
            border: 0.5px solid rgba(0, 0, 0, .15);
            border-radius: 6px;
            cursor: pointer;
            transition: .18s;
        }

        .vqr-btn-copy:hover {
            background: #faeeda;
            color: #633806;
            border-color: #EF9F27;
        }

        .vqr-btn-del {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 500;
            background: #FCEBEB;
            color: #A32D2D;
            border: 0.5px solid #F09595;
            border-radius: 6px;
            cursor: pointer;
            transition: .18s;
        }

        .vqr-btn-del:hover {
            background: #F09595;
            color: #501313;
        }

        .vqr-pag {
            padding: 12px 16px;
            border-top: 0.5px solid rgba(0, 0, 0, .06);
        }
    </style>

    <script>
        async function copiarImagen(id) {
            try {
                const img = document.getElementById(id);
                const blob = await (await fetch(img.src)).blob();
                await navigator.clipboard.write([new ClipboardItem({
                    "image/png": blob
                })]);
                alert("QR copiado ✅");
            } catch (err) {
                alert("No se pudo copiar automáticamente");
            }
        }
    </script>
</x-app-layout>
