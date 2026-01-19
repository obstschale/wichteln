<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Group;
use App\User;

class AdminController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->orderByDesc('created_at')->get();
        $users = User::with('groups')->orderBy('name')->get();

        return view('admin.dashboard', [
            'groups' => $groups,
            'users' => $users,
        ]);
    }
}
