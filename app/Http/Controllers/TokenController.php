<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->action) {
            case 'approve':
                $row = $this->approve($request->token);

                if ($row === 1) {
                    return view('partials.approved');
                }

                break;

            case 'decline':
                $row = $this->decline($request->token);

                if ($row === 1) {
                    return view('partials.declined');
                }

                break;

            default:
        }

        return redirect('/');
    }

    /**
     * @todo move to own repository
     * @param $token
     * @return int altered rows
     */
    public function approve($token)
    {
        return DB::table('group_user')
            ->where('token', $token)
            ->update(['status' => 'approved']);

    }

    /**
     * @todo move to own repository
     * @param $token
     * @return int altered rows
     */
    public function decline($token)
    {
        return DB::table('group_user')
            ->where('token', $token)
            ->update(['status' => 'declined']);
    }
}
