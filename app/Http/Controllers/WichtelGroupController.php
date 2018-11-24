<?php

namespace App\Http\Controllers;

use App\Group;
use App\Jobs\WichtelJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class WichtelGroupController extends Controller
{
    /**
     * WichtelGroupController constructor.
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // @TODO: Only Access for Admins
        // return response()->json(Group::all());
        return response('', 501);
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
        $this->authorize('create', Group::class);

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

        $group->users()->attach(Auth::user(), [
            'status' => 'approved',
            'is_admin' => true,
        ]);

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

        // @TODO: Return JSON on validation fail. Should happen automatically
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'date' => 'required|date',
            'status' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

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
