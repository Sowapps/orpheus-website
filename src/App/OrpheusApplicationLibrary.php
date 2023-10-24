<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace App;

use App\Entity\DemoEntity;
use App\Entity\User;
use App\File\File;
use Orpheus\Core\AbstractOrpheusLibrary;
use Orpheus\EntityDescriptor\Entity\PermanentEntity;
use Orpheus\Initernationalization\TranslationService;
use Orpheus\InputController\ControllerRoute;
use Orpheus\InputController\Exception\ForceResponseException;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\InputController\HttpController\RedirectHttpResponse;
use Orpheus\InputController\InputRequest;
use Orpheus\InputController\Locale\LocaleRouting;

class OrpheusApplicationLibrary extends AbstractOrpheusLibrary {
	
	private string $excludePath = '/admin|/developer|/api';// No locale required for this path
	
	public function start(): void {
		// Entities
		PermanentEntity::registerEntity(DemoEntity::class);
		PermanentEntity::registerEntity(File::class);
		PermanentEntity::registerEntity(User::class);
		
		User::setUserClass();
	}
	
	public function configureMainRequest(InputRequest $request): InputRequest {
		if( $request instanceof HttpRequest ) {
			// *** Get active locale ***
			// Get locale from path
			$localeRouting = new LocaleRouting($request);
			[$locale, $subRequest] = $localeRouting->extractLocaleFromPath();
			// *** Guess locale from request ***
			// Request is now invalid and we will redirect
			if( !$locale ) {
				$locale = $localeRouting->getCookieLocale();
			}
			//			if( !$locale ) {
			//				$locale = $localeRouting->getPreferredLocale();
			//			}
			if( $locale ) {
				$translate = TranslationService::getInstance($locale);
				$translate->setActive();
			}
			if( $subRequest ) {
				$request = $subRequest;
			} else if( $this->isPathRequiringLocale($request->getPath()) ) {
				// No locale and requiring one
				/** @var HttpRoute $route */
				[$route, $values] = $request->findFirstMatchingRoute();
				if( $route ) {
					throw new ForceResponseException('Missing locale in path', new RedirectHttpResponse(u($route->getName(), $values)), $request);
				}// Else invalid url, system will automatically handle the 404
			}
		}
		return $request;
	}
	
	public function isPathRequiringLocale(string $path): bool {
		return preg_match("#^(?!{$this->excludePath})#", $path);
	}
	
	public function formatRoutePath(ControllerRoute &$route, string &$path, array $parameters = []): void {
		$translate = TranslationService::getActive();
		if( $route instanceof HttpRoute && $this->isPathRequiringLocale($path) ) {
			$parameters['locale'] ??= $translate->getHttpLocale();
			$path = '/' . $parameters['locale'] . $path;
		}
	}
	
}
