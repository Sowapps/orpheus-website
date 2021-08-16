<?php

use Demo\User;
use Orpheus\Email\Email;
use Orpheus\EntityDescriptor\PermanentEntity;

/**
 * PHP File for the website sources
 * It's your app's library.
 *
 * Author: Your name.
 */

defifn('DOMAIN_SETUP', 'setup');

// Entities
PermanentEntity::registerEntity('\Demo\File\File');
PermanentEntity::registerEntity('\Demo\User');
PermanentEntity::registerEntity('\Demo\DemoEntity');

/**
 * @param User $user
 */
function sendAdminRegistrationEmail($user) {
	$SITENAME = t('app_name');
	$SITEURL = DEFAULTLINK;
	$e = new Email('Orpheus - Registration of ' . $user->getLabel());
	$e->setText(<<<BODY
Hi master !

A new dude just registered on <a href="{$SITEURL}">{$SITENAME}</a>, he is named {$user} ({$user->name}) with email {$user->email}.

Your humble servant, {$SITENAME}.
BODY
	);
	
	return $e->send(ADMINEMAIL);
}

/**
 * @param ThreadMessage $tm
 */
function sendNewThreadMessageEmail($tm) {
	$SITENAME = t('app_name');
	$e = new Email('Orpheus - New message of ' . $tm->user_name);
	$e->setText(<<<BODY
Hi master !

{$tm->getUser()} posted a new thread message:
{$tm}

Your humble servant, {$SITENAME}.
BODY
	);
	return $e->send(ADMINEMAIL);
}

function includeHTMLAdminFeatures() {
	require_once ORPHEUSPATH . 'src/admin-form.php';
}

function generateUniqueId() {
	static $id = 0;
	$id++;
	return $id;
}

function renderReadonlyInputHtml($label, $value) {
	$id = 'Input' . generateUniqueId();
	?>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="<?php echo $id; ?>">
			<?php echo $label; ?>
		</label>
		<div class="col-sm-9">
			<input type="text" readonly class="form-control-plaintext" id="<?php echo $id; ?>" value="<?php echo $value; ?>">
		</div>
	</div>
	<?php
}
