<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Paginate links for table
        $links = Link::where('user_id', $userId)->latest()->paginate(10);

        // Stats
        $totalLinks = Link::where('user_id', $userId)->count();
        $totalClicks = Link::where('user_id', $userId)->sum('clicks');
        $totalFiles = Link::where('user_id', $userId)->whereIn('type', ['file', 'image', 'video'])->count();

        // Prepare clicks over time chart (last 7 days)
        $last7Days = now()->subDays(6)->startOfDay();
        $clicksData = Link::where('user_id', $userId)
            ->where('created_at', '>=', $last7Days)
            ->get()
            ->groupBy(function ($link) {
                return $link->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->sum('clicks');
            });

        $chartLabels = [];
        $chartValues = [];

        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays(6 - $i)->format('Y-m-d');
            $chartLabels[] = $date;
            $chartValues[] = $clicksData[$date] ?? 0;
        }

        // Recent activity
        $recentLinks = Link::where('user_id', $userId)->latest()->take(5)->get();

        return view('dashboard', compact(
            'links', 'totalLinks', 'totalClicks', 'totalFiles',
            'chartLabels', 'chartValues', 'recentLinks'
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
            'file' => 'nullable|mimes:jpg,jpeg,png,mp4,mov,avi|max:51200',
        ]);

        if (! $request->filled('original_url') && ! $request->hasFile('file')) {
            return back()->withErrors('Provide URL or file');
        }

        if ($request->hasFile('file') && ! custom_user()) {
            return redirect('/login')->withErrors('Login required');
        }

        $code = Str::random(6);

        // dd(custom_user());
        // dd(session('user_id'));

        if (custom_user()) {

            $data = [
                'user_id' => custom_user()->id,
                'short_code' => $code,
                'type' => 'url',
                'clicks' => 0,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads', 'public');
                $size = $file->getSize(); // bytes

                $data['file_path'] = $path;
                $data['type'] = 'file';

                // 📦 Track storage usage
                custom_user()->increment('storage_used', $size);
            } else {
                $data['original_url'] = $request->original_url;
            }

            // dd($data);

            Link::create($data);
        } else {
            session()->push('guest_links', [
                'short_code' => $code,
                'original_url' => $request->original_url,
                'clicks' => 0,
            ]);
        }

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
        // Security check
        if ($link->user_id !== custom_user()->id) {
            abort(403);
        }

        // ✅ Only file links affect storage
        if ($link->type === 'file' && $link->file_path && $link->user_id) {

            $fullPath = storage_path('app/public/'.$link->file_path);

            if (file_exists($fullPath)) {
                $fileSize = filesize($fullPath); // bytes

                // 📉 Reduce user storage
                $user = custom_user();
                if ($user) {
                    $user->decrement('storage_used', $fileSize);
                }

                // ❌ Remove physical file
                // unlink($fullPath);
            }
        }

        $link->delete();

        return back()->with('success', 'Link deleted successfully');
    }

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        // 📈 Increment click count
        $link->increment('clicks');

        // 📁 FILE → PREVIEW PAGE
        if ($link->type === 'file') {
            return redirect()->route('file.preview', $code);
        }

        // 🌐 URL → Redirect
        return redirect()->away($link->original_url);
    }

    public function preview($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        $extension = strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
        $isVideo = in_array($extension, ['mp4', 'mov', 'avi']);

        return view('file.preview', compact('link', 'isImage', 'isVideo'));
    }

    public function download($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        $link->increment('downloads');

        return response()->download(storage_path('app/public/'.$link->file_path));
    }
}
