<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\HrConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado.');
        }

        $certificates = MedicalCertificate::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.certificates.index', compact('certificates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'type'         => 'required|in:medical,dental,attendance,work_accident',
            'observations' => 'nullable|string|max:1000',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado.');
        }

        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);
        $days  = $start->diffInDays($end) + 1;

        // Evitar duplicidade de atestados para o mesmo período
        $isDuplicate = MedicalCertificate::where('employee_id', $employee->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })->exists();

        if ($isDuplicate) {
            return redirect()->route('employee.certificates.index')
                ->with('error', 'Já existe um atestado registrado (ou em análise) para este período.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('certificates', 'public');
        }

        $config = HrConfig::forDate($validated['start_date']);

        MedicalCertificate::create([
            'employee_id'  => $employee->id,
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

        return redirect()->route('employee.certificates.index')
            ->with('success', 'Atestado enviado! Agora é só aguardar a análise do RH.');
    }
}
