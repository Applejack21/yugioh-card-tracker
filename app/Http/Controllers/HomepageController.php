<?php

namespace App\Http\Controllers;

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
		return Inertia::render('Homepage', [
			'title' => 'Homepage',
		]);
	}
}
