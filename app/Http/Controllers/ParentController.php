<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
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
                'parents.guardian_relationship',
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
