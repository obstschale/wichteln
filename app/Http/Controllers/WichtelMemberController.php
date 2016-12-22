<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;

class WichtelMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // @TODO: Only Access for Admins
        $members = $group->users()->get();

        return response()->json($members);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @param User $wichtelmember
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, User $wichtelmember)
    {
        if (! $wichtelmember->belongsToGroup($group)) {
            return response()->json([
                'message' => "This member ({$wichtelmember->id}) is not part of this group ({$group->id})",
            ], 403);
        }

        return response()->json($wichtelmember);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @param User $wichtelmember
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, User $wichtelmember)
    {
        if (! $wichtelmember->belongsToGroup($group)) {
            return response()->json([
                'message' => "This member ({$wichtelmember->id}) is not part of this group ({$group->id})",
            ], 403);
        }

        $wichtelmember->delete();

        return response('', 204);
    }
}
