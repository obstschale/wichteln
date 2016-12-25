<?php

namespace App\Http\Controllers;

use App\Group;
use App\Jobs\SendApprovalEmail;
use App\Mail\ApproveWichtelmember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class WichtelMemberController extends Controller
{
    /**
     * WichtelMemberController constructor.
     *
     * Apply API Token Guard for authentication to this controller.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        $this->authorize('viewMembers', $group);

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
        $this->authorize('createMember', $group);

        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'wishlist' => 'string|max:1000',
        ]);

        // Check if user is already in DB
        $member = User::where('email', $request->email)->first();

        // If not create new instance
        if (is_null($member)) {
            $member = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(str_random(16)),
                'api_token' => str_random(60),
            ]);
        } else {
            if ($member->belongsToGroup($group)) {
                return response()->json([
                    'message' => 'User already belongs to group.'
                ]);
            }
        }

        $pivotData = [
            'wishlist' => $request->wishlist,
            'status' => 'invited',
            'is_admin' => false,
        ];

        $group->users()->save($member, $pivotData);

        Mail::to($member)->queue(new ApproveWichtelmember($member, $group));

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
        $this->authorize('viewMember', $group);

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
        $this->authorize('updateMember', [$group, $wichtelmember]);

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
        $this->authorize('deleteMember', $group);

        $group->users()->detach($wichtelmember);

        if (count($wichtelmember->groups) === 0) {
            $wichtelmember->delete();
        }

        return response('', 204);
    }
}
