<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\CounselingNotes;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HeadCounselingController extends Controller
{
    public function index()
    {
        $query = CounselingNotes::with(['user'])->orderBy('created_at', 'desc');

        // filter by remarks if provided
        if (request()->filled('remarks')) {
            $query->where('remarks', request('remarks'));
        }

        // live search: search note_id, student_name, observations
        if (request()->filled('search')) {
            $s = request('search');
            $query->where(function($q) use ($s) {
                $q->where('note_id', 'like', "%{$s}%")
                  ->orWhere('student_name', 'like', "%{$s}%")
                  ->orWhere('observations', 'like', "%{$s}%");
            });
        }

    $counselings = $query->paginate(10);
    $counselings->appends(request()->only(['search','remarks']));

    return view('Head.counseling', compact('counselings'));
    }

    public function show($id)
    {
        $counseling = CounselingNotes::findOrFail($id);
        return response()->json($counseling);
    }

    public function store(Request $request)
    {
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