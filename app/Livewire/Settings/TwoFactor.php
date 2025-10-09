<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Collection;

class TwoFactor extends Component
{
    public bool $enabled = false;
    public bool $showRecoveryCodes = false;
    public bool $showQrModal = false;
    public bool $showAuthModal = false;
    public string $qrCodeSvg = '';
    public string $secret = '';
    public string $verifyCode = '';
    public array $recoveryCodes = [];
    public $verifyCodeDigits = [];

    public function mount()
    {
        $this->enabled = !empty(Auth::user()->two_factor_secret);
        $this->verifyCodeDigits = array_fill(0, 6, '');
    }

    public function toggle()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        if (!$this->enabled) {
            // Generate a new secret and QR code
            $this->secret = $google2fa->generateSecretKey(); // Base32, uppercase letters + digits 2–7
            $qrCodeUrl = $google2fa->getQRCodeUrl(config('app.name'), $user->email, $this->secret);

            $writer = new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd()));
            $this->qrCodeSvg = $writer->writeString($qrCodeUrl);

            $this->showQrModal = true;
        } else {
            // Disable 2FA
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->save();

            $this->enabled = false;
            $this->reset(['qrCodeSvg', 'secret', 'recoveryCodes']);
            session()->flash('success', 'Two-Factor Authentication has been disabled.');
        }
    }

    public function proceedToAuth()
    {
        $this->showQrModal = false;
        $this->showAuthModal = true;
    }

    public function verifyTwoFactor()
    {
        if (count($this->verifyCodeDigits) !== 6 || in_array('', $this->verifyCodeDigits, true)) {
            session()->flash('error', 'Please enter all 6 digits.');
            return;
        }

        $this->verifyCode = implode('', $this->verifyCodeDigits);
        $google2fa = new Google2FA();
        $user = Auth::user();

        try {
            // Raw Base32 secret
            if ($google2fa->verifyKey($this->secret, $this->verifyCode)) {
                $recoveryCodes = $this->generateRecoveryCodes();

                $user->two_factor_secret = encrypt($this->secret);
                $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes->all()));
                $user->save();

                $this->enabled = true;
                $this->recoveryCodes = $recoveryCodes->all();
                $this->showAuthModal = false;

                session()->flash('success', 'Two-Factor Authentication has been enabled.');
            } else {
                session()->flash('error', 'Invalid authentication code.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while verifying your code.');
        }
    }

    public function toggleRecoveryCodes()
    {
        $this->showRecoveryCodes = !$this->showRecoveryCodes;

        if ($this->showRecoveryCodes && empty($this->recoveryCodes)) {
            $user = Auth::user();

            if ($user->two_factor_recovery_codes) {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            }
        }
    }

    public function regenerateRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes->all())),
        ])->save();

        $this->recoveryCodes = $recoveryCodes->all();
        session()->flash('success', 'New recovery codes have been generated.');
    }

    protected function generateRecoveryCodes(): Collection
    {
        // Uppercase recovery codes for consistency
        return Collection::times(6, function () {
            return strtoupper(str()->random(4) . '-' . str()->random(4) . '-' . str()->random(4));
        });
    }

    public function copySecret()
    {
        $this->dispatchBrowserEvent('copy-secret', ['secret' => $this->secret]);
    }

    public function render()
    {
        return view('livewire.settings.two-factor');
    }
}
