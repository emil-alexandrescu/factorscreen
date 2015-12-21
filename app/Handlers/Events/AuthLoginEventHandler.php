<?php namespace App\Handlers\Events;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\User;
use App\UserUsage;

class AuthLoginEventHandler {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  Events  $event
	 * @return void
	 */
	public function handle(User $user, $remember)
    {
    	$usage = new UserUsage;

    	$usage->type = UserUsage::TYPE_LOGIN;
    	$usage->user_id = $user->id;

    	$usage->save();
    }

}
