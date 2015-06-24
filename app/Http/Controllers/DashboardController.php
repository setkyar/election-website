<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Application;

class DashboardController extends Controller
{

	protected $user;

	public function __construct()
	{
		$this->user = Auth::user();
	}

	public function index()
	{
		if ( $this->user->isAdmin())
		{
			return $this->dashboardForAdmin();
		}

		return $this->dashboardForUser();
	}

	protected function dashboardForAdmin()
	{
		$data['user'] = $this->user;

		$data['users'] = User::latest()->take(10)->get();

		$data['applications'] = Application::latest()->take(10)->get();

		return view('dashboard.admin', $data);
	}

	protected function dashboardForUser()
	{
		$data['user'] = $this->user;
		
		return view('dashboard.user', $data);
	}
}