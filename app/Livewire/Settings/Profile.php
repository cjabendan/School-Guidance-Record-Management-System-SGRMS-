<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    public $name = '';
    public $middle_name = '';
    public $last_name = '';
    public $email = '';
    public $num = '';
    public $cropped_image_data = null;
    public $cropped_image_name = null;
    public $toastPayload = null;

    public function mount()
    {
    // clear any transient toast payload on mount
    $this->toastPayload = null;

        $user = Auth::user();
        $this->name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;

        // normalize displayed phone: strip +63 or any non-digits so input holds exactly 10 digits
        $raw = $user->contact_num ?? '';
        $digits = preg_replace('/\D+/', '', $raw);
        if (str_starts_with($digits, '63') && strlen($digits) > 10) {
            $digits = substr($digits, -10);
        }
        $this->num = $digits;
    }

    public function save()
    {
        $user = Auth::user();

        $rules = [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'num' => ['required', 'regex:/^[0-9]{10}$/'],
        ];

        // Only admins may change name parts in the UI — validate if present
        if ($user->role === 'admin') {
            $rules['name'] = ['required','string','max:255'];
            $rules['middle_name'] = ['nullable','string','max:255'];
            $rules['last_name'] = ['nullable','string','max:255'];
        }

        $this->validate($rules);

        // Additional uniqueness check for stored format (+63XXXXXXXXXX)
        $fullNum = null;
        if ($this->num !== null && $this->num !== '') {
            $digits = preg_replace('/\D+/', '', $this->num);
            if (strlen($digits) !== 10) {
                $this->addError('num', 'Phone number must be exactly 10 digits.');
                return;
            }
            $fullNum = '+63' . $digits;

            $exists = \App\Models\User::where('contact_num', $fullNum)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                $this->addError('num', 'The phone number has already been taken.');
                return;
            }
        }

        // Apply changes
        if ($user->role === 'admin') {
            $user->first_name = $this->name;
            $user->middle_name = $this->middle_name;
            $user->last_name = $this->last_name;
        }

        $user->email = $this->email;
        $user->contact_num = $fullNum;
        $user->save();

        // handle cropped image upload if provided
        if ($this->cropped_image_data) {
            // expected format: data:image/png;base64,....
            try {
                $data = $this->cropped_image_data;
                if (preg_match('/^data:\w+\/\w+;base64,/', $data)) {
                    $parts = explode(',', $data);
                    $imageData = base64_decode($parts[1]);
                    $ext = 'png';
                    if (strpos($parts[0], 'jpeg') !== false || strpos($parts[0], 'jpg') !== false) {
                        $ext = 'jpg';
                    }
                    // use provided original filename if present, else fallback to generated name
                    $dir = public_path('images/user');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    if ($this->cropped_image_name) {
                        // sanitize and use base name, force png extension
                        $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($this->cropped_image_name, PATHINFO_FILENAME));
                        $filename = $safeBase . '.png';
                        $target = $dir . DIRECTORY_SEPARATOR . $filename;
                        if (file_exists($target)) {
                            $filename = $safeBase . '_' . time() . '.png';
                        }
                    } else {
                        $filename = 'profile_' . $user->id . '_' . time() . '.' . $ext;
                    }
                    $path = $dir . DIRECTORY_SEPARATOR . $filename;
                    file_put_contents($path, $imageData);
                    // update DB
                    $user->profile_image = $filename;
                    $user->save();
                    // clear the temporary cropped data so UI shows stored image on mount
                    $this->cropped_image_data = null;
                    // clear uploaded name too
                    $this->cropped_image_name = null;
                }
            } catch (\Exception $e) {
                // swallow error but log it
                \Log::error('Failed to save cropped profile image', ['err' => $e->getMessage()]);
            }
        }

    // Refresh mount data first
    $this->mount();
    // Set a payload property that the Blade will render as a small inline script to show a toast.
    // This avoids calling Livewire methods that may be missing in this environment.
    $this->toastPayload = ['type' => 'success', 'message' => 'Profile updated successfully.'];
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}

