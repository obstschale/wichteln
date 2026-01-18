<?php

namespace App\Http\Controllers;

use App\Group;
use App\Jobs\WichtelJob;
use App\Mail\WelcomeMail;
use App\Statistic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WichtelGroupController extends Controller
{
    /**
     * WichtelGroupController constructor.
     *
     * Apply API Token Guard for authentication to this controller.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'date' => 'required|date',
            'username' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($request->username) {
            $user = User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make(Str::random()),
                'api_token' => Str::random(60),
            ]);

            Auth::guard('web')->login($user);
        }

        $this->authorize('create', Group::class);

        $group = Group::create([
            'name' => $request->name,
            'date' => $request->date,
            'status' => 'created',
        ]);

        $group->users()->attach(Auth::user(), [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        Mail::to(Auth::user())->queue(new WelcomeMail($user, $group));
        Statistic::groupCreated();

        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $wichtelgroup
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Group $wichtelgroup)
    {
        $this->authorize('view', $wichtelgroup);
        return response()->json($wichtelgroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Group                     $wichtelgroup
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Group $wichtelgroup)
    {
        $this->authorize('update', $wichtelgroup);

        $request->validate([
            'name' => 'required|max:255',
            'date' => 'required|date',
            'status' => 'string',
        ]);

        if ($request->status === 'started') {
            $wichtelgroup->status = 'started';
            dispatch(new WichtelJob($wichtelgroup));
        }

        $wichtelgroup->name = $request->name;
        $wichtelgroup->date = $request->date;
        $wichtelgroup->save();

        return response()->json($wichtelgroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $wichtelgroup
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Group $wichtelgroup)
    {
        $this->authorize('delete', $wichtelgroup);
        $wichtelgroup->delete();
        return response('', 204);
    }
}
