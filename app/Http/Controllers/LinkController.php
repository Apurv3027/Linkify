<?php

namespace App\Http\Controllers;

use App\Models\Click;
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

        $links = Link::where('user_id', $userId)->latest()->paginate(10);

        $totalLinks = Link::where('user_id', $userId)->count();
        $totalClicks = Link::where('user_id', $userId)->sum('clicks');
        $totalFiles = Link::where('user_id', $userId)
            ->where('type', 'file')
            ->count();

        // Click chart (last 7 days)
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            // $date = now()->subDays($i)->format('Y-m-d');
            $date = now()->subDays($i)->toDateString();

            $labels[] = $date;
            // $values[] = Link::where('user_id', $userId)
            //     ->whereDate('created_at', $date)
            //     ->sum('clicks');

            $values[] = Click::whereDate('created_at', $date)
                ->whereHas('link', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->count();
        }

        return view('dashboard', compact(
            'links',
            'totalLinks',
            'totalClicks',
            'totalFiles',
            'labels',
            'values'
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

        // Store click record
        Click::create([
            'link_id' => $link->id,
        ]);

        if ($link->type === 'file') {
            $extension = strtolower(pathinfo($link->file_path, PATHINFO_EXTENSION));

            return response()->json([
                'type' => in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'video',
                'url' => Storage::url($link->file_path),
                'views' => $link->clicks,
                'downloads' => $link->downloads ?? 0,
                'downloadUrl' => route('file.download', $code),
            ]);
        }

        return response()->json([
            'redirect' => $link->original_url,
        ]);
    }

    public function download($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('downloads');

        return Storage::download($link->file_path);
    }

    public function clickAnalytics()
    {
        $userId = custom_user()->id;

        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = $date;

            $values[] = Click::whereDate('created_at', $date)
                ->whereHas('link', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->count();
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
