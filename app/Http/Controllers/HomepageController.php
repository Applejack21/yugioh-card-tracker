<?php

namespace App\Http\Controllers;

use App\Jobs\BatchImportCards;
use App\Jobs\ImportCardSets;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
    /**
     * Display the homepage to the user.
     *
     * @param  Request  $request  Any request data.
     */
    public function index(Request $request): Response
    {
        // ImportCardSets::dispatch();
        // BatchImportCards::dispatch();
        return Inertia::render('Homepage', [
            'title' => 'Homepage',
        ]);
    }
}
