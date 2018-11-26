<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('web.wichtelgroup.view')->with([
            'userId'  => Auth::user()->id,
            'group'   => $group,
            'isAdmin' => $group->admin()->id === Auth::user()->id
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
