<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MedicalCertificate;
use App\Models\HrConfig;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MedicalCertificateController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $certificates = MedicalCertificate::with('employee.user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('medical-certificates.index', compact('certificates', 'status'));
    }

    public function create()
    {
        $employees = Employee::with('user')->orderBy('created_at', 'desc')->get();
        return view('medical-certificates.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'type'         => 'required|in:medical,dental,attendance,work_accident',
            'observations' => 'nullable|string',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);
        $days  = $start->diffInDays($end) + 1;

        // Evitar duplicidade de atestados para o mesmo período
        $isDuplicate = MedicalCertificate::where('employee_id', $validated['employee_id'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })->exists();

        if ($isDuplicate) {
            return redirect()->route('certificates.create')
                ->with('error', 'Já existe um atestado registrado para este funcionário nesse período.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('certificates', 'public');
        }

        $config = HrConfig::forDate($validated['start_date']);

        MedicalCertificate::create([
            'employee_id'  => $validated['employee_id'],
            'start_date'   => $validated['start_date'],
            'end_date'     => $validated['end_date'],
            'days'         => $days,
            'type'         => $validated['type'],
            'file_path'    => $filePath,
            'observations' => $validated['observations'] ?? null,
            'status'       => 'pending',
            'excused'      => (bool) ($config->certificate_excuses_absence ?? true),
            'deducted'     => !(bool) ($config->certificate_excuses_absence ?? true),
        ]);

        return redirect()->route('certificates.index')
            ->with('success', 'Tudo certo. O atestado já está registrado no sistema.');
    }

    public function approve(MedicalCertificate $certificate)
    {
        $config = HrConfig::forDate($certificate->start_date, $certificate->employee_id);

        $excused  = (bool) ($config->certificate_excuses_absence ?? true);
        $counted  = (bool) ($config->certificate_counts_as_worked ?? true);
        $maxDays  = (int) ($config->certificate_company_paid_days ?? 15);

        // Se ultrapassar o limite de dias pagos pela empresa, marca como não abonado
        if ($maxDays > 0 && $certificate->days > $maxDays) {
            $excused = false;
        }

        $certificate->update([
            'status'   => 'approved',
            'excused'  => $excused,
            'deducted' => !$excused,
        ]);

        return redirect()->route('certificates.index')
            ->with('success', 'Pronto! Atestado validado e aprovado.');
    }

    public function reject(MedicalCertificate $certificate)
    {
        $certificate->update([
            'status'   => 'rejected',
            'excused'  => false,
            'deducted' => true,
        ]);

        return redirect()->route('certificates.index')
            ->with('success', 'Atestado recusado e status atualizado.');
    }

    public function destroy(MedicalCertificate $certificate)
    {
        if ($certificate->file_path) {
            Storage::disk('public')->delete($certificate->file_path);
        }
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'O atestado foi apagado.');
    }
}
