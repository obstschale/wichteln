<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Group;
use App\Mail\SelfRegistrationConfirmMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SelfRegistrationController extends Controller
{
    public function showForm(string $joinToken)
    {
        $group = Group::where('join_token', $joinToken)->firstOrFail();

        if ($group->started()) {
            return view('self-registration.closed', ['group' => $group]);
        }

        return view('self-registration.form', ['group' => $group, 'joinToken' => $joinToken]);
    }

    public function register(Request $request, string $joinToken)
    {
        $group = Group::where('join_token', $joinToken)->firstOrFail();

        if ($group->started()) {
            return redirect()
                ->route('join.form', $joinToken)
                ->with('error', 'Die Gruppe hat bereits mit dem Wichteln begonnen.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'wishlist' => 'nullable|string|max:1000',
        ]);

        $emailExistsInGroup = $group->users()
            ->where('email', $validated['email'])
            ->exists();

        if ($emailExistsInGroup) {
            return redirect()
                ->route('join.form', $joinToken)
                ->with('error', 'Diese E-Mail-Adresse ist bereits in dieser Gruppe registriert.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'api_token' => Str::random(60),
        ]);

        $confirmToken = Str::random(16);

        DB::table('group_user')->insert([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'status' => 'invited',
            'wishlist' => $validated['wishlist'],
            'is_admin' => false,
            'token' => $confirmToken,
        ]);

        Mail::to($user)->send(new SelfRegistrationConfirmMail($user, $group));

        return view('self-registration.success', ['group' => $group]);
    }
}
