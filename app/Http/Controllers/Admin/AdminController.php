<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;

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

        $totalUsers = User::count();
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

    public function analytics()
    {
        $clicks = Click::selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics', compact('clicks'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
