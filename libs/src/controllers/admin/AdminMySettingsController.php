<?php

use Orpheus\InputController\HTTPController\HTTPRequest;

class AdminMySettingsController extends AdminController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 * @see HTTPController::run()
	 */
	public function run(HTTPRequest $request) {

		/* @var $USER User */
		global $USER, $formData;

		$user	= User::getLoggedUser();
		
		$this->addThisToBreadcrumb();

		try {
			if( $request->hasData('submitUpdate') ) {
				$userInput	= POST('user');
				$userFields	= array('fullname', 'email', 'timezone');
				if( !empty($userInput['password']) ) {
					$userInput['password_conf']	= $userInput['password'];
					$userFields[]	= 'password';
				}
				$result = $user->update($userInput, $userFields);
				if( $result ) {
					reportSuccess(User::text('succesSelfEdit'));
				}
			}
		} catch(UserException $e) {
			reportError($e);
		}
		unset($userInput);
		
		$formData	= array('user'=>$user->all);

		$USER_CAN_USER_EDIT	= !CHECK_MODULE_ACCESS || $USER->canUserEdit();
		
		require_once ORPHEUSPATH.LIBSDIR.'src/admin-form.php';
		
		return $this->renderHTML('app/admin_useredit', array(
			'USER_CAN_USER_EDIT'	=> $USER_CAN_USER_EDIT,
			'USER_CAN_USER_GRANT'	=> false,
			'USER_CAN_USER_DELETE'	=> false,
			'user'	=> $user
		));
	}

}
