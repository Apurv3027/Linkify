<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! custom_user()) {
            return view('home');
        }

        $userId = custom_user()->id;

        // Selected period (default 7 days)
        $period = request('period', '7');

        // Base click query
        $baseQuery = Click::whereHas('link', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        // Apply period filter
        if ($period == '7') {
            $baseQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($period == '30') {
            $baseQuery->where('created_at', '>=', now()->subDays(30));
        }

        // "all" means no date filter

        //  Basic Stats

        $links = Link::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $totalLinks = Link::where('user_id', $userId)->count();

        $totalFiles = Link::where('user_id', $userId)
            ->where('type', 'file')
            ->count();

        $totalClicks = (clone $baseQuery)->count();

        $uniqueVisitors = (clone $baseQuery)
            ->distinct('ip_address')
            ->count('ip_address');

        // Click Trend Chart
        $labels = [];
        $values = [];

        if ($period == '7' || $period == '30') {

            $days = ($period == '7') ? 6 : 29;

            for ($i = $days; $i >= 0; $i--) {

                $date = now()->subDays($i)->toDateString();

                $labels[] = $date;

                $values[] = Click::whereDate('created_at', $date)
                    ->whereHas('link', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                    ->count();
            }

        } else {

            $allClicks = Click::whereHas('link', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            foreach ($allClicks as $click) {
                $labels[] = $click->date;
                $values[] = $click->total;
            }
        }

        // Country Stats
        $countries = (clone $baseQuery)
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        // Referrer Stats
        $referrers = (clone $baseQuery)
            ->selectRaw("COALESCE(referrer,'Direct') as referrer, COUNT(*) as total")
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->get();

        // Device Stats
        $devices = (clone $baseQuery)
            ->whereNotNull('device')
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        // Browser Stats
        $browsers = (clone $baseQuery)
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->get();

        // Top Values
        $topCountry = $countries->first()->country ?? null;
        $topDevice = $devices->first()->device ?? null;

        return view('dashboard', compact(
            'links',
            'totalLinks',
            'totalFiles',
            'totalClicks',
            'uniqueVisitors',
            'labels',
            'values',
            'countries',
            'referrers',
            'devices',
            'browsers',
            'topCountry',
            'topDevice',
            'period'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'nullable|url',
            'file' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:51200',
        ]);

        if (! $request->filled('original_url') && ! $request->hasFile('file')) {
            return back()->withErrors('Provide a URL or upload a file');
        }

        if ($request->hasFile('file') && ! custom_user()) {
            return redirect('/login')->withErrors('Login required');
        }

        $code = Str::random(6);

        $data = [
            'user_id' => custom_user()?->id,
            'short_code' => $code,
            'clicks' => 0,
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');

            $data['file_path'] = $path;
            $data['type'] = 'file';

            // Track storage usage
            custom_user()?->increment('storage_used', $request->file('file')->getSize());
        } else {
            $data['original_url'] = $request->original_url;
            $data['type'] = 'url';
        }

        Link::create($data);

        return back()->with('shortUrl', url($code));
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        if ($link->user_id !== custom_user()->id) {
            abort(403);
        }

        // Reduce storage usage (safe for cloud)
        if ($link->type === 'file' && $link->file_path) {
            $size = Storage::size($link->file_path);
            custom_user()?->decrement('storage_used', $size);

            // Optional: actually delete file
            // Storage::delete($link->file_path);
        }

        $link->delete();

        return back()->with('success', 'Link deleted successfully');
    }

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('clicks');

        try {

            $agent = new Agent;

            $ip = request()->ip();
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                $ip = '8.8.8.8';
            }

            $country = 'Unknown';

            $response = \Illuminate\Support\Facades\Http::timeout(2)
                ->get("http://ip-api.com/json/{$ip}");

            if ($response->ok() && $response['status'] === 'success') {
                $country = $response['country'] ?? 'Unknown';
            }

            // Store click record
            Click::create([
                'link_id' => $link->id,
                'ip_address' => $ip,
                'referrer' => request()->headers->get('referer') ?? 'Direct',
                'device' => $agent->device() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
                'country' => $country,
            ]);

        } catch (\Exception $e) {
            \Log::error('Analytics Error: '.$e->getMessage());
        }

        if ($link->type === 'file') {
            $extension = strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION));
            $fileUrl = Storage::url($link->file_path);

            // If AJAX → return JSON for modal preview
            if (request()->expectsJson()) {

                return response()->json([
                    'type' => in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])
                                ? 'image'
                                : 'video',
                    'url' => $fileUrl,
                    'views' => $link->clicks,
                    'downloads' => $link->downloads ?? 0,
                    'downloadUrl' => route('file.download', $code),
                ]);
            }

            // If normal browser request → open media directly
            return redirect($fileUrl);
        }

        // return response()->json([
        //     'redirect' => $link->original_url,
        // ]);

        if (request()->expectsJson()) {
            return response()->json([
                'redirect' => $link->original_url,
            ]);
        }

        return redirect()->away($link->original_url);

    }

    public function download($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('downloads');

        return Storage::download($link->file_path);
    }

    public function analyticsData(Request $request)
    {
        $userId = custom_user()->id;
        $period = $request->period ?? '7';

        $baseQuery = Click::whereHas('link', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($period == '7') {
            $baseQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($period == '30') {
            $baseQuery->where('created_at', '>=', now()->subDays(30));
        }

        // Trend data
        $labels = [];
        $values = [];

        $days = ($period == '30') ? 29 : 6;

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = $date;

            $values[] = Click::whereDate('created_at', $date)
                ->whereHas('link', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->count();
        }

        $countries = (clone $baseQuery)
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->get();

        $devices = (clone $baseQuery)
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')
            ->get();

        $referrers = (clone $baseQuery)
            ->selectRaw("COALESCE(referrer,'Direct') as referrer, COUNT(*) as total")
            ->groupBy('referrer')
            ->get();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'totalClicks' => $baseQuery->count(),
            'uniqueVisitors' => $baseQuery->distinct('ip_address')->count('ip_address'),
            'countries' => $countries,
            'devices' => $devices,
            'referrers' => $referrers,
        ]);
    }
}
