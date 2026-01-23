<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\WelcomeMemberMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TokenController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->token;
        $action = $request->action;

        if (!in_array($action, ['approve', 'decline'], true)) {
            return redirect('/');
        }

        $membership = DB::table('group_user')->where('token', $token)->first();

        if (!$membership || $membership->status !== 'invited') {
            return redirect('/');
        }

        return view('partials.confirm-action', [
            'action' => $action,
            'token' => $token,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'action' => 'required|in:approve,decline',
        ]);

        $token = $request->token;
        $action = $request->action;

        switch ($action) {
            case 'approve':
                $row = $this->approve($token);

                if ($row === 1) {
                    $user = User::whereHas('groups', static function ($group) use ($token) {
                        $group->where('token', $token);
                    })
                        ->with(['groups' => function ($query) use ($token) {
                            $query->where('token', $token);
                        }])
                        ->first();

                    $group = $user->groups->first();
                    Mail::to($user)->send(new WelcomeMemberMail($user, $token));

                    return view('partials.approved', [
                        'group' => $group,
                        'userToken' => $user->api_token,
                    ]);
                }

                break;

            case 'decline':
                $row = $this->decline($token);

                if ($row === 1) {
                    return view('partials.declined');
                }

                break;
        }

        return redirect('/');
    }

    /**
     * @todo move to own repository
     *
     * @param $token
     *
     * @return int altered rows
     */
    public function approve($token)
    {
        return DB::table('group_user')->where('token', $token)->update(['status' => 'approved']);
    }

    /**
     * @todo move to own repository
     *
     * @param $token
     *
     * @return int altered rows
     */
    public function decline($token)
    {
        return DB::table('group_user')->where('token', $token)->update(['status' => 'declined']);
    }
}
