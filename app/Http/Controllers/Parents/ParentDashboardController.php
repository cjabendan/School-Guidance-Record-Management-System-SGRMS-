<?php

namespace App\Http\Controllers\Parents;

use App\Models\ParentModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcements;
use Illuminate\Support\Facades\DB;

class ParentDashboardController extends Controller
{

    /**
     * Display the dashboard view.
     */
    public function dashboard()
    {
        $announcements = Announcements::orderByDesc('date_posted')->take(10)->get();
        return view('Parent.dashboard', compact('announcements'));
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = DB::table('parents')
            ->leftJoin('users', 'parents.user_id', '=', 'users.id')
            ->select(
                'parents.*',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) AS full_name"),
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                'users.profile_image',
                'users.username'
            )
            ->get(); // or use ->paginate(10) for pagination

        return view('Head.profiling.parents', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentModel $parent)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParentModel $parent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParentModel $parents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParentModel $parent)
    {
        //
    }

}
