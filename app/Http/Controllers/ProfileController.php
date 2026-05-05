<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Atualiza name e email
        $user->fill($request->safe()->only(['name', 'email']));

        // Remove avatar se solicitado
        if ($request->input('remove_avatar') === '1') {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                $user->avatar = null;
            }
        }

        // Upload de novo avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Validar que o arquivo é uma imagem real
            if ($file->isValid()) {
                // Remove avatar antigo do disco
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $file->store('avatars', 'public');
            }
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->employee) {
            $employeeData = $request->safe()->only([
                'rg', 'birth_date', 'gender', 'marital_status', 
                'phone', 'address', 'emergency_contact_name', 'emergency_contact_phone'
            ]);
            
            // Verifica se algum dado do funcionário realmente mudou
            $user->employee->fill($employeeData);
            if ($user->employee->isDirty()) {
                $user->employee->save();
                
                // Notifica os gestores de RH sobre a atualização
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    \App\Services\NotificationService::notify(
                        $admin->id,
                        '📝 Perfil Atualizado',
                        "O funcionário {$user->name} atualizou seus dados de contato/endereço.",
                        route('employees.show', $user->employee->id)
                    );
                }
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        \Illuminate\Support\Facades\Auth::logout();

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Se o usuário for um funcionário, apagar os atestados dele
            if ($user->employee) {
                $certificates = \App\Models\MedicalCertificate::where('employee_id', $user->employee->id)->whereNotNull('file_path')->get();
                foreach ($certificates as $cert) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($cert->file_path);
                }
            }

            // Remover avatar
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
