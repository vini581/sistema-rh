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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::with(['user', 'workSchedule'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search'));
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
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'admission_date' => 'required|date',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::transaction(function () use ($validated, $avatarPath) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'employee', // Forçado como funcionário por segurança
                'avatar'   => $avatarPath,
            ]);

            Employee::create([
                'user_id'        => $user->id,
                'cpf'            => $validated['cpf'],
                'position'       => $validated['position'],
                'address'        => $validated['address'],
                'admission_date' => $validated['admission_date'],
            ]);
        });

        return redirect()->route('employees.index')
            ->with('success', 'Pronto! A equipe acaba de ganhar mais um talento.');
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        $workLogs = $employee->workLogs()
            ->orderBy('work_date', 'desc')
            ->paginate(20);
        return view('employees.show', compact('employee', 'workLogs'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'cpf'            => ['required', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', 'unique:employees,cpf,' . $employee->id, new Cpf],
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'admission_date' => 'required|date',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $userUpdate = ['name' => $request->name];
        
        if ($request->hasFile('avatar')) {
            if ($employee->user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->user->avatar);
            }
            $userUpdate['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $employee->user->update($userUpdate);

        $employee->update([
            'cpf'            => $request->cpf,
            'position'       => $request->position,
            'address'        => $request->address,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Tudo certo! As informações do funcionário foram atualizadas.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user_id === Auth::id()) {
            return redirect()->route('employees.index')
                ->with('error', 'Ei, você não pode excluir seu próprio acesso, né?');
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