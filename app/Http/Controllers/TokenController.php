<?php

namespace App\Http\Controllers;

use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTokenController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->action) {
            case 'approve':
                $this->approve($request->token);
                return view('partials.approved');
                break;

            case 'decline':
                $this->decline($request->token);
                return view('partials.declined');
                break;

            default:
                return view('welcome');
        }
    }

    /**
     * @todo move to own repository
     * @param $token
     */
    public function approve($token)
    {
        DB::table('group_user')
            ->where('token', $token)
            ->update(['status' => 'approved']);
    }

    /**
     * @todo move to own repository
     * @param $token
     */
    public function decline($token)
    {
        DB::table('group_user')
            ->where('token', $token)
            ->update(['status' => 'declined']);
    }
}
