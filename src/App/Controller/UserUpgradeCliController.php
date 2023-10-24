<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace App\Controller;

use App\Entity\User;
use Orpheus\InputController\CliController\CliController;
use Orpheus\InputController\CliController\CliRequest;
use Orpheus\InputController\CliController\CliResponse;

class UserUpgradeCliController extends CliController {
	
	/**
	 * @param CliRequest $request The input CLI request
	 */
	public function run($request): CliResponse {
		$query = User::requestSelect()
			->where('auth_token', '=', '');
		$count = 0;
		foreach( $query as $user ) {
			/** @var User $user */
			$token = User::generateAuthenticationToken();
			$user->update(['auth_token' => $token], ['auth_token']);
			$count++;
		}
		
		return new CliResponse(0, sprintf('All users upgraded (upgraded %d).', $count));
	}
	
	
}
