<?php
/**
 * @var HtmlRendering $rendering
 * @var HttpController $controller
 * @var HttpRequest $request
 * @var HttpRoute $route
 */

use Orpheus\InputController\HttpController\HttpController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\Rendering\HtmlRendering;

$rendering->useLayout('layout.admin');
$rendering->addJsFile('ModelList.js', HtmlRendering::LINK_TYPE_CUSTOM);
$rendering->addThemeJsFile('test/test_api.js', HtmlRendering::LINK_TYPE_CUSTOM);
?>

<div class="row">
	<div class="col-12 col-lg-6">
		<?php $rendering->useLayout('component/panel'); ?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead>
				<tr>
					<th><?php echo t('property.id', DOMAIN_TEST); ?></th>
					<th><?php echo t('property.name', DOMAIN_TEST); ?> </th>
					<th><?php echo t('property.create_date', DOMAIN_TEST); ?> </th>
					<th><?php echo t('actionsColumn'); ?> </th>
				</tr>
				</thead>
				<tbody id="ListDemoEntity">
				</tbody>
			</table>
			<template id="TemplateDemoEntityItem">
				<tr>
					<th scope="row">{id}</th>
					<td>{name}</td>
					<td>{createDateText}</td>
					<td>
						<div class="btn-group btn-group-sm" role="group" aria-label="<?php echo t('actionsColumn'); ?>">
							<button type="button" class="btn btn-outline-primary" data-action="open-entity" data-item-id="{id}">
								<i class="fa fa-edit fa-fw"></i>
							</button>
							<button type="button" class="btn btn-outline-warning" data-action="remove-entity" data-toggle="confirm" data-item-id="{id}"
									data-confirm-title="<?php echo t('remove.title', DOMAIN_TEST); ?>"
									data-confirm-message="<?php echo t('remove.legend', DOMAIN_TEST); ?>"
									data-confirm-submit="button-event"
							>
								<i class="fa fa-times fa-fw"></i>
							</button>
						</div>
					</td>
				</tr>
			</template>
			<template id="TemplateDemoEntityPlaceholder">
				<tr>
					<td colspan="99"><?php echo t('list.empty', DOMAIN_TEST); ?></td>
				</tr>
			</template>
		</div>
		<?php $rendering->endCurrentLayout([
			'title' => t('list.title', DOMAIN_TEST),
		]); ?>
	</div>
	<div class="col-12 col-lg-6">
		<form data-action="create" class="form-demo-entity needs-validation" novalidate>
			<?php $rendering->useLayout('component/panel'); ?>
			<div class="mb-3">
				<label for="InputDemoEntityCreateName"><?php echo t('property.name', DOMAIN_TEST); ?></label>
				<input id="InputDemoEntityCreateName" class="form-control" type="text" name="name" required>
				<div class="invalid-feedback">
					The name is required.
				</div>
			</div>
			<?php $rendering->startNewBlock('footer'); ?>
			<button type="button" class="btn btn-outline-secondary" data-action="cancel"><?php echo t('cancel'); ?></button>
			<button type="submit" class="btn btn-primary"><?php echo t('add'); ?></button>
			<?php $rendering->endCurrentLayout([
				'title' => t('create.title', DOMAIN_TEST),
			]); ?>
		</form>
		<form data-action="update" class="form-demo-entity needs-validation" novalidate id="FormDemoEntityUpdate" hidden>
			<?php $rendering->useLayout('component/panel'); ?>
			<div class="mb-3">
				<label for="InputDemoEntityUpdateName"><?php echo t('property.name', DOMAIN_TEST); ?></label>
				<input id="InputDemoEntityUpdateName" class="form-control" type="text" name="name" required>
				<div class="invalid-feedback">
					The name is required.
				</div>
			</div>
			<div class="mb-3">
				<label for="InputDemoEntityUpdateCreateDate"><?php echo t('property.create_date', DOMAIN_TEST); ?></label>
				<input id="InputDemoEntityUpdateCreateDate" class="form-control" type="text" name="createDateText" disabled>
			</div>
			<?php $rendering->startNewBlock('footer'); ?>
			<button type="button" class="btn btn-outline-secondary" data-action="cancel"><?php echo t('cancel'); ?></button>
			<button type="submit" class="btn btn-primary"><?php echo t('save'); ?></button>
			<?php $rendering->endCurrentLayout([
				'title' => t('update.title', DOMAIN_TEST),
			]); ?>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-12 col-lg-6">
		<form id="FormUserInformation">
			<?php $rendering->useLayout('component/panel'); ?>
			<div class="mb-3">
				<label for="InputUserName"><?php echo t('user.tokenLabel', DOMAIN_TEST); ?></label>
				<input id="InputUserName" class="form-control" type="text" name="token" placeholder="<?php echo t('user.tokenPlaceholder', DOMAIN_TEST); ?>">
			</div>
			<div id="AlertUserInformationError" class="alert alert-danger" role="alert" hidden></div>
			<div id="AlertUserInformationLoading" class="alert alert-info text-center" role="alert" hidden>
				<i class="fa-solid fa-spinner fa-spin-pulse me-1"></i>
				<?php echo t('loading'); ?>
			</div>
			<fieldset disabled id="FieldsetUser" hidden>
				<div class="mb-3">
					<label for="InputUserName"><?php echo t('property.id', DOMAIN_TEST); ?></label>
					<input id="InputUserName" class="form-control" type="text" name="id" disabled>
				</div>
				<div class="mb-3">
					<label for="InputUserName"><?php echo t('name', DOMAIN_USER); ?></label>
					<input id="InputUserName" class="form-control" type="text" name="fullname" disabled>
				</div>
				<div class="mb-3">
					<label for="InputUserEmail"><?php echo t('email', DOMAIN_USER); ?></label>
					<input id="InputUserEmail" class="form-control" type="text" name="email" disabled>
				</div>
			</fieldset>
			<?php $rendering->startNewBlock('footer'); ?>
			<button type="button" class="btn btn-outline-secondary" data-action="cancel"><?php echo t('cancel'); ?></button>
			<button type="submit" class="btn btn-primary"><?php echo t('user.action', DOMAIN_TEST); ?></button>
			<?php $rendering->endCurrentLayout([
				'title'      => t('user.title', DOMAIN_TEST),
				'panelClass' => 'panel-user',
			]); ?>
		</form>
	</div>
</div>
