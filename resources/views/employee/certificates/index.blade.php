<x-app-layout>
    <x-slot name="header">Meus Atestados</x-slot>

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px;" class="fade-up">

        <div>
            <div class="card">
                <div class="card-header"><span class="card-title">Enviar Atestado</span></div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('employee.certificates.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="start_date" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="end_date" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-input" required>
                                <option value="medical">Médico</option>
                                <option value="dental">Odontológico</option>
                                <option value="attendance">Comparecimento</option>
                                <option value="work_accident">Acidente de Trabalho</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Arquivo (PDF ou Imagem)</label>
                            <input type="file" name="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <button type="submit" class="btn btn-primary w-full" style="margin-top:10px;">
                            Enviar para o RH
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header"><span class="card-title">Atestados Enviados</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th>Tipo</th>
                            <th>Dias</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $c)
                        <tr>
                            <td style="font-size:13px; font-weight:600;">
                                {{ $c->start_date->format('d/m') }} — {{ $c->end_date->format('d/m/Y') }}
                            </td>
                            <td><span style="font-size:12px;">{{ $c->type_label }}</span></td>
                            <td style="text-align:center; font-weight:700;">{{ $c->days }}</td>
                            <td>
                                @if($c->status === 'pending')
                                <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Análise</span>
                                @elseif($c->status === 'approved')
                                <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Aprovado</span>
                                @else
                                <span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Recusado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:48px; color:var(--text-muted);">Nenhum atestado enviado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($certificates->hasPages())
            <div style="padding:16px; border-top:1px solid var(--border);">{{ $certificates->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
