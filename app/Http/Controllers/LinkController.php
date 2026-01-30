<?php

namespace App\Http\Controllers;

use App\Models\Link;
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
            'original_url' => 'nullable|url',
            'file' => 'nullable|mimes:jpg,jpeg,png,mp4,mov,avi|max:51200', // 50MB
        ]);

        if (! $request->original_url && ! $request->file) {
            return back()->withErrors('Please provide a URL or upload a file');
        }

        do {
            $code = Str::random(6);
        } while (Link::where('short_code', $code)->exists());

        $data = [
            'short_code' => $code,
            'type' => 'url',
        ];

        if ($request->file) {
            $path = $request->file('file')->store('uploads', 'public');
            $data['file_path'] = $path;
            $data['type'] = 'file';
        } else {
            $data['original_url'] = $request->original_url;
        }

        Link::create($data);

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
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('clicks');

        if ($link->type === 'file') {
            return redirect(asset('storage/'.$link->file_path));
        }

        return redirect()->away($link->original_url);
    }
}
