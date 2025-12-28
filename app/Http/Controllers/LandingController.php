<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(): View
    {
        return view('landing');
    }

    /**
     * Store a new waitlist signup.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:waitlist,email'],
        ]);

        Waitlist::create($validated);

        return redirect()->route('landing')
            ->with('success', 'Thank you for joining our waitlist! We\'ll be in touch soon.');
    }
}
