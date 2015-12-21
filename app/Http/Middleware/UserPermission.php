<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Route;

class UserPermission {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(!Auth::user() || Auth::user()->type != 1) {
			redirect('home');
		}
		return $next($request);
	}

}
