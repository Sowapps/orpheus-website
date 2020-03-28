<?php

use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;

/**
 * @var string $CONTROLLER_OUTPUT
 * @var HTMLRendering $rendering
 * @var HTTPController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 *
 * @var bool $allowContinue
 * @var bool $wasAlreadyDone
 */

$rendering->useLayout('page_skeleton');

?>
<form method="POST">
	<div class="row">
		
		<div class="col-lg-8 offset-lg-2">
			
			<h1><?php _t('installfixtures_title', DOMAIN_SETUP, t('app_name')); ?></h1>
			<p class="lead"><?php echo text2HTML(t('installfixtures_description', DOMAIN_SETUP, ['APP_NAME' => t('app_name')])); ?></p>
			
			<?php
			$this->display('reports');
			?>
			<p>
				<button type="submit" class="btn btn-lg <?php echo $wasAlreadyDone ? 'btn-outline-secondary' : 'btn-primary' ?>" name="submitInstallFixtures">
					<?php _t('install_fixtures', DOMAIN_SETUP); ?>
				</button>
				<?php
				if( $allowContinue ) {
					?>
					<a class="btn btn-lg btn-primary" href="<?php _u('setup_end'); ?>" role="button"><?php _t('continue', DOMAIN_SETUP); ?></a>
					<?php
				}
				?>
			</p>
		
		</div>
	
	</div>
</form>
