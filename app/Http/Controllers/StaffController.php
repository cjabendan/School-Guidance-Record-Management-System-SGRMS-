<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Counselor;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        // Get all admins with their user info
        $admins = Admins::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })->get()
            ->map(function ($admin) {
                $middleInitial = $admin->user->middle_name
                    ? strtoupper(substr($admin->user->middle_name, 0, 1)) . '. '
                    : '';

                return [
                    'name' => $admin->user->first_name . ' ' . $middleInitial . $admin->user->last_name,
                    'role' => 'School Principal',
                    'image' => $admin->user->profile_image ?? 'default.png',
                ];
            });

        // Get all counselors with their user info
        $counselors = Counselor::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })->get()
            ->map(function ($counselor) {
                $middleInitial = $counselor->user->middle_name
                    ? strtoupper(substr($counselor->user->middle_name, 0, 1)) . '. '
                    : '';

                return [
                    'name' => $counselor->user->first_name . ' ' . $middleInitial . $counselor->user->last_name,
                    'role' => 'Counselor',
                    'image' => $counselor->user->profile_image ?? 'default.png',
                ];
            });


        // Merge both collections
        $staff = $admins->merge($counselors);

        return view('landing', compact('staff'));
    }
}
