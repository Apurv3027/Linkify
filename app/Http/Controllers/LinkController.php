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
        if (custom_user()) {
            $links = Link::where('user_id', custom_user()->id)->latest()->get();

            return view('dashboard', compact('links'));
        }

        return view('home');
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

        if (! $request->original_url && ! $request->file) {
            return back()->withErrors('Provide URL or file');
        }

        if ($request->file && ! custom_user()) {
            return redirect('/login')->withErrors('Login required');
        }

        $code = Str::random(6);

        // dd(custom_user());
        // dd(session('user_id'));

        if (custom_user()) {

            $data = [
                'user_id' => session('user_id'),
                'short_code' => $code,
                'type' => 'url',
            ];

            if ($request->file) {
                $data['file_path'] = $request->file->store('uploads', 'public');
                $data['type'] = 'file';
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

        $link->delete();

        return back()->with('success', 'Link deleted successfully');
    }

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->first();

        if ($link) {
            $link->increment('clicks');

            return $link->type === 'file'
                ? redirect(asset('storage/'.$link->file_path))
                : redirect()->away($link->original_url);
        }

        // SESSION LINKS
        $guestLinks = session('guest_links', []);
        foreach ($guestLinks as &$g) {
            if ($g['short_code'] === $code) {
                $g['clicks']++;
                session(['guest_links' => $guestLinks]);

                return redirect()->away($g['original_url']);
            }
        }

        abort(404);
    }
}
