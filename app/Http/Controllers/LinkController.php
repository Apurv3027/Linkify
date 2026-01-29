<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = Link::latest()->take(10)->get();

        return view('linkify', compact('links'));
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
            'original_url' => 'required|url',
            'expires_at' => 'nullable|date',
        ]);

        do {
            $code = Str::random(6);
        } while (Link::where('short_code', $code)->exists());

        $expiresAt = null;
        if ($request->expires_at) {
            $expiresAt = Carbon::parse($request->expires_at);

            // Debug log
            \Log::info('Creating link:', [
                'expires_at_input' => $request->expires_at,
                'expires_at_parsed' => $expiresAt,
                'now' => now(),
            ]);
        }

        $link = Link::create([
            'original_url' => $request->original_url,
            'short_code' => $code,
            'expires_at' => $expiresAt,
        ]);

        return redirect('/')
            ->with('shortUrl', url($code));
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
        //
    }

    public function redirect($code)
    {
        $link = Link::withTrashed()->where('short_code', $code)->first();

        if (! $link) {
            abort(404, 'Link not found.');
        }

        // Debug: Log the values
        \Log::info('Link Debug:', [
            'code' => $code,
            'expires_at' => $link->expires_at,
            'expires_at_timestamp' => $link->expires_at ? $link->expires_at->timestamp : null,
            'now' => now(),
            'now_timestamp' => now()->timestamp,
            'is_expired' => $link->expires_at && now()->greaterThanOrEqualTo($link->expires_at),
            'is_trashed' => $link->trashed(),
        ]);

        // If link is soft-deleted
        if ($link->trashed()) {
            abort(410, 'This link has expired or does not exist.');
        }

        // Check expiry
        if ($link->expires_at && now()->greaterThanOrEqualTo($link->expires_at)) {
            $link->delete(); // soft delete
            abort(410, 'This link has expired.');
        }

        $link->increment('clicks');

        return redirect()->away($link->original_url);
    }
}
