<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a specific page.
     *
     * @param string $page
     * @return View
     */
    public function show(string $page): View
    {
        // Security: prevent directory traversal
        $page = str_replace(['/', '\\', '..'], '', $page);
        
        // Check if the page view exists
        if (!view()->exists("pages.{$page}")) {
            abort(404);
        }
        
        return view("pages.{$page}");
    }
}
