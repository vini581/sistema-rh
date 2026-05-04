<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollCalculator;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        $payrolls = Payroll::with('employee.user')
            ->where('reference_month', 'like', "$month%")
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::with('user')->get();

        return view('payroll.index', compact('payrolls', 'employees', 'month'));
    }

    public function generate(Request $request, PayrollCalculator $calculator)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $year  = (int) explode('-', $month)[0];
        $m     = (int) explode('-', $month)[1];
        $type  = $request->input('type', 'monthly'); // advance or monthly
        
        $employees = Employee::all();
        $count = 0;

        // Limpa caches estáticos uma vez antes do lote (eficiência máxima)
        \App\Services\CalendarService::clearCache();
        \App\Models\HrConfig::clearCache();

        foreach ($employees as $employee) {
            $calculator->calculate($employee, $year, $m, true, $type);
            $count++;
        }

        $msg = $type === 'advance' ? "Adiantamento gerado para {$count} funcionários." : "Folha mensal calculada para {$count} funcionários.";

        return redirect()->route('payroll.index', ['month' => $month])
            ->with('success', $msg);
    }

    public function calculate(Request $request, PayrollCalculator $calculator)
    {
        return $this->generate($request, $calculator);
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.user');
        return view('payroll.show', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if (in_array($payroll->status, ['closed', 'paid'])) {
            return back()->with('error', 'Essa folha já está fechada e não pode mais ser alterada.');
        }

        $validated = $request->validate([
            'deductions'      => 'required|numeric|min:0',
            'deduction_notes' => 'nullable|string|max:500',
        ]);

        $payroll->deductions = (int) round($validated['deductions'] * 100);
        $payroll->deduction_notes = $validated['deduction_notes'];
        $payroll->net_total = max(0, $payroll->gross_total - $payroll->deductions);
        $payroll->save();

        return back()->with('success', 'Valores ajustados. Tudo certo!');
    }

    public function close(Payroll $payroll)
    {
        if (in_array($payroll->status, ['closed', 'paid'])) {
            return redirect()->back()->with('error', 'Essa folha já está fechada.');
        }

        $payroll->close();
        return redirect()->back()->with('success', 'Folha fechada! Sem mais alterações por aqui.');
    }
}
