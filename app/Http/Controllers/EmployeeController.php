<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\Cpf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        // View delegates the data fetching entirely to the Livewire EmployeeTable component
        return view('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'cpf'            => ['required', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', 'unique:employees,cpf', new Cpf],
            'rg'             => 'nullable|string|max:20|unique:employees,rg',
            'birth_date'     => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other,not_specified',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,other',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'phone'          => 'nullable|string|max:20',
            'admission_date' => 'required|date',
            'base_salary'    => 'nullable|numeric|min:0',
            'payment_type'   => 'nullable|in:monthly,hourly',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Converte salário de reais para centavos
        $baseSalaryCents = $validated['base_salary'] ? (int) round($validated['base_salary'] * 100) : null;

        DB::transaction(function () use ($validated, $avatarPath, $baseSalaryCents) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'employee',
                'avatar'   => $avatarPath,
            ]);

            $employee = Employee::create([
                'user_id'        => $user->id,
                'cpf'            => $validated['cpf'],
                'rg'             => $validated['rg'] ?? null,
                'birth_date'     => $validated['birth_date'] ?? null,
                'gender'         => $validated['gender'] ?? 'not_specified',
                'marital_status' => $validated['marital_status'] ?? 'single',
                'emergency_contact_name'  => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'position'       => $validated['position'],
                'address'        => $validated['address'],
                'phone'          => $validated['phone'] ?? null,
                'admission_date' => $validated['admission_date'],
                'base_salary'    => $baseSalaryCents,
            ]);

            // Criar a vigência inicial de RH para o funcionário com os dados salariais (se informados)
            if (isset($validated['payment_type']) || $baseSalaryCents) {
                $paymentType = $validated['payment_type'] ?? 'monthly';
                // Assumimos que base_salary no form significa salário mensal se for mensalista, 
                // e o valor da hora se for horista.
                $hourlyRate = $paymentType === 'hourly' 
                    ? $baseSalaryCents 
                    : ($baseSalaryCents ? (int) round($baseSalaryCents / 220) : 0);

                $defaults = \App\Models\HrConfig::defaults()->toArray();
                
                \App\Models\HrConfig::create(array_merge($defaults, [
                    'employee_id'         => $employee->id,
                    'vigencia_inicio'     => \Carbon\Carbon::parse($validated['admission_date'])->startOfMonth(),
                    'payment_type'        => $paymentType,
                    'default_hourly_rate' => $hourlyRate,
                ]));
            }
        });

        return redirect()->route('employees.index')
            ->with('success', 'Pronto! A equipe acaba de ganhar mais um talento.');
    }

    public function show(Employee $employee)
    {
        $employee->load('user', 'workSchedule');
        $workLogs = $employee->workLogs()
            ->orderBy('work_date', 'desc')
            ->paginate(10);
            
        $recentPayrolls = $employee->payrolls()
            ->orderBy('reference_month', 'desc')
            ->take(3)
            ->get();
            
        $recentCertificates = $employee->medicalCertificates()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $hourBankBalance = $employee->total_hour_bank_minutes;

        return view('employees.show', compact('employee', 'workLogs', 'recentPayrolls', 'recentCertificates', 'hourBankBalance'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $employee->user_id,
            'cpf'            => ['required', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', 'unique:employees,cpf,' . $employee->id, new Cpf],
            'rg'             => 'nullable|string|max:20|unique:employees,rg,' . $employee->id,
            'birth_date'     => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other,not_specified',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,other',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'phone'          => 'nullable|string|max:20',
            'admission_date' => 'required|date',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $userUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        if ($request->hasFile('avatar')) {
            if ($employee->user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->user->avatar);
            }
            $userUpdate['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $employee->user->update($userUpdate);

        $employee->update([
            'cpf'            => $validated['cpf'],
            'rg'             => $validated['rg'] ?? null,
            'birth_date'     => $validated['birth_date'] ?? null,
            'gender'         => $validated['gender'] ?? 'not_specified',
            'marital_status' => $validated['marital_status'] ?? 'single',
            'emergency_contact_name'  => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            'position'       => $validated['position'],
            'address'        => $validated['address'],
            'phone'          => $validated['phone'] ?? null,
            'admission_date' => $validated['admission_date'],
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Tudo certo! As informações do funcionário foram atualizadas.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user_id === Auth::id()) {
            return redirect()->route('employees.index')
                ->with('error', 'Ei, você não pode excluir seu próprio acesso, né?');
        }

        // Impedir exclusão se o funcionário tiver histórico financeiro (holerites)
        if (\App\Models\Payroll::where('employee_id', $employee->id)->exists()) {
            return redirect()->route('employees.index')
                ->with('error', 'Este funcionário possui histórico financeiro (holerites) e não pode ser excluído para manter a integridade contábil. Recomenda-se apenas desativá-lo.');
        }

        DB::transaction(function () use ($employee) {
            // Remover arquivos de atestados médicos
            $certificates = \App\Models\MedicalCertificate::where('employee_id', $employee->id)->whereNotNull('file_path')->get();
            foreach ($certificates as $cert) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cert->file_path);
            }

            // Remover avatar
            if ($employee->user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->user->avatar);
            }

            $employee->user->delete();
        });

        return redirect()->route('employees.index')
            ->with('success', 'Feito. O funcionário foi removido da plataforma e seus arquivos foram limpos.');
    }
}