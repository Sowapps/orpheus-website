<?php
/**
 * @file Orpheus/loader.php
 * @brief The Orpheus Loader
 * @author Florent Hazard
 * @copyright The MIT License, see LICENSE.txt
 * 
 * PHP File for the website core.
 */

if( !isset($SRCPATHS) ) {
	$SRCPATHS = array();
}

define('DEV_LEVEL',			E_ALL | E_STRICT);//Development
define('PROD_LEVEL',		0);//Production

/** Defines an undefined constant.

 * @param string $name The name of the constant.
 * @param int|string $value The value of the constant.
 * @return True if the constant was defined successfully, else False.
 * 
 *  Defines a constant if this one is not defined yet.
 */
function defifn($name, $value) {
	if( defined($name) ) {
		return false;
	}
	define($name, $value);
	return true;
}

/** Gets the directory path

 * @param $path The path get parent directory
 * @return string The secured path
 * @sa dirname()
 * 
 * Gets the parent directory path of $path
 */
function dirpath($path) {
	$dirname = dirname($path);
	return $dirname === '/' ? '/' : $dirname.'/';
}

/** Gets the path of a file/directory.

 * @param string $commonPath The common path
 * @param boolean $silent Do not throw exception if path does not exist
 * @return string The first valid path or null if there is no valid one.
 * @sa addSrcPath()
 * 
 * This function uses global variable $SRCPATHS to get the known paths.
 * It allows developers to get a dynamic path to a file.
 */
function pathOf($commonPath, $silent=false) {
	global $SRCPATHS;
	for( $i=count($SRCPATHS)-1; $i>=0; $i-- ) {
		if( file_exists($SRCPATHS[$i].$commonPath) ) {
			return $SRCPATHS[$i].$commonPath;
		}
	}
	if( $silent ) { return null; }
	throw new Exception('Path not found: '.$commonPath);
}

/** Checks if the path exists.

 * @param string $commonPath The common path.
 * @param string $path The output parameter to get the first valid path.
 * @sa pathOf()
 * 
 * This function uses pathOf() to determine possible path of $commonPath and checks if there is any file with this path in file system.
 */
function existsPathOf($commonPath, &$path=null) {
	return ($path=pathOf($commonPath, true))!==NULL;
}

/**
 * Add the path to the known paths
 * 
 * @param string $path The source path to add.
 * @return boolean True if the path was added.
 * @see pathOf()
 */
function addSrcPath($path) {
	global $SRCPATHS;
	if( in_array($path, $SRCPATHS) ) {
		return false;
	}
	$SRCPATHS[] = $path;
	return true;
}

/**
 * List all source paths
 * 
 * @return string[]
 */
function listSrcPath() {
	global $SRCPATHS;
	return $SRCPATHS;
}

/**
 * Include a directory
 * 
 * @param string $dir The directory to include.
 * @param array $importants The files in that are importants to load first.
 * @return int The number of files included.
 * 
 * Include all files with a name beginning by '_' in the directory $dir.
 * It browses recursively through sub-directories.
*/
function includeDir($dir, $importants=array()) {
	//Require to be immediatly available.
	$files = array_unique(array_merge($importants, scandir($dir)));
	
	$i=0;
	foreach($files as $file) {
		// If file is not readable or hidden, we pass.
		if( !is_readable($dir.$file) || $file[0] == '.' ) {
			continue;
		}
		//We don't check infinite file system loops.
		if( is_dir($dir.$file) ) {
			$i += includeDir($dir.$file.'/');
		} else if( $file[0] == '_' ) {
			require_once $dir.$file;
			$i++;
		}
	}
	return $i;
}

/**
 * Include a directory by source path
 * 
 * @param string $dir The directory to include.
 * @param array $importants The files in that are importants to load first.
 * @return int The number of files included.
 * @see includeDir()
 * 
 * Include all files with a name beginning by '_' in the directory $dir.
 * It browses recursively through sub-directories.
*/
function includePath($path, $importants=array()) {
	return includeDir(pathOf($path), $importants);
}

/**
 * Escape a text
 * 
 * @param string $str The string to escape
 * @param int $flags The flags of htmlentities()
 * @return string The escaped string

 * Escape the text $str from special characters.
 */
function escapeText($str, $flags=ENT_NOQUOTES) {
	return htmlentities(str_replace("\'", "'", $str), $flags, 'UTF-8', false);
}

// Experimental
function ob_end_to($min) {
	$min = max($min, 0);
	while( ob_get_level() > $min ) {
		ob_end_flush();
	}
}

/**
 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 */
define('HTTP_OK',						200);
define('HTTP_MOVED_PERMANENTLY',		301);
define('HTTP_FOUND',					302);
define('HTTP_MOVED_TEMPORARILY',		HTTP_FOUND);
define('HTTP_BAD_REQUEST',				400);
define('HTTP_UNAUTHORIZED',				401);
define('HTTP_FORBIDDEN',				403);
define('HTTP_NOT_FOUND',				404);
define('HTTP_INTERNAL_SERVER_ERROR',	500);

function http_response_codetext($code=null) {
	if( $code === null ) {
		$code = http_response_code();
	}
	static $codeTexts;
	if( !isset($codeTexts) ) {
		$codeTexts = array(
			100	=> 'Continue',
			101	=> 'Switching Protocols',
			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoritative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',
			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Moved Temporarily',
			303	=> 'See Other',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			402	=> 'Payment Required',
			403	=> 'Forbidden',
			404	=> 'Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Time-out',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Large',
			415	=> 'Unsupported Media Type',
			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Bad Gateway',
			503	=> 'Service Unavailable',
			504	=> 'Gateway Time-out',
			505	=> 'HTTP Version not supported',
		);
	}
	return isset($codeTexts[$code]) ? $codeTexts[$code] : 'Unknown';
}

function displayException(Exception $Exception, $action) {
	if( IS_CONSOLE ) {
		displayExceptionAsText($Exception, $action);
	} else {
		displayExceptionAsHTML($Exception, $action);
	}
}

function displayExceptionAsHTML(Exception $Exception, $action) {
	$code = $Exception->getCode();
	if( $code < 100 ) {
		$code = HTTP_INTERNAL_SERVER_ERROR;
	}
	http_response_code($code);
	die(convertExceptionAsHTMLPage($Exception, $code, $action));
}

function displayExceptionAsText(Exception $Exception, $action) {
	$code = $Exception->getCode();
	if( $code < 100 ) {
		$code = HTTP_INTERNAL_SERVER_ERROR;
	}
	die(convertExceptionAsText($Exception, $code, $action));
}

function typeOf($var) {
	$type	= gettype($var);
	if( $type === 'object' ) {
		return get_class($var);
	}
	return $type;
}

function displayRawException(Exception $Exception) {
	?>
	<h3><?php echo get_class($Exception); ?></h3>
	<blockquote class="exception_message">
		<?php echo $Exception->getMessage(); ?>
		<footer>In <cite><?php echo $Exception->getFile(); ?></cite> at line <?php echo $Exception->getLine(); ?></footer>
	</blockquote>
	<?php
	displayExceptionStackTrace($Exception);
}

function displayStackTrace($backtrace) {
	?>
	<ol>
	<?php
	foreach( $backtrace as $trace ) {
		// file, line, function, args
		if( !isset($trace['class']) ) {
			$trace['class']	= null;
		}
		if( !isset($trace['type']) ) {
			$trace['type']	= null;
		}
		$args	= '';
		if( isset($trace['args']) ) {
			foreach( $trace['args'] as $i => $arg ) {
				// 			debug('$arg', $arg);
				if( is_array($arg) ) {
					$argTxt	= '['.count($arg).']';
				} else
				if( is_closure($arg) ) {
					$argTxt	= '{closure}';
				} else {
					$argTxt	= ' "<span class="arg_value">'.escapeText( (is_object($arg) && !method_exists($arg, '__toString')) ? get_class($arg) : $arg.'' ).'</span>"';
				}
				$args .= ($i ? ', ' : '').'
		<span class="arg"><span class="arg_type">'.typeOf($arg).'</span> '.$argTxt.'</span>';
			}
		}
		?>
		<li class="trace">
			Call <?php echo $trace['class'].$trace['type'].$trace['function'].'('.$args.')' ?><br />
			<address>In <?php echo isset($trace['file']) ? $trace['file'].' at line '.$trace['line'] : 'an unknown file'; ?></address>
		</li>
			<?php
		}
		?>
	</ol>
	<?php
}

function displayExceptionStackTrace(Exception $Exception) {
	$backtrace = $Exception->getTrace();
	if( $Exception->getCode() == 1 && is_array($GLOBALS['DEBUG_BACKTRACE']) ) {
		$backtrace = $GLOBALS['DEBUG_BACKTRACE'];
	}
	displayStackTrace($backtrace);
}

function convertExceptionAsHTMLPage(Exception $Exception, $code, $action) {
	// TODO: Add resubmit button
	// TODO: Display already sent headers and contents
	// TODO: Externalize this and allow developers to ovverride it
	// Clean all buffers
	$buffer	= '';
	while( ob_get_level() ) {
		$buffer = ob_get_clean().$buffer;
	}
// 	debug('ob_get_level() => '.ob_get_level());
// 		debug_print_backtrace();
	ob_start();
	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>An error occurred :: Orpheus</title>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
</head>
<body>

	<div class="container">
		<div class="header clearfix">
			<h3 class="text-muted">Orpheus</h3>
		</div>
		<div class="linkmenu clearfix">
			<a class="pull-right" href="<?php echo DEFAULTLINK; ?>">Home</a>
		</div>
		<div class="panel panel-danger">
			<div class="panel-heading">An error occurred !</div>
			<div class="panel-body exception">
				<h3><?php echo $code.' '.http_response_codetext($code).'<small> - '.get_class($Exception).'</small>'; ?></h3>
				<blockquote class="exception_message">
					<?php echo $Exception->getMessage(); ?>
					<footer>In <cite><?php echo $Exception->getFile(); ?></cite> at line <?php echo $Exception->getLine(); ?></footer>
				</blockquote>
				
				<div class="sourcecode">
					<ul class="sourcecode_lines"><?php
						$excLine = $Exception->getLine();
						$fileContent = file_get_contents($Exception->getFile());
// 						$lines = substr_count($fileContent, "\n");
						$lines = substr_count($fileContent, PHP_EOL);
						for( $i=0; $i<=$lines; $i++ ) {
							echo '<li'.($excLine == $i+1 ? ' class="active"' : '').'>'.($i+1).'</li>';
						}
						// Make some css issues, easier without
// 						<div class="sourcecode_content"></div>
					?></ul>
					<div class="sourcecode_content">
						<?php echo highlight_string($fileContent, true); unset($fileContent); ?>
					</div>
				</div>
				<?php
// 				echo formatSourceAsHTML($Exception->getFile(), $Exception->getLine(), 4, 2);
				?>
			</div>
		</div>
		<div class="panel panel-danger">
			<div class="panel-heading">Here is the stacktrace...</div>
			<div class="panel-body exception">
				<?php displayExceptionStackTrace($Exception); ?>
			</div>
		</div>
		
		<?php
		if( trim($buffer) && class_exists('DelayedPageController', true) ) {
			try {
				$bufferSrc	= DelayedPageController::store(uniqid('error'), $buffer);
				?>
		<div class="panel panel-danger" >
			<div class="panel-heading">The buffer is not empty, maybe this could help you...</div>
			<div class="panel-body buffer">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" src="<?php echo $bufferSrc; ?>"></iframe>
				</div>
			</div>
		</div>
		<?php
			} catch( Exception $e ) {
				?>
		<div class="panel panel-danger">
			<div class="panel-heading">An exception occurred storing the delayed page...</div>
			<div class="panel-body buffer">
				<?php displayRawException($e); ?>
			</div>
		</div>
		<?php
				
			}
		}
		?>
	</div>
<style>
.header {
	padding-bottom: 20px;
	margin-bottom: 30px;
	border-bottom: 1px solid #e5e5e5;
}
.linkmenu {
	padding-right: 10px;
}
.arg_type {
	font-weight: bold;
	font-size: 0.9em;
}
.arg_value {
	white-space: nowrap;
	max-width: 120px;
	max-width: 15ch;
	text-overflow: ellipsis;
	display: inline-block;
	overflow: hidden;
	vertical-align: bottom;
	
	font-style: italic;
	cursor: pointer;
}
.arg_value.nolimit {
	max-width: none;
}
.sourcecode {
	height: 176px;/* 10+1 lines * line-height */
	line-height: 16px;
	overflow-y: scroll;
/* 	overflow-x: hidden; */
/* 	overflow-x: auto; */
	display: flex;
}
.sourcecode_lines {
	float: left;
	padding: 2px 2px 2px 6px;
	list-style: none;
	font-size: 12px;
	margin: 0;
	border-right: 1px solid #CCC;
	text-align: right;
}
/* 	padding: 2px 10px 2px 6px; */
.sourcecode_lines li:after {
	content: "\00a0";
	margin-left: 6px;
}
.sourcecode_lines li.active {
	background: #F2DEDE;
/* 	background: #ebccd1; */
	color: #a94442;
}
.sourcecode_lines li.active:after {
	content: ">";
	margin-left: 2px;
}
/*
.sourcecode_content {
}
*/
.sourcecode_content {
/* .sourcecode code { */
	width: 100%;
	height: 100%;
	white-space: nowrap;
}
.sourcecode_content code {
	line-height: inherit;
	display: block;
}
/* 	overflow-x: scroll; */
/* 	white-space: normal; */
/* 	word-wrap: break-word; */
/*
code {
	width: 100%;
	display: block;
	font-size: 12px;
	white-space: nowrap;
	overflow-x: scroll;
}
*/
</style>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$(".arg_value").click(function() {
		$(this).toggleClass("nolimit");
	});
// 	$(".sourcecode_content code").animate({
	$(".sourcecode").scrollTop(<?php
	        // Line height * (exception line - (1 + Lines before)
	        echo 16*($excLine-6);
	        ?>);
    /*
	$(".sourcecode").animate({
        scrollTop: <?php
        // Line height * (exception line - (1 + Lines before)
        echo 16*($excLine-6);
        ?>
    }, 1200);
    */
});
</script>
</body>
</html>
	<?php
	return ob_get_clean();
// 	$content	= ob_get_contents();
// 	ob_end_clean();
// 	debug('End of error report');
// 	return $content;
}

function convertExceptionAsText(Exception $Exception, $code, $action) {
	// Clean all buffers
// 	$buffer	= '';
	while( ob_get_level() ) {
		ob_end_clean();
// 		$buffer = ob_get_clean().$buffer;
	}
// 	debug('ob_get_level() => '.ob_get_level());
// 		debug_print_backtrace();
	ob_start();
	?>
*****************************************
************* ~  Orpheus  ~ *************
*****************************************
*********  Error Report System  *********
*****************************************

We caught an exception of type <?php echo get_class($Exception); ?>: 
 > <?php echo $Exception->getMessage(); ?> 
 > In <?php echo $Exception->getFile(); ?> at line <?php echo $Exception->getLine(); ?> 
 
<?php echo formatSourceAsText($Exception->getFile(), $Exception->getLine(), 4, 2); ?>

Stacktrace:<?php
	foreach( $Exception->getTrace() as $trace ) {
		// file, line, function, args
		if( !isset($trace['class']) ) {
			$trace['class']	= null;
		}
		if( !isset($trace['type']) ) {
			$trace['type']	= null;
		}
		$args	= '';
		foreach( $trace['args'] as $i => $arg ) {
			$args .= ($i ? ', ' : '').typeOf($arg).(is_array($arg) ? '['.count($arg).']' : ' '.$arg);
		}
		echo "
 - Call {$trace['class']}{$trace['type']}{$trace['function']}({$args})
   In ".(isset($trace['file']) ? $trace['file'].' at line '.$trace['line'] : 'an unknown file')."\n";
	}
	echo "\n";
	return ob_get_clean();
// 	$content	= ob_get_contents();
// 	ob_end_clean();
// 	debug('End of error report');
// 	return $content;
}

function formatSourceAsHTML($file, $lineNumber, $linesBefore, $linesAfter) {
	// Partial highlight not working, send all file
// 	$from	= max($lineNumber-$linesBefore, 0);
// 	$to		= $lineNumber+$linesAfter;
// 	$count	= 0;
// 	$string	= getFileLines($file, $from, $to, $count);
// 	$lines	= '';
// 	for( $line=$from; $line<$from+$count; $line++ ) {
// 		$lines	.= '<li>'.$line.($lineNumber==$line ? '&nbsp;&nbsp;>' : '').'</li>';
// 	}
	
	$string	= highlight_source($string, true);
	return <<<EOF
<div class="sourcecode">
	<ul class="sourcecode_lines">{$lines}</ul>
 	{$string}
</div>
EOF;
}

function formatSourceAsText($file, $activeLineNumber, $linesBefore, $linesAfter) {
// 	echo "formatSourceAsHTML($file, $lineNumber, $linesBefore, $linesAfter)\n";
	$from	= max($activeLineNumber-$linesBefore, 0);
	$to		= $activeLineNumber+$linesAfter;
	$count	= 0;
// 	echo "getFileLines($file, $from, $to, $count, true)\n";
	$lines	= getFileLines($file, $from, $to, $count, true);
	$lineLen= strlen($to);
	$result	= '';
	foreach( $lines as $lineNumber => $line ) {
		$result	.=
'| '.str_pad($lineNumber, $lineLen, ' ', STR_PAD_RIGHT).($lineNumber==$activeLineNumber ? ' >' : '  ').' | '.$line;
	}
	return $result;
// 	$lines	= '';
// 	for( $line=$from; $line<$from+$count; $line++ ) {
// 		$lines	.= '<li>'.$line.($lineNumber==$line ? '&nbsp;&nbsp;>' : '').'</li>';
// 	}
// 	$string	= highlight_source($string, true);
// 	return <<<EOF
// <div class="sourcecode">
// 	<ul class="sourcecode_lines">{$lines}</ul>
//  	{$string}
// </div>
// EOF;
}

function highlight_source($string, $return=false) {
// 	$string	= preg_replace();
// 	$result	= "<?php\n".$string;
	/*
	$length	= strlen($string);
	$spaces	= 0;
	$tabSpaces	= 4;
	$result	= '';
	for( $i=0; $i<$length; $i++ ) {
		$char	= $string[$i];
		$add	= $char;
		if( $char === "\t" ) {
			$add	= str_pad('', $tabSpaces-$spaces, ' ', STR_PAD_RIGHT);
			$spaces	= 0;
			
		} else
		if( $char === "\r" || $char === "\n" ) {
// 		if( $spaces >= $tabSpaces || in_array($char, array("\t", "\r", "\n")) ) {
			$spaces	= 0;
		} else {
			$spaces++;
		}
		if( $spaces >= $tabSpaces ) {
			$spaces	= 0;
		}
		$result	.= $add;
	}
	// NOT USED - The ; forces the parser to create another span element
	// The " " (space) forces the parser to create a multiline span element
// 	$str	= preg_replace("#&lt;\?php<br \/>#", '', highlight_string("<?php\n".$result, $return), 1, $count);
// 	debug('Replaced => '.$count);
// 	echo escapeText($str);
// 	return $str;
 */
	return highlight_string("<?php\n".$string, true);
// 	return preg_replace("#&lt;\?php<br \/>#", '', highlight_string("<?php\n".$result, true), 1, $count);
// 	return preg_replace("#&lt;\?php<br \/>#", '', highlight_string("<?php\n".$result, $return), 1, $count);
}

// function getFileLineContext($file, $lineNumber, &$linesBefore, $linesAfter) {
// 	return getFileLines($file, $lineNumber-$linesBefore, $lineNumber+$linesAfter);
// }

function getFileLines($file, $from, $to, &$count=0, $asArray=false) {
	if( is_string($file) ) {
		$file	= fopen($file, 'r');
	}
// 	$from	= max($from, 0);
// 	$lines	= '';
	$lines	= array();
	$c		= 0;
	$lineNb	= $from;
	while( ($line=fgets($file)) !== false ) {
		$c++;
		if( $c >= $from ) {
			if( $c > $to ) {
				break;
			}
// 			$lines	.= $line;
			$lines[$lineNb++]	= $line;
			$to;
// 			$count++;
		}
	}
	$count	= count($lines);
	return $asArray ? $lines : implode('', $lines);
}

/** Displays a variable as HTML
 * @param $message The data to display. Default value is an empty string.
 * @param $html True to add html tags. Default value is True.
 * @warning Use it only for debugs.

 * Displays a variable as HTML.
 * If the constant TERMINAL is defined, parameter $html is forced to False.
*/
function text($message = '', $html = true) {
	if( IS_CONSOLE ) {
		$html = false;
	}
	if( !is_scalar($message) ) {
		$message = print_r($message, 1);
		if( $html ) {
			$message = '<pre>'.$message.'</pre>';
		}
	}
	echo $message.($html ? '<br />' : '')."\n";
	if( IS_CONSOLE ) {
		flush();
	}
}

function debug($s, $d=-1) {
	if( $d !== -1 ) {
		$s .= ': '.htmlSecret($d);
	}
	text($s);
}

function htmlSecret($message) {
	if( $message===NULL ) {
		$message = '{NULL}';
	} else if( $message === false ) {
		$message = '{FALSE}';
	} else if( $message === true ) {
		$message = '{TRUE}';
	} else if( !is_scalar($message) ) {
		$message = '<pre>'.print_r($message, 1).'</pre>';
	}
	return '<button type="button" onclick="this.nextSibling.style.display = this.nextSibling.style.display === \'none\' ? \'block\' : \'none\'; return 0;">Show</button><div style="display: none;">'.$message.'</div>';
// 	return '<button type="button" onclick="$(this).next().toggle(); return 0;">'.t('Show').'</button><div style="display: none;">'.$message.'</div>';
}

/** Limits the length of a string
 * @param string $string The string to limit length.
 * @param int $max The maximum length of the string.
 * @param int $strend A string to append to the shortened string.
 * @return The shortened string.

 * Limits the length of a string and append $strend.
 * This function do it cleanly, it tries to cut before a word.
*/
function str_limit($string, $max, $strend='...') {
	$max = (int) $max;
	if( $max <= 0 ) {
		return '';
	}
	if( strlen($string) <= $max ) {
		return $string;
	}
	$subStr = substr($string, 0, $max);
	if( !in_array($string[$max], array("\n", "\r", "\t", " ")) ) {
		$lSpaceInd = strrpos($subStr, ' ');
		if( $max-$lSpaceInd < 10 ) {
			$subStr = substr($string, 0, $lSpaceInd);
		}
	}
	return $subStr.$strend;
}
