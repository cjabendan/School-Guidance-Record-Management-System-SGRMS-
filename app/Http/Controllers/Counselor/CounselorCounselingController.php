<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselingNotes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CounselorCounselingController extends Controller
{
    public function index(Request $request)
    {
        $remarks = $request->get('remarks', null);
        $search = $request->get('search', null);

        $counselings = CounselingNotes::with(['user'])
            ->when($remarks && strtolower($remarks) !== 'all', function($q) use ($remarks) {
                $q->where('remarks', ucfirst($remarks));
            })
            ->when($search, function($q) use ($search) {
                $term = "%{$search}%";
                $q->where(function($q2) use ($term) {
                    $q2->where('note_id', 'like', $term)
                       ->orWhere('student_name', 'like', $term)
                       ->orWhere('observations', 'like', $term);
                });
            })
                ->orderByDesc('created_at')
                ->paginate(10);

            // preserve query params for pagination links
            $counselings->appends($request->only(['search','remarks']));

            return view('Counselor.counseling', compact('counselings'));
    }

    public function store(Request $request)
    {
    Log::info('CounselorCounselingController::store called', $request->all());
        $validated = $request->validate([
            'student_name' => 'required|string|max:50',
            'user_id' => 'required|integer|exists:users,id',
            'observations' => 'required|string',
            'remarks' => 'required|in:Alarming,Moderate,Low',
            'recommendations' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $validated['follow_up_needed'] = $request->has('follow_up_needed') ? 1 : 0;
        if ($request->filled('follow_up_date')) {
            $validated['follow_up_date'] = Carbon::parse($request->input('follow_up_date'));
        }

        CounselingNotes::create($validated);

        return redirect()->back()->with('success', 'Counseling note saved.');
    }

    public function update(Request $request, $id)
    {
        $counseling = CounselingNotes::findOrFail($id);

        $validated = $request->validate([
            'student_name' => 'required|string|max:50',
            'user_id' => 'required|integer|exists:users,id',
            'observations' => 'required|string',
            'remarks' => 'required|in:Alarming,Moderate,Low',
            'recommendations' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $validated['follow_up_needed'] = $request->has('follow_up_needed') ? 1 : 0;
        if ($request->filled('follow_up_date')) {
            $validated['follow_up_date'] = Carbon::parse($request->input('follow_up_date'));
        } else {
            $validated['follow_up_date'] = null;
        }

        $counseling->update($validated);

        return redirect()->back()->with('success', 'Counseling note updated.');
    }

    public function destroy($id)
    {
        $counseling = CounselingNotes::findOrFail($id);
        $counseling->delete();
        return redirect()->back()->with('success', 'Counseling note deleted.');
    }


}