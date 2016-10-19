<?php

use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\Rendering\HTMLRendering;

abstract class AdminController extends HTTPController {
	
	protected $breadcrumb = array();

	public function addBreadcrumb($label, $link=null) {
		$this->breadcrumb[]	= (object)array('label' => $label, 'link' => $link);
	}

	public function addRouteToBreadcrumb($route, $label=null, $link=true) {
		if( $link ) {
			if( typeOf($link) === 'string' ) {
				$link = $link;
			} else {
				$link = u($route, is_array($link) ? $link : array());
			}
		} else {
			$link = null;
		}
		$this->addBreadcrumb($label ? $label : t($route), $link);
	}

	public function addThisToBreadcrumb($label=null, $link=false) {
		$link = $this->getRequest()->getParameters();
		$this->addRouteToBreadcrumb($this->getRouteName(), $label, $link);
	}
	
	public function preRun(HTTPRequest $request) {
		parent::preRun($request);
		HTMLRendering::setDefaultTheme('admin');
		
		$this->addRouteToBreadcrumb(DEFAULTROUTE);
// 		$this->addBreadcrumb(t('home'), u(DEFAULTMEMBERROUTE));
		$this->addRouteToBreadcrumb(ROUTE_ADM_HOME);
// 		if( DEFAULTMEMBERROUTE !== $this->getRouteName() ) {
// 			$this->addRouteToBreadcrumb(DEFAULTMEMBERROUTE);
// 		}
		
		/* @var $USER User */
// 		if( CHECK_MODULE_ACCESS ) {
// 			global $USER;
// 			if( !$USER || !$USER->canAccess($request->getRouteName()) ) {
// 				throw new ForbiddenException('forbiddenAccessToRoute');
// 			}
// 		}
	}
	
	public function render($response, $layout, $values=array()) {
		if( isset($GLOBALS['USER']) ) {
			$values['USER']	= $GLOBALS['USER'];
		}
		$values['Breadcrumb'] = $this->breadcrumb;
		return parent::render($response, $layout, $values);
	}

}
