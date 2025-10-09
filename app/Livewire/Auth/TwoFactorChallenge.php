<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallenge extends Component
{
    public $code = '';
    public $errorMessage = '';
    public $verifyCodeDigits = [];
    public bool $recoveryCode = false;

    public function mount()
    {
        $this->verifyCodeDigits = array_fill(0, 6, '');
    }

    public function useRecoveryCode()
    {
        $this->recoveryCode = true;
        $this->errorMessage = ''; 
        $this->code = '';
    }

    public function useAuthCode()
    {
        $this->recoveryCode = false;
        $this->errorMessage = '';
        $this->verifyCodeDigits = array_fill(0, 6, '');
    }

    public function verify()
    {
        $this->code = $this->recoveryCode
            ? trim($this->code)
            : implode('', $this->verifyCodeDigits);

        $userId = session('2fa:user:id');
        $user = $userId ? User::find($userId) : null;

        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        // Helper to finalize login
        $finalizeLogin = function () use ($user) {
            Auth::login($user);
            session()->regenerate();

            $role = session('pending_role') ?? $user->role;
            $roleId = session('pending_role_id') ?? match ($role) {
                'admin' => $user->admin?->a_id,
                'student' => $user->student?->s_id,
                'parent' => $user->parentProfile?->p_id,
                'counselor' => $user->counselor?->c_id,
                default => null,
            };

            session(['role' => $role, 'role_id' => $roleId]);
            session()->forget(['2fa:user:id', 'pending_role', 'pending_role_id']);

            return match ($role) {
                'admin' => redirect()->intended('/Head/dashboard')->with('success', 'Welcome back, Admin!'),
                'student' => redirect()->intended('/Student/dashboard')->with('success', 'Welcome back, Student!'),
                'parent' => redirect()->intended('/Parent/dashboard')->with('success', 'Welcome back, Parent!'),
                'counselor' => redirect()->intended('/Counselor/dashboard')->with('success', 'Welcome back, Counselor!'),
                default => redirect()->intended('/')->with('success', 'Welcome back!'),
            };
        };

        $google2fa = new Google2FA();

        // 🔹 Try authentication code first
        if (!$this->recoveryCode) {
            try {
                $secret = decrypt($user->two_factor_secret);
                if ($google2fa->verifyKey($secret, $this->code)) {
                    return $finalizeLogin();
                }
            } catch (\Exception $e) {
                $this->errorMessage = 'Invalid or unreadable 2FA secret.';
                return;
            }
        }

        // 🔹 Try recovery codes (normalize, case-insensitive, allow hyphens/spaces; single-use)
        try {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
        } catch (\Exception $e) {
            $recoveryCodes = [];
        }

        if (!empty($recoveryCodes)) {
            // normalize by removing non-alphanumerics and uppercasing
            $normalize = static fn($s) => preg_replace('/[^A-Z0-9]/', '', strtoupper((string)$s));
            $enteredNorm = $normalize($this->code);
            $normalizedMap = array_map($normalize, $recoveryCodes);

            $matchIndex = array_search($enteredNorm, $normalizedMap, true);

            if ($matchIndex !== false) {
                // remove the used recovery code (single-use) from the original stored array
                unset($recoveryCodes[$matchIndex]);
                $remaining = array_values($recoveryCodes);

                $user->two_factor_recovery_codes = $remaining
                    ? encrypt(json_encode($remaining))
                    : null;
                $user->save();

                return $finalizeLogin();
            }
        }

        $this->errorMessage = 'Invalid code or recovery code.';
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('layouts.auth');
    }
}
