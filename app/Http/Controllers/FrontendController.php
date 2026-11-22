<?php

declare(strict_types=1);

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
        $user = User::where('api_token', $token)->first();

        if (is_null($user)) {
            throw new NotFoundHttpException();
        }

        Auth::login($user);
        $this->authorize('view', $group);

        $group->loadMissing('users');

        $buddyId = $user->groups()->whereKey($group->id)->value('group_user.buddy_id');
        $buddy = $group->users->find($buddyId);

        return view('web.wichtelgroup.view')->with([
            'userId' => Auth::user()->id,
            'group' => $group,
            'isAdmin' => $user->can('viewAssignments', $group),
            'buddy' => $buddy
                ? json_encode([
                    'name' => $buddy->name,
                    'wishlist' => $buddy->pivot->wishlist,
                ], JSON_THROW_ON_ERROR) : '{}',
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
