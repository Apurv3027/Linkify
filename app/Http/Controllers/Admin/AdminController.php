<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403, 'Access denied. You are not authorized to access this page.');
        }

        $totalUsers = User::where('is_admin', false)->count();
        $totalLinks = Link::count();
        $totalClicks = Click::count();

        $latestUsers = User::latest()->take(5)->get();
        $latestLinks = Link::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalLinks',
            'totalClicks',
            'latestUsers',
            'latestLinks'
        ));
    }

    public function users(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return view('admin.components.user_table', compact('users'))->render();
        }

        return view('admin.users', compact('users'));
    }

    public function links()
    {
        $links = Link::with('user')->latest()->paginate(10);

        return view('admin.links', compact('links'));
    }

    public function analyticsData(Request $request)
    {
        $days = (int) ($request->days ?? 7);

        $startDate = now()->subDays($days)->startOfDay();
        $previousStartDate = now()->subDays($days * 2)->startOfDay();
        $previousEndDate = now()->subDays($days)->endOfDay();

        // Current period totals
        $totalClicks = Click::where('created_at', '>=', $startDate)->count();
        $totalUsers = User::where('created_at', '>=', $startDate)->count();

        // Previous period totals
        $previousClicks = Click::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $previousUsers = User::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Calculate percentage growth safely
        $clickGrowth = $previousClicks > 0
            ? (($totalClicks - $previousClicks) / $previousClicks) * 100
            : 100;

        $userGrowth = $previousUsers > 0
            ? (($totalUsers - $previousUsers) / $previousUsers) * 100
            : 100;

        // Grouped daily data
        $clicksData = Click::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $usersData = User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $clicks = [];
        $users = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $labels[] = $date;
            $clicks[] = $clicksData[$date] ?? 0;
            $users[] = $usersData[$date] ?? 0;
        }

        $topLinks = Link::orderByDesc('clicks')
            ->take(5)
            ->get(['short_code', 'clicks']);

        $countries = Click::where('created_at', '>=', $startDate)
            ->selectRaw("COALESCE(country,'Unknown') as country, COUNT(*) as total")
            ->groupBy('country')
            ->orderByDesc('total')
            ->pluck('total', 'country');

        $recentClicks = Click::latest()
            ->take(10)
            ->get(['ip_address', 'country', 'created_at']);

        return response()->json([
            'labels' => $labels,
            'clicks' => $clicks,
            'users' => $users,
            'countries' => $countries->keys(),
            'countryClicks' => $countries->values(),
            'topLinks' => $topLinks,
            'recentClicks' => $recentClicks,
            'totals' => [
                'clicks' => $totalClicks,
                'users' => $totalUsers,
                'clickGrowth' => round($clickGrowth, 1),
                'userGrowth' => round($userGrowth, 1),
            ],
        ]);
    }

    public function analytics()
    {
        $topLinks = Link::orderByDesc('clicks')->take(5)->get();

        return view('admin.analytics', compact('topLinks'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings', [
            'user' => auth()->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
