<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Group;
use App\Statistic;
use App\User;

class AdminController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->orderByDesc('created_at')->get();
        $users = User::with('groups')->orderBy('name')->get();

        $statistics = Statistic::whereIn('name', [Statistic::CREATED_GROUPS, Statistic::STARTED_GROUPS])
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn($stat) => $stat->created_at->format('Y-m'));

        $chartData = [];
        foreach ($statistics as $month => $stats) {
            $chartData[$month] = [
                'created' => $stats->where('name', Statistic::CREATED_GROUPS)->sum('count'),
                'started' => $stats->where('name', Statistic::STARTED_GROUPS)->sum('count'),
            ];
        }

        $totalCreated = (int) Statistic::where('name', Statistic::CREATED_GROUPS)->sum('count');
        $totalStarted = (int) Statistic::where('name', Statistic::STARTED_GROUPS)->sum('count');
        $totalAccounts = (int) Statistic::where('name', Statistic::ACCOUNTS)->sum('count');

        return view('admin.dashboard', [
            'groups' => $groups,
            'users' => $users,
            'chartData' => $chartData,
            'totalCreated' => $totalCreated,
            'totalStarted' => $totalStarted,
            'totalAccounts' => $totalAccounts,
        ]);
    }
}
