@if (session('success'))
    <div class="vac-alert is-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="vac-alert is-error">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="vac-alert is-error">
        <div class="vac-alert-title">Revise la solicitud</div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
