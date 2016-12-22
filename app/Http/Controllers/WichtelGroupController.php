<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class WichtelGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // @TODO: Only Access for Admins
        return response()->json(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'name' => 'required|max:255',
            'date' => 'required|date',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'date' => $request->date,
            'status' => 'created',
        ]);

        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $wichtelgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Group $wichtelgroup)
    {
        return response()->json($wichtelgroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Group $wichtelgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $wichtelgroup)
    {
        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'name' => 'required|max:255',
            'date' => 'required|date'
        ]);

        $wichtelgroup->name = $request->name;
        $wichtelgroup->date = $request->date;
        $wichtelgroup->save();

        return response()->json($wichtelgroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $wichtelgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $wichtelgroup)
    {
        $wichtelgroup->delete();

        return response('', 204);
    }
}
