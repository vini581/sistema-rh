<x-app-layout>
    <x-slot name="header">Registro de Ponto</x-slot>

    <div style="max-width:560px; margin:0 auto;">
        <div class="card fade-up">
            <div class="card-body" style="text-align:center; padding:36px;">

                <div style="margin-bottom:24px;">
                    <img src="{{ Auth::user()->avatar_url }}" style="width:80px; height:80px; border-radius:20px; object-fit:cover; border:2px solid var(--primary); padding:2px; margin:0 auto;" alt="{{ Auth::user()->name }}">
                    <div style="margin-top:12px; font-weight:700; font-size:18px;">{{ Auth::user()->name }}</div>
                </div>

                {{-- Data e hora --}}
                <div style="margin-bottom:32px;">
                    <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">{{ now()->format('l, d \d\e F \d\e Y') }}</div>
                    <div id="clock" style="font-size:52px; font-weight:700; font-family:'JetBrains Mono',monospace; color:var(--primary); letter-spacing:-2px; line-height:1;"></div>
                </div>

                {{-- Status dos 4 pontos --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:32px;">
                    @php
                        $pontos = [
                            ['label' => 'Entrada',      'time' => $todayLog?->clock_in,  'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1', 'color' => '#10b981'],
                            ['label' => 'Saída Almoço', 'time' => $todayLog?->lunch_out, 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z', 'color' => '#f59e0b'],
                            ['label' => 'Volta Almoço', 'time' => $todayLog?->lunch_in,  'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1', 'color' => '#3b82f6'],
                            ['label' => 'Saída Final',  'time' => $todayLog?->clock_out, 'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1', 'color' => '#8b5cf6'],
                        ];
                    @endphp

                    @foreach($pontos as $ponto)
                    <div style="background:var(--surface2); border:1px solid {{ $ponto['time'] ? $ponto['color'] : 'var(--border)' }}; border-radius:12px; padding:14px; transition:border .3s;">
                        <div style="font-size:11px; font-weight:600; color:{{ $ponto['time'] ? $ponto['color'] : 'var(--text-muted)' }}; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px;">
                            {{ $ponto['label'] }}
                        </div>
                        <div style="font-family:'JetBrains Mono',monospace; font-size:22px; font-weight:700; color:{{ $ponto['time'] ? 'var(--text)' : 'var(--border)' }};">
                            {{ $ponto['time'] ? $ponto['time']->format('H:i') : '--:--' }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Botão ou finalizado --}}
                @php
                    $nextAction = $todayLog?->getNextAction() ?? 'clock_in';
                    $labels = [
                        'clock_in'  => 'Registrar Entrada',
                        'lunch_out' => 'Registrar Saída para Almoço',
                        'lunch_in'  => 'Registrar Volta do Almoço',
                        'clock_out' => 'Registrar Saída Final',
                    ];
                    $label = $labels[$nextAction] ?? null;
                @endphp

                @if($label)
                <button id="punch-btn" class="btn btn-primary" style="width:100%; justify-content:center; padding:16px; font-size:16px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $label }}
                </button>
                <p id="punch-msg" style="margin-top:12px; font-size:13px; color:var(--text-muted); min-height:20px;"></p>
                @else
                <div style="background:#d1fae5; border:1px solid #a7f3d0; border-radius:12px; padding:18px; text-align:center;">
                    <svg fill="none" stroke="#10b981" viewBox="0 0 24 24" style="width:32px;height:32px;margin:0 auto 8px;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div style="font-weight:700; color:#065f46; font-size:16px;">Jornada finalizada!</div>
                    <div style="color:#065f46; font-size:13px; margin-top:4px;">Total trabalhado: <strong>{{ $todayLog->formatted_hours }}</strong></div>
                </div>
                @endif

                <div style="margin-top:20px;">
                    <a href="{{ route('work-log.history') }}" style="font-size:13px; color:var(--primary); text-decoration:none; font-weight:500;">
                        Ver histórico completo →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        const el = document.getElementById('clock');
        if (el) el.textContent = `${h}:${m}:${s}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    const btn = document.getElementById('punch-btn');
    if (btn) {
        const originalLabel = btn.innerHTML;
        btn.addEventListener('click', function() {
            btn.disabled = true;
            btn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Registrando...';

            fetch('{{ route('work-log.punch') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({})
            })
            .then(res => {
                if (!res.ok && !res.headers.get('content-type')?.includes('application/json')) throw new Error();
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('punch-msg').textContent = '✓ Registrado às ' + data.time;
                    document.getElementById('punch-msg').style.color = 'var(--success)';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    document.getElementById('punch-msg').textContent = data.error;
                    document.getElementById('punch-msg').style.color = 'var(--danger)';
                    btn.disabled = false;
                    btn.innerHTML = originalLabel;
                }
            })
            .catch(() => {
                document.getElementById('punch-msg').textContent = 'Erro de conexão. Tente novamente.';
                document.getElementById('punch-msg').style.color = 'var(--danger)';
                btn.disabled = false;
                btn.innerHTML = originalLabel;
            });
        });
    }
    </script>
    <style>
    @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</x-app-layout>