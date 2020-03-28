<?php

namespace Demo\Controller\Setup;

use Exception;
use Orpheus\Cache\FSCache;
use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\RedirectHTTPResponse;
use Orpheus\Rendering\HTMLRendering;

abstract class SetupController extends HTTPController {
	
	/** @var array */
	private static $setupData;
	
	/** @var FSCache */
	private static $setupCache;
	
	/** @var string */
	protected static $routeName;
	
	/** @var string */
	private $step;
	
	private static $stepOrder = [
		'StartSetupController',
		'CheckFileSystemSetupController',
		'CheckDatabaseSetupController',
		'InstallDatabaseSetupController',
		'InstallFixturesSetupController',
		'EndSetupController',
	];
	
	/**
	 * @return string
	 * @throws Exception
	 */
	public static function getDefaultRoute() {
		if( !static::$routeName ) {
			throw new Exception('SetupController::getRoute() should be overridden in ' . get_called_class());
		}
		return static::$routeName;
	}
	
	public static function getStepPosition($stepName) {
		return array_search($stepName, self::$stepOrder);
	}
	
	public static function getPreviousStep($stepName) {
		$stepPos = static::getStepPosition($stepName);
		return $stepPos ? self::$stepOrder[$stepPos - 1] : null;
	}
	
	protected static function getAvailableStepTo($target) {
		// Return the last available step to reach this one, or this one if this is available
		$prevStep = static::getPreviousStep($target);
		if( !$prevStep ) {
			// This one is the first or unknown, so we redirect to the first step
			return self::$stepOrder[0];
		} elseif( static::isThisStepValidated($prevStep) ) {
			// The target step is available
			return $target;
		} else {
			return static::getAvailableStepTo($prevStep);
		}
	}
	
	public function preRun($request) {
		parent::preRun($request);
		HTMLRendering::setDefaultTheme('setup');
		
		if( self::$setupData === null ) {
			self::$setupCache = new FSCache('setup', 'data');
			if( !self::$setupCache->get(self::$setupData) ) {
				self::$setupData = (object) ['steps' => []];
			}
		}
		$step = static::getCurrentStepName();
		if( empty(self::$setupData->steps[$step]) ) {
			self::$setupData->steps[$step] = (object) ['init_time' => time()];
			static::saveSetupData();
		}
		$this->step = &self::$setupData->steps[$step];
		$stepClass = static::getStepClass($step);
		
		$availClass = static::getStepClass(static::getAvailableStepTo($step));
		if( $stepClass !== $availClass ) {
			return new RedirectHTTPResponse($availClass::getDefaultRoute());
		}
		
		return null;
	}
	
	private static function getStepClass($step) {
		return __NAMESPACE__ . '\\' . $step;
	}
	
	protected static function getCurrentStepName() {
		return str_replace(__NAMESPACE__ . '\\', '', static::class);
	}
	
	protected function isStepValidated() {
		return isset($this->step->lastvalidate_time);
	}
	
	protected static function isThisStepValidated($stepName) {
		return isset(self::$setupData->steps[$stepName]) && self::$setupData->steps[$stepName]->lastvalidate_time;
	}
	
	protected function validateStep() {
		$this->step->lastvalidate_time = time();
		static::saveSetupData();
	}
	
	protected static function saveSetupData() {
		self::$setupCache->set(self::$setupData);
	}
	
}
