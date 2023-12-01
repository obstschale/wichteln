<?php

namespace App\Http\Controllers;

use App\Group;
use App\Mail\WelcomeMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontendController extends Controller
{

    public function welcome()
    {
        return view('welcome');
    }


    public function showWichtelgroup(Request $request, Group $group)
    {
        $token = $request->query('token');
        $user  = User::where('api_token', $token)->first();

        if (is_null($user)) {
            throw new NotFoundHttpException();
        }

        Auth::login($user);
        $this->authorize('view', $group);

        $group->loadMissing('users');

        $buddy_id = $user->groups[0]->pivot->buddy_id;
        $buddy = User::where('id', $buddy_id)->with([
            'groups' => function ($query) use ($group) {
                $query->where('group_id', $group->id);
            }
        ])->first();

        return view('web.wichtelgroup.view')->with([
            'userId'  => Auth::user()->id,
            'group'   => $group,
            'isAdmin' => $group->admin()->id === Auth::user()->id,
            'buddy' => $buddy ? json_encode([
                'name'     => $buddy->name,
                'wishlist' => $buddy->groups[0]->pivot->wishlist,
            ], JSON_THROW_ON_ERROR) : "{}"
        ]);
    }


    public function imprint()
    {
        return view('imprint');
    }


    public function dataPrivacy()
    {
        return view('data-privacy');
    }
}
