<?php

use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\Exception\NotFoundException;

class DevLogViewController extends DevController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 * @see HTTPController::run()
	 */
	public function run(HTTPRequest $request) {
		
		$file = $request->getParameter('file');
		if( !$file || !is_readable(LOGSPATH.$file) ) {
			throw new NotFoundException('Invalid log file');
		}
		
		return $this->renderHTML('app/dev_logfile', array(
			'file' => LOGSPATH.$file
		));
	}

}
