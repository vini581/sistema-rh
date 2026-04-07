<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Ponto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="text-center mb-8">
                    <p class="text-gray-500 text-sm">Data de hoje</p>
                    <p class="text-2xl font-bold">{{ now()->format('d/m/Y') }}</p>
                    <p class="text-4xl font-bold mt-1" id="clock">{{ now()->format('H:i:s') }}</p>
                </div>

                {{-- Status dos registros --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 rounded p-3 text-center">
                        <p class="text-xs text-gray-500">Entrada</p>
                        <p class="font-semibold">{{ $todayLog?->clock_in?->format('H:i') ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-3 text-center">
                        <p class="text-xs text-gray-500">Saída Almoço</p>
                        <p class="font-semibold">{{ $todayLog?->lunch_out?->format('H:i') ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-3 text-center">
                        <p class="text-xs text-gray-500">Volta Almoço</p>
                        <p class="font-semibold">{{ $todayLog?->lunch_in?->format('H:i') ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-3 text-center">
                        <p class="text-xs text-gray-500">Saída</p>
                        <p class="font-semibold">{{ $todayLog?->clock_out?->format('H:i') ?? '—' }}</p>
                    </div>
                </div>

                {{-- Botão de ponto --}}
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
                <div class="text-center">
<button id="punch-btn"
        style="background:#16a34a; color:white; padding:16px 32px; border-radius:8px; font-size:18px; font-weight:600; border:none; cursor:pointer;">
    {{ $label }}
</button>
                    <p id="punch-msg" class="mt-3 text-sm text-gray-500"></p>
                </div>
                @else
                <div class="text-center">
                    <p class="text-green-600 font-semibold text-lg">✓ Jornada finalizada hoje!</p>
                    <p class="text-gray-500 mt-1">Total: {{ $todayLog->formatted_hours }}</p>
                </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ route('work-log.history') }}"
                       class="text-blue-600 hover:underline text-sm">
                        Ver histórico completo
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Relógio em tempo real
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const el = document.getElementById('clock');
            if (el) el.textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);

        // AJAX para registrar ponto
        const btn = document.getElementById('punch-btn');
        if (btn) {
            btn.addEventListener('click', function () {
                btn.disabled = true;
                btn.textContent = 'Registrando...';

                fetch('{{ route('work-log.punch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('punch-msg').textContent =
                            'Registrado às ' + data.time;
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        document.getElementById('punch-msg').textContent = data.error;
                        btn.disabled = false;
                        btn.textContent = 'Tentar novamente';
                    }
                });
            });
        }
    </script>
</x-app-layout>