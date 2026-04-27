<x-app-layout>
    <x-slot name="header">Minhas Férias</x-slot>

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px;" class="fade-up">

        <div>
            <div class="card">
                <div class="card-header"><span class="card-title">Solicitar Férias</span></div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('employee.vacations.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Data de Início</label>
                            <input type="date" name="start_date" class="form-input" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quantidade de Dias</label>
                            <select name="days" class="form-input" required>
                                <option value="15">15 dias</option>
                                <option value="20">20 dias</option>
                                <option value="30">30 dias</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full" style="margin-top:10px;">
                            Enviar Solicitação
                        </button>
                    </form>
                </div>
            </div>
            

            <div class="card" style="margin-top:20px; background:var(--primary-lt); border:1px solid var(--primary);">
                <div class="card-body" style="padding:20px;">
                    <div style="font-size:13px; color:var(--primary); font-weight:700; margin-bottom:5px;">Dica de RH</div>
                    <div style="font-size:12px; color:var(--text-main); line-height:1.5;">
                        As solicitações devem ser feitas com no mínimo 30 dias de antecedência para aprovação.
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header"><span class="card-title">Histórico de Pedidos</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Data Início</th>
                            <th>Dias</th>
                            <th>Previsão Bruta</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                        <tr>
                            <td style="font-weight:600;">{{ $r->start_date->format('d/m/Y') }}</td>
                            <td>{{ $r->days }} dias</td>
                            <td style="font-family:'JetBrains Mono',monospace;">R$ {{ number_format($r->forecast_value / 100, 2, ',', '.') }}</td>
                            <td>
                                @if($r->status === 'pending')
                                <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Pendente</span>
                                @elseif($r->status === 'approved')
                                <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Aprovada</span>
                                @else
                                <span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Recusada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:48px; color:var(--text-muted);">Você ainda não fez solicitações de férias.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
