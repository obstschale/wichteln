<?php

namespace App\Http\Controllers;

use App\Group;
use App\Mail\ApproveWichtelMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param Group                     $group
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
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

        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random()),
            'api_token' => Str::random(60),
        ]);
        //} else {
        //    if ($member->belongsToGroup($group)) {
        //        return response()->json([
        //            'message' => 'User already belongs to group.'
        //        ]);
        //    }
        //}

        $pivotData = [
            'wishlist' => $request->wishlist,
            'status' => 'invited',
            'is_admin' => false,
        ];

        $group->users()->save($member, $pivotData);

        Mail::to($member)->queue(new ApproveWichtelMember($member, $group));

        return response()->json($member, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @param User  $wichtelmember
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Group $group, User $wichtelmember)
    {
        $this->authorize('viewMembers', $group);

        return response()->json($group->users()->where('id', $wichtelmember->id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Group                     $group
     * @param User                      $wichtelmember
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Group $group, User $wichtelmember)
    {
        $this->authorize('updateMember', [$group, $wichtelmember]);

        // @TODO: Return JSON on validation fail. Should happen automatically
        $this->validate($request, [
            'wishlist' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:invited,approved',
        ]);

        if ($request->has('wishlist')) {
            $wichtelmember->saveWishlist($group, $request->wishlist);
        }

        if ($request->has('status')) {
            $this->authorize('createMember', $group);

            $group->users()->updateExistingPivot($wichtelmember->id, [
                'status' => $request->status,
            ]);
        }

        return response()->json($group->users()->where('id', $wichtelmember->id)->first());
    }

    /**
     * Resend the invitation email to a member.
     *
     * @param Group $group
     * @param User  $member
     *
     * @return Response
     */
    public function resendInvitation(Group $group, User $member)
    {
        $this->authorize('createMember', $group);

        Mail::to($member)->queue(new ApproveWichtelMember($member, $group));

        return response()->json(['message' => 'Einladung wurde erneut versendet.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @param User  $member
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Group $group, User $member)
    {
        $this->authorize('deleteMember', $group);

        $group->users()->detach($member);

        if (count($member->groups) === 0) {
            $member->delete();
        }

        return response('', 204);
    }
}
