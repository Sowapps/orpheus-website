<?php

namespace App\Controller\Test;

use App\Controller\Developer\DevController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpResponse;

class DevTestApiController extends DevController {
	
	/**
	 * @param HttpRequest $request The input HTTP request
	 * @return HttpResponse The output HTTP response
	 */
	public function run($request): HttpResponse {
		
		$this->addThisToBreadcrumb();
		
		return $this->renderHtml('test/test_api');
	}
	
}
