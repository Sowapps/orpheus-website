<?php

use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTMLHTTPResponse;
use Orpheus\InputController\HTTPController\RedirectHTTPResponse;
use Orpheus\Config\AppConfig;

class DownloadController extends HTTPController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 * @see HTTPController::run()
	 */
	public function run(HTTPRequest $request) {
		$downloadURL = AppConfig::instance()->get($request->hasParameter('releases') ? 'releases_url' : 'download_url');
		if( $downloadURL ) {
			return new RedirectHTTPResponse($downloadURL);
		}

		return HTMLHTTPResponse::render('app/home');
	}
}
