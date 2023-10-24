<?php
/**
 * @var HtmlRendering $rendering
 * @var HttpController $controller
 * @var HttpRequest $request
 * @var HttpRoute $route
 */

use App\Entity\User;
use Orpheus\InputController\HttpController\HttpController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\Rendering\HtmlRendering;

$user = User::getActiveUser();
$rendering->useLayout('layout.public');
$gettingStartedUrl = u('doc_getting_started');

?>

<div class="container py-4">
	<div class="p-5 mb-4 bg-light border rounded-3">
		<div class="p-3">
			<h1 class="display-5 fw-bold mb-3">
				<?php echo t('home.introduction.title', DOMAIN_APP); ?>
			</h1>
			<p class="col-md-10 fs-4">
				<?php echo html(t('home.introduction.legend', DOMAIN_APP)); ?>
			</p>
			<div class="mt-5 text-center">
				<a class="btn btn-info text-white btn-lg" href="<?php echo $gettingStartedUrl; ?>">
					<?php echo html(t('home.introduction.startAction', DOMAIN_APP)); ?>
					<i class="fa-solid fa-play fa-sm ms-2"></i>
				</a>
			</div>
		</div>
	</div>
	
	<?php
	if( $user ) {
		?>
		<div class="alert alert-info" role="alert">
			<h3>
				<?php echo html(t('home.authenticated.title', DOMAIN_APP)); ?>
			</h3>
			<p>
				<?php echo nl2br(t('home.authenticated.legend', DOMAIN_APP, [
					'user'        => $user,
					'link_member' => '<a href="' . u('admin_demo') . '">',
					'link_end'    => '</a>',
				])); ?>
			</p>
		</div>
		<?php
	}
	?>
	
	<div class="row mb-4 gap-4 gap-lg-0">
		<div class="col-lg-4">
			<div class="d-flex flex-column h-100 px-5 py-5 bg-light border rounded-3">
				<div class="text-center display-1 mb-4">
					<i class="fa-brands fa-php fa-2x"></i>
				</div>
				<h2>
					<?php echo t('home.projectPhp.title', DOMAIN_APP); ?>
				</h2>
				<p class="mb-5">
					<?php echo t('home.projectPhp.legend', DOMAIN_APP); ?>
				</p>
				<div class="mt-auto text-center">
					<a class="btn btn-info text-white" href="https://www.php.net/" role="button" target="_blank">
						<?php echo t('home.projectPhp.action', DOMAIN_APP); ?>
						<i class="fas fa-angle-double-right fa-xs ms-1"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="d-flex flex-column h-100 px-5 py-5 text-white bg-info rounded-3">
				<div class="text-center display-1 mb-4">
					<i class="fa-brands fa-bootstrap fa-2x"></i>
				</div>
				<h2>
					<?php echo t('home.projectBootstrap.title', DOMAIN_APP); ?>
				</h2>
				<p class="mb-5">
					<?php echo t('home.projectBootstrap.legend', DOMAIN_APP); ?>
				</p>
				<div class="mt-auto text-center">
					<a class="btn btn-light text-info" href="https://getbootstrap.com/" role="button" target="_blank">
						<?php echo t('home.projectBootstrap.action', DOMAIN_APP); ?>
						<i class="fas fa-angle-double-right fa-xs ms-1"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="d-flex flex-column h-100 px-5 py-5 bg-light border rounded-3">
				<div class="text-center display-1 mb-4">
					<i class="fa-solid fa-font-awesome fa-2x"></i>
				</div>
				<h2>
					<?php echo t('home.projectFontAwesome.title', DOMAIN_APP); ?>
				</h2>
				<p class="mb-5">
					<?php echo t('home.projectFontAwesome.legend', DOMAIN_APP); ?>
				</p>
				<div class="mt-auto text-center">
					<a class="btn btn-info text-white" href="https://fontawesome.com/icons?d=gallery&m=free" role="button" target="_blank">
						<?php echo t('home.projectFontAwesome.action', DOMAIN_APP); ?>
						<i class="fas fa-angle-double-right fa-xs ms-1"></i>
					</a>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
$rendering->display('component/section.cloud', [
	'icon'        => 'fa-solid fa-trowel-bricks',
	'title'       => t('home.advantages.extensible.title', DOMAIN_APP),
	'legend'      => t('home.advantages.extensible.legend', DOMAIN_APP),
	'description' => t('home.advantages.extensible.description', DOMAIN_APP),
]);
?>

<div class="bg-light py-5 my-5">
	<div class="container my-5">
		
		<div class="row align-items-center">
			<div class="col-12 col-lg-6">
				
				<div class="h-100 d-flex flex-column justify-content-between fs-1">
					<div class="d-flex justify-content-center py-3">
						<i class="fa-regular fa-2x px-5 fa-paper-plane text-primary"></i>
					</div>
					<div class="d-flex justify-content-around py-4">
						<i class="fa-regular fa-2x px-5 fa-chess-queen text-warning"></i>
						<i class="fa-regular fa-2x px-5 fa-hourglass-half text-danger"></i>
					</div>
					<div class="d-flex justify-content-center py-4">
						<i class="fa-regular fa-2x px-5 fa-hand-back-fist text-success"></i>
						<i class="fa-regular fa-2x px-5 fa-gem text-info"></i>
					</div>
				</div>
			
			</div>
			<div class="col-12 col-lg-6 px-5 px-md-0">
				<h2 class="mb-3">
					<?php echo t('home.deploy.title', DOMAIN_APP); ?>
				</h2>
				<p>
					<?php echo t('home.deploy.legend', DOMAIN_APP); ?>
				</p>
				<div class="d-flex align-items-top mt-4">
					<i class="fa-regular fa-chess-rook text-warning me-4 fs-1"></i>
					<div>
						<h3 class="h5">
							<?php echo t('home.deploy.continuousIntegration.title', DOMAIN_APP); ?>
						</h3>
						<p>
							<?php echo t('home.deploy.continuousIntegration.legend', DOMAIN_APP); ?>
						</p>
					</div>
				</div>
				<div class="d-flex align-items-top mt-4">
					<i class="fa-regular fa-chart-bar text-primary me-4 fs-1"></i>
					<div>
						<h3 class="h5">
							<?php echo t('home.deploy.returnOnInvestment.title', DOMAIN_APP); ?>
						</h3>
						<p>
							<?php echo t('home.deploy.returnOnInvestment.legend', DOMAIN_APP); ?>
						</p>
					</div>
				</div>
				<div class="mt-4 text-center text-md-start">
					<a class="btn btn-info text-white btn-lg" href="<?php echo $gettingStartedUrl; ?>" target="_blank">
						<?php echo t('home.deploy.action', DOMAIN_APP); ?>
					</a>
				</div>
			</div>
		</div>
	
	</div>
</div>

<?php
$rendering->display('component/section.cloud', [
	'icon'        => 'fa-solid fa-shield',
	'title'       => t('home.advantages.safety.title', DOMAIN_APP),
	'legend'      => t('home.advantages.safety.legend', DOMAIN_APP),
	'description' => t('home.advantages.safety.description', DOMAIN_APP),
]);
?>

<div class="container py-4">
	
	<div class="row gap-5 py-5 mb-4 bg-light justify-content-center border-start border-info border-5">
		<div class="col-10 col-md-3 d-flex flex-column justify-content-center order-first order-md-last">
			<div class="text-center display-1 text-info">
				<i class="fa-solid fa-sun"></i>
			</div>
		</div>
		<div class="col-10 col-md-7">
			<h2>
				<?php echo t('home.features.rendering.title', DOMAIN_APP); ?>
			</h2>
			<p>
				<?php echo t('home.features.rendering.legend', DOMAIN_APP); ?>
			</p>
		</div>
	</div>
	<div class="row gap-5 py-5 mb-5 bg-light justify-content-center align-items-centers border-end border-info border-5">
		<div class="col-10 col-md-3 d-flex flex-column justify-content-center">
			<div class="text-center display-1 text-info">
				<i class="fa-solid fa-font"></i>
			</div>
		</div>
		<div class="col-10 col-md-7">
			<h2>
				<?php echo t('home.features.internationalization.title', DOMAIN_APP); ?>
			</h2>
			<p>
				<?php echo t('home.features.internationalization.legend', DOMAIN_APP); ?>
			</p>
		</div>
	</div>
	<div class="row gap-5 py-5 mb-5 bg-light justify-content-center border-start border-info border-5">
		<div class="col-10 col-md-3 d-flex flex-column justify-content-center order-first order-md-last">
			<div class="text-center display-1 text-info">
				<i class="fa-solid fa-bolt"></i>
			</div>
		</div>
		<div class="col-10 col-md-7">
			<h2>
				<?php echo t('home.features.caching.title', DOMAIN_APP); ?>
			</h2>
			<p>
				<?php echo t('home.features.caching.legend', DOMAIN_APP); ?>
			</p>
		</div>
	</div>

</div>

<?php
$rendering->display('component/section.cloud', [
	'icon'        => 'fa-solid fa-database',
	'title'       => t('home.advantages.database.title', DOMAIN_APP),
	'legend'      => t('home.advantages.database.legend', DOMAIN_APP),
	'description' => t('home.advantages.database.description', DOMAIN_APP),
]);
?>

<div class="container py-4">
	
	<h2 class="text-center">
		<?php echo t('home.history.title', DOMAIN_APP); ?>
	</h2>
	<div class="mt-4 mt-md-0">
		<div>
			<i class="fa-solid fa-quote-left fa-3x"></i>
		</div>
		<div class="bg-light rounded-3 p-4 ms-4">
			<p class="lead m-0 text-center">
				<?php echo html(t('home.history.legend', DOMAIN_APP)); ?>
			</p>
		</div>
	</div>

</div>

<style>
	.title-cloud {
		font-family: Arial, sans-serif;
		line-height: 69%;
	}
</style>
