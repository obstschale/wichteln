<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'wishlist' => 'string|max:1000',
        ]);

        // Check if user is already in DB
        $member = User::where('email', $request->email)->first();

        if ($member->belongsToGroup($group)) {
            return response()->json([
                'message' => 'User already belongs to group.'
            ]);
        }

        // If not create new instance
        if (is_null($member)) {
            $member = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(generateRandomString(16)),
            ]);
        }

        $pivotData = [
            'wishlist' => $request->wishlist,
            'status' => 'invited',
        ];

        $group->users()->save($member, $pivotData);

        // @TODO: dispatch( Invite Mail )

        return response()->json($member, 201);
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
        if (!$wichtelmember->belongsToGroup($group)) {
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
     * @param Group $group
     * @param User $wichtelmember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, User $wichtelmember)
    {
        if (!$wichtelmember->belongsToGroup($group)) {
            return response()->json([
                'message' => "This member ({$wichtelmember->id}) is not part of this group ({$group->id})",
            ], 403);
        }

        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'wishlist' => 'string|max:1000',
        ]);

        $wichtelmember->saveWishlist($group, $request->wishlist);

        return response()->json($wichtelmember);

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
        if (!$wichtelmember->belongsToGroup($group)) {
            return response()->json([
                'message' => "This member ({$wichtelmember->id}) is not part of this group ({$group->id})",
            ], 403);
        }

        $group->users()->detach($wichtelmember);

        if (count($wichtelmember->groups) === 0) {
            $wichtelmember->delete();
        }

        return response('', 204);
    }
}
