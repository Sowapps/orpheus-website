<?php
// <a href="./">Basic demo</a><br />
// <br />
// $formregister = array();
$FORM_TOKEN	= new FormToken();

try {
	isPOST() && $FORM_TOKEN->validateForm();
	if( isPOST('submitLogin') ) {
		User::userLogin($_POST['login']);
		reportSuccess('You\'re successfully logged in.');
	} else if( isPOST('submitRegister') ) {
// 		$formregister = POST('register');
		$user	= User::createAndGet(POST('register'), array('name', 'fullname', 'email', 'email_public', 'password'));
		sendAdminRegistrationEmail($user);
		unset($user);
		reportSuccess('You\'re successfully registered.');
	}
} catch(UserException $e) {
	reportError($e);
}
displayReportsHTML();

if( User::is_login() ) {
	echo "<p>Welcome {$USER->fullname} </p>";
}
?>
<div class="row">
	
	<div class="col-xs-6">
		<form method="POST" role="form"><?php echo $FORM_TOKEN; ?>
		<fieldset>
			<legend>Sign in</legend>
			<div class="form-group">
				<label for="loginUsername">Nickname</label>
				<input type="text" class="form-control" id="loginUsername" name="login[name]" placeholder="Enter your username"/>
			</div>
			<div class="form-group">
				<label for="loginPassword">Password</label>
				<input type="password" class="form-control" id="loginPassword" name="login[password]" placeholder="Enter your password"/>
			</div>
			<button name="submitLogin" type="submit" class="btn btn-primary">Sign in</button>
		</fieldset>
		</form>
	</div>
		
	<div class="col-xs-6">
		<form method="POST" role="form"><?php echo $FORM_TOKEN; ?>
		<fieldset>
			<legend>Register</legend>
			
			<div class="form-group">
				<label for="registerUsername">Your nickname</label>
				<input type="text" class="form-control" id="registerUsername" name="register[name]" required placeholder="My ID to sign in, e.g. cartman"/>
			</div>
			<div class="form-group">
				<label for="registerFullname">Your public name</label>
				<input type="text" class="form-control" id="registerFullname" name="register[fullname]" required placeholder="The displayed name, e.g. Eric Cartman" />
			</div>
			<div class="form-group">
				<label for="registerEmail">Email</label>
				<input type="email" class="form-control" id="registerEmail" name="register[email]" required placeholder="your_name@domain.com"/>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="register[email_public]"> I allow other members to see my email address.
				</label>
			</div>
			<div class="form-group">
				<label for="registerPassword">Your password</label>
				<input type="password" class="form-control" id="registerPassword" name="register[password]" required />
			</div>
			<div class="form-group">
				<label for="registerConfirmPassword">Confirm password</label>
				<input type="password" class="form-control" id="registerConfirmPassword" name="register[password_conf]" required />
			</div>
			<button name="submitRegister" type="submit" class="btn btn-primary">Register</button>
		</fieldset>
		</form>
	</div>
</div>